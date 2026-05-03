<?php

namespace App\Filament\Resources\Raports;

use App\Filament\Resources\Raports\Pages\CreateRaport;
use App\Filament\Resources\Raports\Pages\EditRaport;
use App\Filament\Resources\Raports\Pages\ListRaports;
use App\Filament\Resources\Raports\Pages\ViewRaport;
use App\Filament\Resources\Raports\Schemas\RaportForm;
use App\Filament\Resources\Raports\Schemas\RaportInfolist;
use App\Filament\Resources\Raports\Tables\RaportsTable;
use App\Models\Raport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RaportResource extends Resource
{
    protected static ?string $model = Raport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentChartBar;

    protected static ?string $navigationLabel = 'Raport Pembinaan';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'warga_binaan_id';

    public static function form(Schema $schema): Schema
    {
        return RaportForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RaportInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RaportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRaports::route('/'),
            'create' => CreateRaport::route('/create'),
            'view'   => ViewRaport::route('/{record}'),
            'edit'   => EditRaport::route('/{record}/edit'),
        ];
    }
}