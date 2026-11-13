<?php

namespace App\Filament\Resources\UdharRegistries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UdharRegistryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_name')
                    ->required()
                    ->placeholder('Customer Name'),
                TextInput::make('phone')
                    ->tel()
                    ->placeholder('e.g. 03009876543'),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('Rs. ')
                    ->minValue(0.01),
                DatePicker::make('due_date')
                    ->placeholder('Select payment due date'),
                Select::make('status')
                    ->options([
                        'Pending' => 'Pending Credit',
                        'Settled' => 'Settled (Paid)',
                    ])
                    ->default('Pending')
                    ->required()
                    ->live(),
                DatePicker::make('settled_at')
                    ->label('Settled On')
                    ->visible(fn ($get) => $get('status') === 'Settled')
                    ->required(fn ($get) => $get('status') === 'Settled')
                    ->default(now()),
                Textarea::make('description')
                    ->columnSpanFull()
                    ->placeholder('List details of items purchased or payment promises...'),
            ]);
    }
}
