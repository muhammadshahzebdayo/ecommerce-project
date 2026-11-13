<?php

namespace App\Filament\Resources\ProductVariants\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;

class ProductVariantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->placeholder('Auto-generated on save')
                    ->disabled(fn ($context) => $context === 'create')
                    ->dehydrated(true)
                    ->required(fn ($context) => $context === 'edit'),
                TextInput::make('price')
                    ->label('Variant Price (Optional - falls back to base price)')
                    ->numeric()
                    ->default(null)
                    ->prefix('Rs.'),
                TextInput::make('stock')
                    ->label('In-Stock (Current)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Placeholder::make('sold_units')
                    ->label('Units Sold')
                    ->content(fn ($record) => $record ? \App\Models\OrderItem::where('product_variant_id', $record->id)->whereHas('order', fn ($q) => $q->where('status', 'delivered'))->sum('quantity') . ' Units' : '0 Units'),
                Placeholder::make('sourced_units')
                    ->label('Total Sourced (Purchased)')
                    ->content(fn ($record) => $record ? ($record->stock + \App\Models\OrderItem::where('product_variant_id', $record->id)->whereHas('order', fn ($q) => $q->where('status', 'delivered'))->sum('quantity')) . ' Units' : '0 Units'),

                Repeater::make('optionValues')
                    ->relationship('optionValues')
                    ->schema([
                        Select::make('product_attribute_id')
                            ->label('Attribute')
                            ->options(function ($get) {
                                $productId = $get('../../product_id');
                                if (!$productId) {
                                    return [];
                                }
                                return \App\Models\ProductAttribute::where('product_id', $productId)->pluck('name', 'id');
                            })
                            ->required(),
                        TextInput::make('value')
                            ->label('Value (e.g. Black, 42, 36B)')
                            ->required(),
                    ])
                    ->label('Variant Option Values')
                    ->columnSpanFull()
                    ->defaultItems(0)
                    ->createItemButtonLabel('Add Option Value'),
            ]);
    }
}
