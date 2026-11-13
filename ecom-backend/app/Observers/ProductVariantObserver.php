<?php

namespace App\Observers;

use App\Models\ProductVariant;

class ProductVariantObserver
{
    /**
     * Handle the ProductVariant "creating" event.
     */
    public function creating(ProductVariant $productVariant): void
    {
        if (empty($productVariant->sku)) {
            $product = $productVariant->product;
            $category = $product->category;

            // 1. Get Category Prefix (e.g. GSH)
            $catPrefix = \Illuminate\Support\Str::upper($category->sku_prefix ?? 'PROD');

            // 2. Get Product Code (first 3 letters of Product Name)
            $cleanedProductName = preg_replace('/[^A-Za-z0-9]/', '', $product->name);
            $prodCode = \Illuminate\Support\Str::upper(substr($cleanedProductName, 0, 3));

            // Set a temporary unique SKU
            $productVariant->sku = $catPrefix . '-' . $prodCode . '-TEMP-' . uniqid();
        }
    }
}
