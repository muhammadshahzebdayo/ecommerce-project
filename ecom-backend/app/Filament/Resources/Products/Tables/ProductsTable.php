<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('base_price')
                    ->prefix('Rs. ')
                    ->sortable(),
                TextColumn::make('variants_stock')
                    ->label('In-Stock')
                    ->state(function ($record) {
                        return $record->variants()->sum('stock');
                    })
                    ->numeric()
                    ->sortable(),
                TextColumn::make('variants_sold')
                    ->label('Sold')
                    ->state(function ($record) {
                        return \App\Models\OrderItem::whereIn('product_variant_id', $record->variants()->pluck('id'))
                            ->whereHas('order', fn ($q) => $q->where('status', 'delivered'))
                            ->sum('quantity');
                    })
                    ->numeric()
                    ->sortable(),
                TextColumn::make('variants_sourced')
                    ->label('Total Sourced')
                    ->state(function ($record) {
                        $stock = $record->variants()->sum('stock');
                        $sold = \App\Models\OrderItem::whereIn('product_variant_id', $record->variants()->pluck('id'))
                            ->whereHas('order', fn ($q) => $q->where('status', 'delivered'))
                            ->sum('quantity');
                        return $stock + $sold;
                    })
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
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
