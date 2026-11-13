<?php

namespace App\Filament\Resources\PhysicalStores\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PhysicalStoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->placeholder('e.g. Lahore Emporium Mall Store'),
                TextInput::make('address')
                    ->required()
                    ->placeholder('Full store address'),
                TextInput::make('monthly_rent')
                    ->required()
                    ->numeric()
                    ->prefix('Rs. ')
                    ->minValue(0),
                Select::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Closed' => 'Closed',
                    ])
                    ->default('Active')
                    ->required(),
            ]);
    }
}
