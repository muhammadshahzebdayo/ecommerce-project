<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    protected function getStats(): array
    {
        // 1. Total Sales Revenue (Only count delivered orders where parcel is received)
        $totalSales = Order::where('status', 'delivered')->sum('total_amount');

        // Fetch categories to calculate split stats
        $shoesCategory = Category::where('slug', 'gents-shoes')->first();
        $undergarmentsCategory = Category::where('slug', 'female-undergarments')->first();

        // Subcategory IDs for female undergarments (parent + children)
        $undergarmentsIds = [];
        if ($undergarmentsCategory) {
            $undergarmentsIds = Category::where('parent_id', $undergarmentsCategory->id)
                ->orWhere('id', $undergarmentsCategory->id)
                ->pluck('id')
                ->toArray();
        }

        $shoesIds = [];
        if ($shoesCategory) {
            $shoesIds = Category::where('parent_id', $shoesCategory->id)
                ->orWhere('id', $shoesCategory->id)
                ->pluck('id')
                ->toArray();
        }

        // --- Gents Shoes Stock Calculations ---
        $shoesRemaining = ProductVariant::whereHas('product', function ($q) use ($shoesIds) {
            $q->whereIn('category_id', $shoesIds);
        })->sum('stock');

        $shoesSold = OrderItem::whereHas('variant.product', function ($q) use ($shoesIds) {
            $q->whereIn('category_id', $shoesIds);
        })->whereHas('order', fn ($q) => $q->where('status', 'delivered'))->sum('quantity');

        $shoesPurchased = $shoesRemaining + $shoesSold;

        // --- Ladies Undergarments Stock Calculations ---
        $ugRemaining = ProductVariant::whereHas('product', function ($q) use ($undergarmentsIds) {
            $q->whereIn('category_id', $undergarmentsIds);
        })->sum('stock');

        $ugSold = OrderItem::whereHas('variant.product', function ($q) use ($undergarmentsIds) {
            $q->whereIn('category_id', $undergarmentsIds);
        })->whereHas('order', fn ($q) => $q->where('status', 'delivered'))->sum('quantity');

        $ugPurchased = $ugRemaining + $ugSold;

        // --- Inventory Market Worth (Remaining stock * price) ---
        $inventoryValue = ProductVariant::with('product')->get()->sum(function ($variant) {
            $price = $variant->price ?? $variant->product?->base_price ?? 0;
            return $variant->stock * $price;
        });

        // --- Orders Stats ---
        $pendingOrders = Order::where('status', 'pending')->count();
        $dispatchedOrders = Order::whereIn('status', ['processing', 'shipped', 'delivered'])->count();
        $totalProducts = Product::count();

        return [
            Stat::make('Total Revenue (Delivered)', 'Rs. ' . number_format($totalSales))
                ->description('Earnings from completed sales')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Inventory Worth (Remaining)', 'Rs. ' . number_format($inventoryValue))
                ->description('Market value of warehouse stock')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Total Products', $totalProducts)
                ->description('Product items in catalog')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('primary'),

            // Gents Shoes Inventory Card
            Stat::make('Gents Shoes Stock', number_format($shoesRemaining) . ' Units Left')
                ->description("Sold: {$shoesSold} | Purchased: {$shoesPurchased}")
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('info'),

            // Ladies Undergarments Inventory Card
            Stat::make('Ladies Undergarments', number_format($ugRemaining) . ' Units Left')
                ->description("Sold: {$ugSold} | Purchased: {$ugPurchased}")
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('warning'),

            Stat::make('Pending Orders', $pendingOrders)
                ->description('Awaiting review')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 0 ? 'warning' : 'gray'),

            Stat::make('Dispatched Orders', $dispatchedOrders)
                ->description('Shipped / Processing / Delivered')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
