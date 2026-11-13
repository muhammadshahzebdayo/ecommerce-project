<?php

namespace App\Filament\Resources\DeliveryShippings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DeliveryShippingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->label('Linked Online Order')
                    ->relationship('order', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Order #{$record->id} - {$record->customer_name} (Rs. " . number_format($record->total_amount) . ")")
                    ->searchable()
                    ->preload(),
                Select::make('delivery_partner_id')
                    ->label('Delivery Courier Partner')
                    ->relationship('partner', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('tracking_number')
                    ->required()
                    ->placeholder('Courier Tracking Number'),
                TextInput::make('cod_amount')
                    ->label('COD Collection Amount')
                    ->required()
                    ->numeric()
                    ->prefix('Rs. ')
                    ->default(0.00),
                Select::make('payment_method')
                    ->options([
                        'COD' => 'Cash on Delivery (COD)',
                        'Online' => 'Online Pre-Payment',
                    ])
                    ->required()
                    ->live(),
                Select::make('payment_channel')
                    ->options([
                        'JazzCash' => 'JazzCash Mobile Wallet',
                        'EasyPaisa' => 'EasyPaisa Mobile Wallet',
                        'Bank Transfer' => 'Direct Bank Transfer',
                        'Cash' => 'Direct Cash Payment',
                        'Others' => 'Other Mobile Wallet / Application',
                    ])
                    ->visible(fn ($get) => $get('payment_method') === 'Online')
                    ->required(fn ($get) => $get('payment_method') === 'Online'),
                Select::make('payment_status')
                    ->options([
                        'Pending' => 'Pending (Not Received)',
                        'Received' => 'Received / Settled',
                    ])
                    ->default('Pending')
                    ->required()
                    ->live(),
                DatePicker::make('received_at')
                    ->label('Settled Date')
                    ->visible(fn ($get) => $get('payment_status') === 'Received')
                    ->required(fn ($get) => $get('payment_status') === 'Received')
                    ->default(now()),
                Textarea::make('notes')
                    ->columnSpanFull()
                    ->placeholder('Add tracking history or courier settlement remarks...'),
            ]);
    }
}
