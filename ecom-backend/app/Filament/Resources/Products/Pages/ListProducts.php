<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('importCsv')
                ->label('Import Products (CSV)')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('csv_file')
                        ->label('CSV File')
                        ->acceptedFileTypes(['text/csv', 'application/vnd.ms-excel', 'text/plain'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $filePath = Storage::disk('public')->path($data['csv_file']);

                    if (($handle = fopen($filePath, 'r')) !== false) {
                        // Read headers
                        $headers = fgetcsv($handle, 1000, ',');

                        if ($headers) {
                            // Trim whitespace from headers
                            $headers = array_map('trim', $headers);

                            // Let's parse rows
                            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                                $rowData = array_combine($headers, $row);

                                // 1. Get or Create Category
                                $categoryName = trim($rowData['category'] ?? 'General');
                                $skuPrefix = trim($rowData['category_prefix'] ?? 'GEN');
                                if (empty($categoryName)) {
                                    continue;
                                }
                                $category = \App\Models\Category::firstOrCreate([
                                    'name' => $categoryName,
                                ], [
                                    'slug' => \Illuminate\Support\Str::slug($categoryName),
                                    'sku_prefix' => $skuPrefix,
                                ]);

                                // 2. Get or Create Product
                                $productName = trim($rowData['product_name'] ?? '');
                                $description = trim($rowData['description'] ?? '');
                                $basePrice = floatval($rowData['base_price'] ?? 0);
                                if (empty($productName)) {
                                    continue;
                                }
                                $product = \App\Models\Product::firstOrCreate([
                                    'name' => $productName,
                                ], [
                                    'category_id' => $category->id,
                                    'slug' => \Illuminate\Support\Str::slug($productName),
                                    'description' => $description,
                                    'base_price' => $basePrice,
                                    'is_active' => true,
                                ]);

                                // 3. Get or Create Attributes: Size and Color if provided
                                $attributes = [];
                                if (!empty($rowData['size'])) {
                                    $sizeAttr = \App\Models\ProductAttribute::firstOrCreate([
                                        'product_id' => $product->id,
                                        'name' => 'Size',
                                    ]);
                                    $attributes['Size'] = [
                                        'model' => $sizeAttr,
                                        'value' => trim($rowData['size'])
                                    ];
                                }
                                if (!empty($rowData['color'])) {
                                    $colorAttr = \App\Models\ProductAttribute::firstOrCreate([
                                        'product_id' => $product->id,
                                        'name' => 'Color',
                                    ]);
                                    $attributes['Color'] = [
                                        'model' => $colorAttr,
                                        'value' => trim($rowData['color'])
                                    ];
                                }

                                // 4. Create Product Variant
                                $variantPrice = !empty($rowData['variant_price']) ? floatval($rowData['variant_price']) : $basePrice;
                                $stock = intval($rowData['stock'] ?? 0);

                                $variant = $product->variants()->create([
                                    'price' => $variantPrice,
                                    'stock' => $stock,
                                ]);

                                // 5. Create Option Values (this triggers SKU generation)
                                foreach ($attributes as $attrName => $attrData) {
                                    $variant->optionValues()->create([
                                        'product_attribute_id' => $attrData['model']->id,
                                        'value' => $attrData['value'],
                                    ]);
                                }
                            }
                        }
                        fclose($handle);

                        // Delete temporary file after import
                        Storage::disk('public')->delete($data['csv_file']);

                        Notification::make()
                            ->title('Products and Variants imported successfully!')
                            ->success()
                            ->send();
                    }
                })
        ];
    }
}
