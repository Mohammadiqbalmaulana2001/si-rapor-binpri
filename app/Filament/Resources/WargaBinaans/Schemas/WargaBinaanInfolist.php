<?php

namespace App\Filament\Resources\WargaBinaans\Schemas;

use App\Models\WargaBinaan;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class WargaBinaanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('nama_lengkap'),
                TextEntry::make('no_register'),
                TextEntry::make('nomor_induk')
                    ->placeholder('-'),
                TextEntry::make('agama')
                    ->badge(),
                TextEntry::make('blok_kamar')
                    ->placeholder('-'),
                TextEntry::make('tanggal_masuk')
                    ->date(),
                TextEntry::make('tanggal_bebas')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('jenis_kejahatan')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('foto')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (WargaBinaan $record): bool => $record->trashed()),
            ]);
    }
}
