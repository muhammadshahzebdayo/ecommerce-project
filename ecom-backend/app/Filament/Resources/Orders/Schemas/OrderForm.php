<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_name')
                    ->label('Customer Name')
                    ->disabled()
                    ->required(),
                TextInput::make('customer_email')
                    ->label('Customer Email')
                    ->email()
                    ->disabled()
                    ->required(),
                TextInput::make('customer_phone')
                    ->label('Customer Phone')
                    ->disabled()
                    ->required(),
                Textarea::make('shipping_address')
                    ->label('Shipping Address')
                    ->disabled()
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->prefix('Rs.')
                    ->disabled()
                    ->required(),
                Select::make('status')
                    ->label('Order Status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),

                Repeater::make('orderItems')
                    ->relationship('orderItems')
                    ->schema([
                        TextInput::make('product_name')
                            ->label('Product Name')
                            ->disabled(),
                        TextInput::make('sku')
                            ->label('SKU')
                            ->disabled(),
                        TextInput::make('price')
                            ->label('Price')
                            ->prefix('Rs.')
                            ->disabled(),
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->disabled(),
                    ])
                    ->label('Order Line Items')
                    ->columnSpanFull()
                    ->deletable(false)
                    ->addable(false)
                    ->reorderable(false),
            ]);
    }
}

