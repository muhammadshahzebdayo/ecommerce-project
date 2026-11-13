<?php

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductVariant;
use App\Models\VariantOptionValue;
use Illuminate\Support\Facades\DB;

DB::transaction(function () {
    // 1. Delete category 3 (Female Bra)
    Category::where('slug', 'female-bra')->delete();
    echo "Deleted 'Female Bra' category.\n";

    // 2. Fetch or create Parent Category (Female Undergarments)
    $parent = Category::firstOrCreate([
        'slug' => 'female-undergarments'
    ], [
        'name' => 'Female Undergarments',
        'sku_prefix' => 'LUG'
    ]);
    echo "Confirmed parent category 'Female Undergarments' (ID: {$parent->id}).\n";

    // 3. Create Subcategories under ID 2 (Female Undergarments)
    $sub1 = Category::firstOrCreate(['slug' => 'lace-bras'], [
        'name' => 'Lace Bras',
        'parent_id' => $parent->id,
        'sku_prefix' => 'LBR'
    ]);
    echo "Created/Confirmed subcategory 'Lace Bras' (ID: {$sub1->id}).\n";

    $sub2 = Category::firstOrCreate(['slug' => 'nighty-loungewear'], [
        'name' => 'Nighty & Loungewear',
        'parent_id' => $parent->id,
        'sku_prefix' => 'NIG'
    ]);
    echo "Created/Confirmed subcategory 'Nighty & Loungewear' (ID: {$sub2->id}).\n";

    $sub3 = Category::firstOrCreate(['slug' => 'panty-packs'], [
        'name' => 'Panty Packs',
        'parent_id' => $parent->id,
        'sku_prefix' => 'PTP'
    ]);
    echo "Created/Confirmed subcategory 'Panty Packs' (ID: {$sub3->id}).\n";

    $sub4 = Category::firstOrCreate(['slug' => 'shapewear'], [
        'name' => 'Shapewear',
        'parent_id' => $parent->id,
        'sku_prefix' => 'SHW'
    ]);
    echo "Created/Confirmed subcategory 'Shapewear' (ID: {$sub4->id}).\n";

    // 4. Reassign existing product 'Lace Wireless Bra' to the new 'Lace Bras' subcategory
    $braProduct = Product::where('slug', 'lace-wireless-bra')->first();
    if ($braProduct) {
        $braProduct->category_id = $sub1->id;
        $braProduct->save();
        echo "Reassigned 'Lace Wireless Bra' to subcategory 'Lace Bras'.\n";
    }

    // 5. Create a new product 'Silk Satin Nighty Set' under 'Nighty & Loungewear'
    $nightyProduct = Product::firstOrCreate(['slug' => 'silk-satin-nighty'], [
        'category_id' => $sub2->id,
        'name' => 'Silk Satin Nighty Set',
        'description' => 'Premium soft silk satin nighty set with matching robe. Perfect combination of elegant design and nightly comfort.',
        'base_price' => 3499.00,
        'is_active' => true
    ]);
    echo "Created product 'Silk Satin Nighty Set'.\n";

    // Add Attributes for Nighty Product
    $nightySize = ProductAttribute::firstOrCreate(['product_id' => $nightyProduct->id, 'name' => 'Size']);
    $nightyColor = ProductAttribute::firstOrCreate(['product_id' => $nightyProduct->id, 'name' => 'Color']);

    // Add Variant 1 (M, Black)
    $var1 = ProductVariant::firstOrCreate([
        'product_id' => $nightyProduct->id,
        'price' => 3499.00,
        'stock' => 12
    ]);
    
    // Check and create option values if not present
    if ($var1->optionValues()->count() === 0) {
        $var1->optionValues()->create([
            'product_attribute_id' => $nightySize->id,
            'value' => 'M'
        ]);
        $var1->optionValues()->create([
            'product_attribute_id' => $nightyColor->id,
            'value' => 'Black'
        ]);
    }

    // Add Variant 2 (L, Pink)
    $var2 = ProductVariant::firstOrCreate([
        'product_id' => $nightyProduct->id,
        'price' => 3699.00,
        'stock' => 8
    ]);
    if ($var2->optionValues()->count() === 0) {
        $var2->optionValues()->create([
            'product_attribute_id' => $nightySize->id,
            'value' => 'L'
        ]);
        $var2->optionValues()->create([
            'product_attribute_id' => $nightyColor->id,
            'value' => 'Pink'
        ]);
    }
    
    echo "Created variants and attributes for 'Silk Satin Nighty Set'.\n";
});

echo "Database successfully updated!\n";
