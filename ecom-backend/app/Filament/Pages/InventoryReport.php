<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use BackedEnum;
use Filament\Pages\Page;

class InventoryReport extends Page
{
    protected string $view = 'filament.pages.inventory-report';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-bar';
    protected static ?string $navigationLabel = 'Inventory Report';
    protected static ?string $title = 'Inventory Report';
    protected static string|\UnitEnum|null $navigationGroup = 'Inventory & Catalog';
    protected static ?int $navigationSort = 4;

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    // View data arrays
    public array $overallStats = [];
    public array $footwearStats = [];
    public array $undergarmentsStats = [];
    public array $subcategoryBreakdown = [];
    public array $variantDetails = [];
    public array $lowStockAlerts = [];
    public array $topSellingProducts = [];
    public array $stockHealth = [];

    public function mount(): void
    {
        $this->calculateStats();
    }

    public function calculateStats(): void
    {
        // 1. Fetch root categories
        $shoesCategory = Category::where('slug', 'gents-shoes')->first();
        $undergarmentsCategory = Category::where('slug', 'female-undergarments')->first();

        // 2. Resolve subcategories for female undergarments
        $undergarmentsSubcategories = [];
        $undergarmentsIds = [];
        if ($undergarmentsCategory) {
            $undergarmentsSubcategories = Category::where('parent_id', $undergarmentsCategory->id)->get();
            $undergarmentsIds = $undergarmentsSubcategories->pluck('id')->push($undergarmentsCategory->id)->toArray();
        }

        $shoesIds = [];
        if ($shoesCategory) {
            $shoesIds = Category::where('parent_id', $shoesCategory->id)
                ->orWhere('id', $shoesCategory->id)
                ->pluck('id')
                ->toArray();
        }

        // 3. Overall Statistics
        $totalSalesAmount = Order::where('status', 'delivered')->sum('total_amount');
        $totalSoldUnits = OrderItem::whereHas('order', fn ($q) => $q->where('status', 'delivered'))->sum('quantity');
        $totalRemainingInventory = ProductVariant::sum('stock');
        $totalPurchasedInventory = $totalRemainingInventory + $totalSoldUnits;
        
        $totalInventoryWorth = ProductVariant::with('product')->get()->sum(function ($variant) {
            $price = $variant->price ?? $variant->product?->base_price ?? 0;
            return $variant->stock * $price;
        });

        $this->overallStats = [
            'revenue' => $totalSalesAmount,
            'sold_units' => $totalSoldUnits,
            'remaining_units' => $totalRemainingInventory,
            'purchased_units' => $totalPurchasedInventory,
            'worth' => $totalInventoryWorth,
        ];

        // 4. Gents Shoes Inventory Calculations
        $shoesRemaining = ProductVariant::whereHas('product', fn ($q) => $q->whereIn('category_id', $shoesIds))->sum('stock');
        $shoesSold = OrderItem::whereHas('variant.product', fn ($q) => $q->whereIn('category_id', $shoesIds))
            ->whereHas('order', fn ($q) => $q->where('status', 'delivered'))->sum('quantity');
        $shoesPurchased = $shoesRemaining + $shoesSold;

        $shoesWorth = ProductVariant::whereHas('product', fn ($q) => $q->whereIn('category_id', $shoesIds))
            ->with('product')->get()->sum(function ($variant) {
                return $variant->stock * ($variant->price ?? $variant->product?->base_price ?? 0);
            });

        $this->footwearStats = [
            'remaining' => $shoesRemaining,
            'sold' => $shoesSold,
            'purchased' => $shoesPurchased,
            'worth' => $shoesWorth,
        ];

        // 5. Ladies Undergarments Inventory Calculations (Aggregated)
        $ugRemaining = ProductVariant::whereHas('product', fn ($q) => $q->whereIn('category_id', $undergarmentsIds))->sum('stock');
        $ugSold = OrderItem::whereHas('variant.product', fn ($q) => $q->whereIn('category_id', $undergarmentsIds))
            ->whereHas('order', fn ($q) => $q->where('status', 'delivered'))->sum('quantity');
        $ugPurchased = $ugRemaining + $ugSold;

        $ugWorth = ProductVariant::whereHas('product', fn ($q) => $q->whereIn('category_id', $undergarmentsIds))
            ->with('product')->get()->sum(function ($variant) {
                return $variant->stock * ($variant->price ?? $variant->product?->base_price ?? 0);
            });

        $this->undergarmentsStats = [
            'remaining' => $ugRemaining,
            'sold' => $ugSold,
            'purchased' => $ugPurchased,
            'worth' => $ugWorth,
        ];

        // 6. Subcategory Breakdown for Female Undergarments
        $this->subcategoryBreakdown = [];
        foreach ($undergarmentsSubcategories as $subcat) {
            $subcatRemaining = ProductVariant::whereHas('product', fn ($q) => $q->where('category_id', $subcat->id))->sum('stock');
            $subcatSold = OrderItem::whereHas('variant.product', fn ($q) => $q->where('category_id', $subcat->id))
                ->whereHas('order', fn ($q) => $q->where('status', 'delivered'))->sum('quantity');
            $subcatPurchased = $subcatRemaining + $subcatSold;

            $this->subcategoryBreakdown[] = [
                'name' => $subcat->name,
                'sku_prefix' => $subcat->sku_prefix ?? 'N/A',
                'remaining' => $subcatRemaining,
                'sold' => $subcatSold,
                'purchased' => $subcatPurchased,
            ];
        }

        // 7. Granular Product Variant details breakdown
        $variants = ProductVariant::with(['product.category'])->get();
        $this->variantDetails = [];
        foreach ($variants as $variant) {
            $pName = $variant->product?->name ?? 'Deleted Product';
            $pCategory = $variant->product?->category?->name ?? 'Uncategorized';
            
            $varSold = OrderItem::where('product_variant_id', $variant->id)
                ->whereHas('order', fn ($q) => $q->where('status', 'delivered'))->sum('quantity');
            $varPurchased = $variant->stock + $varSold;

            $this->variantDetails[] = [
                'product' => $pName,
                'category' => $pCategory,
                'sku' => $variant->sku,
                'stock' => $variant->stock,
                'sold' => $varSold,
                'purchased' => $varPurchased,
                'price' => $variant->price ?? $variant->product?->base_price ?? 0,
            ];
        }

        // 8. Low Stock Alerts (Critical items with stock <= 5)
        $lowStockVariants = ProductVariant::where('stock', '<=', 5)
            ->with(['product.category'])
            ->orderBy('stock', 'asc')
            ->get();
        $this->lowStockAlerts = [];
        foreach ($lowStockVariants as $variant) {
            $this->lowStockAlerts[] = [
                'product' => $variant->product?->name ?? 'N/A',
                'sku' => $variant->sku,
                'stock' => $variant->stock,
                'status' => $variant->stock == 0 ? 'Out of Stock' : 'Low Stock',
            ];
        }

        // 9. Top Performing / Best Sellers (Based on Delivered quantities)
        $topSelling = OrderItem::whereHas('order', fn ($q) => $q->where('status', 'delivered'))
            ->selectRaw('product_variant_id, sum(quantity) as total_sold')
            ->groupBy('product_variant_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->with('variant.product')
            ->get();

        $this->topSellingProducts = [];
        foreach ($topSelling as $item) {
            if ($item->variant) {
                $price = $item->variant->price ?? $item->variant->product?->base_price ?? 0;
                $this->topSellingProducts[] = [
                    'product' => $item->variant->product?->name ?? 'N/A',
                    'sku' => $item->variant->sku,
                    'sold' => $item->total_sold,
                    'revenue' => $item->total_sold * $price,
                ];
            }
        }

        // 10. Stock Health Index Counts
        $lowStockCount = ProductVariant::where('stock', '<=', 5)->where('stock', '>', 0)->count();
        $outOfStockCount = ProductVariant::where('stock', 0)->count();
        $inStockCount = ProductVariant::where('stock', '>', 5)->count();
        $totalVariantsCount = ProductVariant::count();
        
        $this->stockHealth = [
            'in_stock' => $inStockCount,
            'low_stock' => $lowStockCount,
            'out_of_stock' => $outOfStockCount,
            'total' => $totalVariantsCount,
            'health_ratio' => $totalVariantsCount > 0 ? round(($inStockCount / $totalVariantsCount) * 100) : 100,
        ];
    }
}
