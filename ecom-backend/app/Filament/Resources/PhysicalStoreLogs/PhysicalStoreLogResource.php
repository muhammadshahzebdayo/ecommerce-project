<?php

namespace App\Filament\Resources\PhysicalStoreLogs;

use App\Filament\Resources\PhysicalStoreLogs\Pages\CreatePhysicalStoreLog;
use App\Filament\Resources\PhysicalStoreLogs\Pages\EditPhysicalStoreLog;
use App\Filament\Resources\PhysicalStoreLogs\Pages\ListPhysicalStoreLogs;
use App\Filament\Resources\PhysicalStoreLogs\Schemas\PhysicalStoreLogForm;
use App\Filament\Resources\PhysicalStoreLogs\Tables\PhysicalStoreLogsTable;
use App\Models\PhysicalStoreLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PhysicalStoreLogResource extends Resource
{
    use \App\Filament\Traits\HasFrozenStateControl;

    protected static ?string $model = PhysicalStoreLog::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Store Sales & Expenses';

    protected static string|\UnitEnum|null $navigationGroup = 'Retail Operations';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'category';

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'finance']);
    }

    public static function form(Schema $schema): Schema
    {
        return PhysicalStoreLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PhysicalStoreLogsTable::configure($table);
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
            'index' => ListPhysicalStoreLogs::route('/'),
            'create' => CreatePhysicalStoreLog::route('/create'),
            'edit' => EditPhysicalStoreLog::route('/{record}/edit'),
        ];
    }
}
