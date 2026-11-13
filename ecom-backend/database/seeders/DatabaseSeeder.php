<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductVariant;
use App\Models\VariantOptionValue;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a Default Admin User for Filament
        User::firstOrCreate([
            'email' => 'admin@test.com',
        ], [
            'name' => 'Admin User',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        // Create a Default Finance User
        User::firstOrCreate([
            'email' => 'finance@test.com',
        ], [
            'name' => 'Finance Manager',
            'password' => bcrypt('password123'),
            'role' => 'finance',
        ]);

        // Seed Delivery Partners
        foreach (['TCS', 'M&P', 'Leopards'] as $partnerName) {
            \App\Models\DeliveryPartner::firstOrCreate([
                'name' => $partnerName,
            ], [
                'contact_person' => "{$partnerName} Manager",
                'phone' => '03001234567',
                'active' => true,
            ]);
        }

        // Seed Physical Stores
        $lahoreStore = \App\Models\PhysicalStore::firstOrCreate([
            'name' => 'Lahore Emporium Mall Store',
        ], [
            'address' => 'Shop 25, Emporium Mall, Lahore',
            'monthly_rent' => 85000.00,
            'status' => 'Active',
        ]);

        $karachiStore = \App\Models\PhysicalStore::firstOrCreate([
            'name' => 'Karachi LuckyOne Mall Store',
        ], [
            'address' => 'Shop 42, LuckyOne Mall, Karachi',
            'monthly_rent' => 95000.00,
            'status' => 'Active',
        ]);

        // Seed Employees
        \App\Models\Employee::firstOrCreate([
            'name' => 'Ali Raza',
            'phone' => '03217654321',
        ], [
            'email' => 'ali@brand.com',
            'employee_type' => 'Admin Office',
            'role' => 'Admin Officer',
            'salary' => 45000.00,
            'status' => 'Active',
            'hired_at' => '2026-01-15',
        ]);

        \App\Models\Employee::firstOrCreate([
            'name' => 'Zainab Bibi',
            'phone' => '03339876543',
        ], [
            'email' => 'zainab@brand.com',
            'employee_type' => 'Physical Store',
            'physical_store_id' => $lahoreStore->id,
            'role' => 'Store Manager',
            'salary' => 35000.00,
            'status' => 'Active',
            'hired_at' => '2026-03-01',
        ]);

        // 2. Create Categories
        $gentsShoes = Category::firstOrCreate([
            'name' => 'Gents Shoes',
        ], [
            'slug' => 'gents-shoes',
            'sku_prefix' => 'GSH',
        ]);

        $undergarments = Category::firstOrCreate([
            'name' => 'Female Undergarments',
        ], [
            'slug' => 'female-undergarments',
            'sku_prefix' => 'LUG',
        ]);

        // 3. Create Products
        // Product A: Oxford Leather Shoes
        $oxford = Product::firstOrCreate([
            'name' => 'Oxford Leather Shoes',
        ], [
            'category_id' => $gentsShoes->id,
            'slug' => 'oxford-leather-shoes',
            'description' => 'Premium formal leather shoes for gentlemen. Handcrafted in Pakistan.',
            'base_price' => 5999.00,
            'is_active' => true,
        ]);

        // Product B: Peshawari Chappal Classic
        $peshawari = Product::firstOrCreate([
            'name' => 'Peshawari Chappal Classic',
        ], [
            'category_id' => $gentsShoes->id,
            'slug' => 'peshawari-chappal-classic',
            'description' => 'Traditional Peshawari Kaptaan Chappal in authentic double sole leather.',
            'base_price' => 4500.00,
            'is_active' => true,
        ]);

        // Product C: Lace Wireless Bra
        $laceBra = Product::firstOrCreate([
            'name' => 'Lace Wireless Bra',
        ], [
            'category_id' => $undergarments->id,
            'slug' => 'lace-wireless-bra',
            'description' => 'Ultra comfortable wireless lace bra, designed for maximum support and premium fit.',
            'base_price' => 2499.00,
            'is_active' => true,
        ]);

        // 4. Create Product Attributes
        $oxfordSize = ProductAttribute::firstOrCreate(['product_id' => $oxford->id, 'name' => 'Size']);
        $oxfordColor = ProductAttribute::firstOrCreate(['product_id' => $oxford->id, 'name' => 'Color']);

        $peshawariSize = ProductAttribute::firstOrCreate(['product_id' => $peshawari->id, 'name' => 'Size']);
        $peshawariColor = ProductAttribute::firstOrCreate(['product_id' => $peshawari->id, 'name' => 'Color']);

        $braSize = ProductAttribute::firstOrCreate(['product_id' => $laceBra->id, 'name' => 'Size']);
        $braColor = ProductAttribute::firstOrCreate(['product_id' => $laceBra->id, 'name' => 'Color']);

        // 5. Create Variants and Option Values (This triggers our observers to set SKU automatically)
        // Oxford Variant 1: Black, Size 42
        $varOxford1 = $oxford->variants()->create([
            'price' => 5999.00,
            'stock' => 15,
        ]);
        $varOxford1->optionValues()->create([
            'product_attribute_id' => $oxfordSize->id,
            'value' => '42',
        ]);
        $varOxford1->optionValues()->create([
            'product_attribute_id' => $oxfordColor->id,
            'value' => 'Black',
        ]);

        // Oxford Variant 2: Brown, Size 43
        $varOxford2 = $oxford->variants()->create([
            'price' => 6199.00,
            'stock' => 10,
        ]);
        $varOxford2->optionValues()->create([
            'product_attribute_id' => $oxfordSize->id,
            'value' => '43',
        ]);
        $varOxford2->optionValues()->create([
            'product_attribute_id' => $oxfordColor->id,
            'value' => 'Brown',
        ]);

        // Peshawari Variant 1: Tan, Size 41
        $varPesh1 = $peshawari->variants()->create([
            'price' => 4500.00,
            'stock' => 20,
        ]);
        $varPesh1->optionValues()->create([
            'product_attribute_id' => $peshawariSize->id,
            'value' => '41',
        ]);
        $varPesh1->optionValues()->create([
            'product_attribute_id' => $peshawariColor->id,
            'value' => 'Tan',
        ]);

        // Lace Bra Variant 1: White, Size 34B
        $varBra1 = $laceBra->variants()->create([
            'price' => 2499.00,
            'stock' => 25,
        ]);
        $varBra1->optionValues()->create([
            'product_attribute_id' => $braSize->id,
            'value' => '34B',
        ]);
        $varBra1->optionValues()->create([
            'product_attribute_id' => $braColor->id,
            'value' => 'White',
        ]);

        // Lace Bra Variant 2: Black, Size 36C
        $varBra2 = $laceBra->variants()->create([
            'price' => 2699.00,
            'stock' => 18,
        ]);
        $varBra2->optionValues()->create([
            'product_attribute_id' => $braSize->id,
            'value' => '36C',
        ]);
        $varBra2->optionValues()->create([
            'product_attribute_id' => $braColor->id,
            'value' => 'Black',
        ]);
    }
}
