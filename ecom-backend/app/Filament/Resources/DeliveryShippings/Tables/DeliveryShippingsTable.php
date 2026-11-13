<?php

namespace App\Filament\Resources\DeliveryShippings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DeliveryShippingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('partner.name')
                    ->label('Courier')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tracking_number')
                    ->searchable(),
                TextColumn::make('order.id')
                    ->label('Order #')
                    ->placeholder('Manual Shipment')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cod_amount')
                    ->label('COD Amount')
                    ->prefix('Rs. ')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'COD' => 'warning',
                        'Online' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('payment_channel')
                    ->label('Channel')
                    ->placeholder('N/A (COD)')
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'danger',
                        'Received' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('received_at')
                    ->date()
                    ->sortable()
                    ->placeholder('Awaiting payment'),
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
