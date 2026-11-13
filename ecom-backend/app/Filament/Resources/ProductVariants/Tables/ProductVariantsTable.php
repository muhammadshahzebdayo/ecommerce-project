<?php

namespace App\Filament\Resources\ProductVariants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductVariantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->searchable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('price')
                    ->prefix('Rs. ')
                    ->sortable(),
                TextColumn::make('stock')
                    ->label('In-Stock')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sold_units')
                    ->label('Sold')
                    ->state(function ($record) {
                        return \App\Models\OrderItem::where('product_variant_id', $record->id)
                            ->whereHas('order', fn ($q) => $q->where('status', 'delivered'))
                            ->sum('quantity');
                    })
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_sourced')
                    ->label('Total Sourced')
                    ->state(function ($record) {
                        $sold = \App\Models\OrderItem::where('product_variant_id', $record->id)
                            ->whereHas('order', fn ($q) => $q->where('status', 'delivered'))
                            ->sum('quantity');
                        return $record->stock + $sold;
                    })
                    ->numeric()
                    ->sortable(),
                TextColumn::make('attributes_summary')
                    ->label('Attributes')
                    ->state(function ($record) {
                        return $record->optionValues()
                            ->with('attribute')
                            ->get()
                            ->map(fn ($val) => "{$val->attribute->name}: {$val->value}")
                            ->implode(', ');
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
