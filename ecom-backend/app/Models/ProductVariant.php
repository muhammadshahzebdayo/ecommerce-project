<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\ProductVariantObserver;

#[ObservedBy([ProductVariantObserver::class])]
class ProductVariant extends Model
{
    public array $tempOptionValues = [];
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'stock',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function optionValues(): HasMany
    {
        return $this->hasMany(VariantOptionValue::class);
    }

    public function generateSku(): void
    {
        $product = $this->product;
        $category = $product->category;

        // 1. Get Category Prefix
        $catPrefix = \Illuminate\Support\Str::upper($category->sku_prefix ?? 'PROD');

        // 2. Get Product Code
        $cleanedProductName = preg_replace('/[^A-Za-z0-9]/', '', $product->name);
        $prodCode = \Illuminate\Support\Str::upper(substr($cleanedProductName, 0, 3));

        // 3. Get Option Values sorted by attribute id for consistency
        $optionCodes = $this->optionValues()
            ->with('attribute')
            ->get()
            ->sortBy('product_attribute_id')
            ->map(function ($val) {
                $cleaned = preg_replace('/[^A-Za-z0-9]/', '', $val->value);
                return \Illuminate\Support\Str::upper(substr($cleaned, 0, 3));
            })
            ->implode('-');

        $baseSku = $catPrefix . '-' . $prodCode;
        if (!empty($optionCodes)) {
            $baseSku .= '-' . $optionCodes;
        }

        // Ensure uniqueness
        $sku = $baseSku;
        $counter = 1;
        while (self::where('sku', $sku)->where('id', '!=', $this->id)->exists()) {
            $sku = $baseSku . '-' . $counter;
            $counter++;
        }

        $this->timestamps = false;
        $this->update(['sku' => $sku]);
        $this->timestamps = true;
    }
}
