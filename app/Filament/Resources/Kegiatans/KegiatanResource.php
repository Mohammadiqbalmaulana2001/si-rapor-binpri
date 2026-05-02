<?php

namespace App\Filament\Resources\Kegiatans;

use App\Filament\Resources\Kegiatans\Pages\ListKegiatans;
use App\Filament\Resources\Kegiatans\Schemas\KegiatanForm;
use App\Filament\Resources\Kegiatans\Schemas\KegiatanInfolist;
use App\Filament\Resources\Kegiatans\Tables\KegiatansTable;
use App\Models\Kegiatan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KegiatanResource extends Resource
{
    protected static ?string $model = Kegiatan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDays;

    protected static ?string $navigationLabel = 'Program Kegiatan';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'nama_kegiatan';

    public static function form(Schema $schema): Schema
    {
        return KegiatanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KegiatanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KegiatansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListKegiatans::route('/'),
        ];
    }
}