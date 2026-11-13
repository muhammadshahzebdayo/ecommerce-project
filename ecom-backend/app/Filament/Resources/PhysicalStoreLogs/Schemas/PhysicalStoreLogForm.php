<?php

namespace App\Filament\Resources\PhysicalStoreLogs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PhysicalStoreLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('physical_store_id')
                    ->relationship('store', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('type')
                    ->options([
                        'Sale' => 'Sale (Revenue)',
                        'Expense' => 'Expense (Outflow)',
                    ])
                    ->required()
                    ->live(),
                Select::make('category')
                    ->options([
                        'Sales' => 'Retail Store Sales',
                        'Utilities' => 'Utilities (Electricity, Net, Water)',
                        'Rent' => 'Monthly Location Rent',
                        'Local Purchase' => 'Local / Direct Purchases',
                        'Salaries' => 'Staff Salaries',
                        'Others' => 'Others / Miscellaneous Overhead',
                    ])
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('Rs. ')
                    ->minValue(0.01),
                DatePicker::make('date')
                    ->required()
                    ->default(now()),
                Textarea::make('notes')
                    ->columnSpanFull()
                    ->placeholder('Add transaction descriptions or local store details...'),
            ]);
    }
}
