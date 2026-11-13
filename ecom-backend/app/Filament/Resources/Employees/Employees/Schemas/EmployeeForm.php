<?php

namespace App\Filament\Resources\Employees\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->placeholder('Employee full name'),
                TextInput::make('phone')
                    ->tel()
                    ->placeholder('e.g. 03211234567'),
                TextInput::make('email')
                    ->email()
                    ->placeholder('e.g. employee@brand.com'),
                Select::make('employee_type')
                    ->options([
                        'Admin Office' => 'Admin Office / Headquarters',
                        'Physical Store' => 'Physical Retail Store',
                    ])
                    ->required()
                    ->live(),
                Select::make('physical_store_id')
                    ->label('Assigned Physical Store')
                    ->relationship('store', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn ($get) => $get('employee_type') === 'Physical Store')
                    ->required(fn ($get) => $get('employee_type') === 'Physical Store'),
                TextInput::make('role')
                    ->required()
                    ->placeholder('e.g. Store Manager, Accountant, Sales Associate'),
                TextInput::make('salary')
                    ->required()
                    ->numeric()
                    ->prefix('Rs. ')
                    ->minValue(0),
                Select::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ])
                    ->default('Active')
                    ->required(),
                DatePicker::make('hired_at')
                    ->default(now()),
            ]);
    }
}
