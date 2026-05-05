<?php

namespace App\Filament\Resources\WargaBinaans\Schemas;

use App\Models\WargaBinaan;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\TextSize;

class WargaBinaanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Identitas Warga Binaan')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        ImageEntry::make('foto')
                            ->label('')
                            ->circular()
                            ->disk('public')
                            ->defaultImageUrl(fn ($record) =>
                                'https://ui-avatars.com/api/?name=' .
                                urlencode($record->nama_lengkap) .
                                '&background=1e3a5f&color=fff&size=128'
                            )
                            ->columnSpanFull(),

                        TextEntry::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->weight(\Filament\Support\Enums\FontWeight::Bold)
                            ->size(TextSize::Large),

                        TextEntry::make('no_register')
                            ->label('Nomor Register')
                            ->copyable()
                            ->copyMessage('Nomor register disalin!')
                            ->badge()
                            ->color('primary'),

                        TextEntry::make('nomor_induk')
                            ->label('NIK')
                            ->placeholder('-'),

                        TextEntry::make('agama')
                            ->label('Agama')
                            ->badge()
                            ->color(fn ($state) => match($state) {
                                'Islam'    => 'warning',
                                'Kristen',
                                'Katolik'  => 'info',
                                'Hindu'    => 'success',
                                'Buddha'   => 'purple',
                                default    => 'gray',
                            }),
                    ])
                    ->columns(2),

                Section::make('Data Penempatan & Masa Pidana')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        TextEntry::make('blok_kamar')
                            ->label('Blok / Kamar')
                            ->placeholder('-')
                            ->badge()
                            ->color('gray'),

                        TextEntry::make('jenis_kejahatan')
                            ->label('Jenis Kejahatan')
                            ->placeholder('-'),

                        TextEntry::make('tanggal_masuk')
                            ->label('Tanggal Masuk')
                            ->date('d F Y'),

                        TextEntry::make('tanggal_bebas')
                            ->label('Tanggal Bebas')
                            ->date('d F Y')
                            ->placeholder('-')
                            ->color(fn ($state) =>
                                $state && \Carbon\Carbon::parse($state)->isPast()
                                    ? 'danger' : 'success'
                            ),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn ($state) => match($state) {
                                'aktif'  => 'success',
                                'bebas'  => 'gray',
                                'pindah' => 'warning',
                                default  => 'gray',
                            }),

                        TextEntry::make('tanggal_masuk')
                            ->label('Lama Ditahan')
                            ->state(fn ($record) =>
                                $record->tanggal_masuk->diffForHumans(now(), [
                                    'parts'  => 2,
                                    'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE,
                                ])
                            ),
                    ])
                    ->columns(2),

                Section::make('Informasi Sistem')
                    ->icon('heroicon-o-clock')
                    ->collapsed()
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diupdate')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('-'),

                        TextEntry::make('deleted_at')
                            ->label('Dihapus Pada')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('-')
                            ->visible(fn (WargaBinaan $record): bool => $record->trashed()),
                    ])
                    ->columns(2),

            ]);
    }
}