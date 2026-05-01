<?php

namespace App\Filament\Resources\WargaBinaans;

use App\Filament\Resources\WargaBinaans\Pages\CreateWargaBinaan;
use App\Filament\Resources\WargaBinaans\Pages\EditWargaBinaan;
use App\Filament\Resources\WargaBinaans\Pages\ListWargaBinaans;
use App\Filament\Resources\WargaBinaans\Pages\ViewWargaBinaan;
use App\Filament\Resources\WargaBinaans\Schemas\WargaBinaanForm;
use App\Filament\Resources\WargaBinaans\Schemas\WargaBinaanInfolist;
use App\Filament\Resources\WargaBinaans\Tables\WargaBinaansTable;
use App\Models\WargaBinaan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WargaBinaanResource extends Resource
{
    protected static ?string $model = WargaBinaan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    public static function form(Schema $schema): Schema
    {
        return WargaBinaanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WargaBinaanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WargaBinaansTable::configure($table);
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
            'index' => ListWargaBinaans::route('/'),
            'create' => CreateWargaBinaan::route('/create'),
            'view' => ViewWargaBinaan::route('/{record}'),
            'edit' => EditWargaBinaan::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
