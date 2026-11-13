<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProductVariant;

echo "--- Variant Stock Details ---" . PHP_EOL;
$variants = ProductVariant::with('product')->get();
$total = 0;
foreach ($variants as $variant) {
    echo "SKU: {$variant->sku} | Stock: {$variant->stock} | Product: {$variant->product->name}" . PHP_EOL;
    $total += $variant->stock;
}
echo "Total Sum of Stock: {$total}" . PHP_EOL;
