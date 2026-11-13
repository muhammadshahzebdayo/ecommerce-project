<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                    ->placeholder('Leave blank to keep current password')
                    ->maxLength(255),
                Select::make('role')
                    ->options([
                        'admin' => 'Super Admin',
                        'finance' => 'Finance User',
                    ])
                    ->default('finance')
                    ->required(),
                Toggle::make('is_frozen')
                    ->label('Freeze Account Actions')
                    ->helperText('If checked, a Finance user can log in but cannot create, update, or delete any records.')
                    ->default(false),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
            ]);
    }
}
