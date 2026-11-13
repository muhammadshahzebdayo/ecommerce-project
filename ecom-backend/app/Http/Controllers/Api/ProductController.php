<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Get categories list with subcategories for navigation.
     */
    public function categories()
    {
        $categories = Category::with('subcategories')
            ->whereNull('parent_id')
            ->get();

        return response()->json($categories);
    }

    /**
     * Get products list with dynamic filters (Category, Size, Color, Price).
     */
    public function index(Request $request)
    {
        $query = Product::query()->with(['category', 'attributes', 'variants.optionValues.attribute']);

        // Filter by category slug
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('base_price', '>=', floatval($request->min_price));
        }
        if ($request->has('max_price')) {
            $query->where('base_price', '<=', floatval($request->max_price));
        }

        // Filter by Size
        if ($request->has('size')) {
            $sizes = is_array($request->size) ? $request->size : explode(',', $request->size);
            $query->whereHas('variants.optionValues', function ($q) use ($sizes) {
                $q->whereIn('value', $sizes)
                  ->whereHas('attribute', function ($attrQuery) {
                      $attrQuery->where('name', 'Size');
                  });
            });
        }

        // Filter by Color
        if ($request->has('color')) {
            $colors = is_array($request->color) ? $request->color : explode(',', $request->color);
            $query->whereHas('variants.optionValues', function ($q) use ($colors) {
                $q->whereIn('value', $colors)
                  ->whereHas('attribute', function ($attrQuery) {
                      $attrQuery->where('name', 'Color');
                  });
            });
        }

        // Return only active products
        $products = $query->where('is_active', true)->paginate(12);

        return response()->json($products);
    }

    /**
     * Get single product details by slug.
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'attributes', 'variants.optionValues.attribute'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json($product);
    }

    /**
     * Secure Checkout API: Validates stock and processes cart items.
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $items = $request->items;
        $total = 0;
        $validatedItems = [];
        $orderItemsToCreate = [];

        // DB Transaction ensures stock deduction is atomic and safe
        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                // Lock the row for update to prevent race conditions in concurrent orders
                $variant = ProductVariant::with('product')->lockForUpdate()->find($item['variant_id']);

                if (!$variant) {
                    throw new \Exception("Product variant not found.");
                }

                // 1. Check stock level
                if ($variant->stock < $item['quantity']) {
                    throw new \Exception("Item {$variant->sku} has insufficient stock. Only {$variant->stock} left.");
                }

                // 2. Deduct inventory
                $variant->decrement('stock', $item['quantity']);

                // 3. Compute price (fallback to base price if variant price is null)
                $price = $variant->price ?? $variant->product->base_price;
                $subtotal = $price * $item['quantity'];
                $total += $subtotal;

                $orderItemsToCreate[] = [
                    'product_variant_id' => $variant->id,
                    'sku' => $variant->sku,
                    'product_name' => $variant->product->name,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                ];

                $validatedItems[] = [
                    'sku' => $variant->sku,
                    'product_name' => $variant->product->name,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                ];
            }

            // 4. Persist the Order in the database
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'total_amount' => $total,
                'status' => 'pending',
            ]);

            // 5. Persist the OrderItems
            foreach ($orderItemsToCreate as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_summary' => [
                    'order_id' => $order->id,
                    'customer' => $request->customer_name,
                    'email' => $request->customer_email,
                    'total_amount' => $total,
                    'items' => $validatedItems,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
