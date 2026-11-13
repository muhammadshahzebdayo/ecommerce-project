<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->placeholder('e.g. Office Rent, Marketing Campaign'),
                Select::make('category')
                    ->options([
                        'Rent' => 'Rent',
                        'Shipping' => 'Shipping / Delivery',
                        'Marketing' => 'Marketing & Ads',
                        'Utilities' => 'Utilities & Internet',
                        'Inventory' => 'Inventory Purchase',
                        'Staff' => 'Staff Salaries',
                        'Others' => 'Others / Miscellaneous',
                    ])
                    ->required()
                    ->searchable(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('Rs. ')
                    ->minValue(0.01),
                DatePicker::make('incurred_at')
                    ->required()
                    ->default(now()),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull()
                    ->placeholder('Add additional remarks or purchase details...'),
            ]);
    }
}
