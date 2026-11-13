<?php

namespace App\Filament\Resources\UdharRegistries;

use App\Filament\Resources\UdharRegistries\Pages\CreateUdharRegistry;
use App\Filament\Resources\UdharRegistries\Pages\EditUdharRegistry;
use App\Filament\Resources\UdharRegistries\Pages\ListUdharRegistries;
use App\Filament\Resources\UdharRegistries\Schemas\UdharRegistryForm;
use App\Filament\Resources\UdharRegistries\Tables\UdharRegistriesTable;
use App\Models\UdharRegistry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UdharRegistryResource extends Resource
{
    use \App\Filament\Traits\HasFrozenStateControl;

    protected static ?string $model = UdharRegistry::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Udhar (Credit) Registry';

    protected static string|\UnitEnum|null $navigationGroup = 'Retail Operations';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'customer_name';

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'finance']);
    }

    public static function form(Schema $schema): Schema
    {
        return UdharRegistryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UdharRegistriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUdharRegistries::route('/'),
            'create' => CreateUdharRegistry::route('/create'),
            'edit' => EditUdharRegistry::route('/{record}/edit'),
        ];
    }
}
