<?php

namespace App\Filament\Resources\PhysicalStores;

use App\Filament\Resources\PhysicalStores\Pages\CreatePhysicalStore;
use App\Filament\Resources\PhysicalStores\Pages\EditPhysicalStore;
use App\Filament\Resources\PhysicalStores\Pages\ListPhysicalStores;
use App\Filament\Resources\PhysicalStores\Schemas\PhysicalStoreForm;
use App\Filament\Resources\PhysicalStores\Tables\PhysicalStoresTable;
use App\Models\PhysicalStore;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PhysicalStoreResource extends Resource
{
    use \App\Filament\Traits\HasFrozenStateControl;

    protected static ?string $model = PhysicalStore::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationLabel = 'Store Locations';

    protected static string|\UnitEnum|null $navigationGroup = 'Retail Operations';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'finance']);
    }

    public static function form(Schema $schema): Schema
    {
        return PhysicalStoreForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PhysicalStoresTable::configure($table);
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
            'index' => ListPhysicalStores::route('/'),
            'create' => CreatePhysicalStore::route('/create'),
            'edit' => EditPhysicalStore::route('/{record}/edit'),
        ];
    }
}
