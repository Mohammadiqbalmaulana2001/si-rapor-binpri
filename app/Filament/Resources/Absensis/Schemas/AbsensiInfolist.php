<?php

namespace App\Filament\Resources\Absensis\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AbsensiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Data Absensi')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->schema([

                        TextEntry::make('wargaBinaan.nama_lengkap')
                            ->label('Warga Binaan')
                            ->weight(\Filament\Support\Enums\FontWeight::Bold)
                            ->size(\Filament\Support\Enums\TextSize::Large),

                        TextEntry::make('wargaBinaan.no_register')
                            ->label('No. Register')
                            ->badge()
                            ->color('primary')
                            ->copyable(),

                        TextEntry::make('kegiatan.nama_kegiatan')
                            ->label('Kegiatan'),

                        TextEntry::make('kegiatan.kategori')
                            ->label('Kategori')
                            ->badge()
                            ->color(fn ($state) => match($state) {
                                'Islam'   => 'warning',
                                'Kristen',
                                'Katolik' => 'info',
                                'Hindu'   => 'success',
                                'Umum'    => 'gray',
                                default   => 'gray',
                            }),

                        TextEntry::make('tanggal')
                            ->label('Tanggal')
                            ->date('d F Y'),

                        TextEntry::make('kehadiran')
                            ->label('Kehadiran')
                            ->badge()
                            ->color(fn ($state) => match($state) {
                                'hadir'       => 'success',
                                'tidak_hadir' => 'danger',
                                'izin'        => 'warning',
                                'sakit'       => 'info',
                                default       => 'gray',
                            })
                            ->formatStateUsing(fn ($state) => match($state) {
                                'hadir'       => '✅ Hadir',
                                'tidak_hadir' => '❌ Tidak Hadir',
                                'izin'        => '📋 Izin',
                                'sakit'       => '🏥 Sakit',
                                default       => $state,
                            }),

                    ])
                    ->columns(2),

                Section::make('Penilaian Keaktifan')
                    ->icon('heroicon-o-star')
                    ->schema([

                        TextEntry::make('keaktifan')
                            ->label('Tingkat Keaktifan')
                            ->badge()
                            ->placeholder('Belum dinilai')
                            ->color(fn ($state) => match($state) {
                                'aktif'                    => 'success',
                                'pasif'                    => 'warning',
                                'perlu_pembinaan_lanjutan' => 'danger',
                                default                    => 'gray',
                            })
                            ->formatStateUsing(fn ($state) => match($state) {
                                'aktif'                    => '✅ Aktif',
                                'pasif'                    => '⚠️ Pasif',
                                'perlu_pembinaan_lanjutan' => '🔴 Perlu Pembinaan Lanjutan',
                                default                    => '— Belum Dinilai',
                            }),

                        TextEntry::make('catatan')
                            ->label('Catatan Petugas')
                            ->placeholder('—')
                            ->columnSpanFull(),

                    ])
                    ->columns(2),

                Section::make('Dicatat Oleh')
                    ->icon('heroicon-o-user')
                    ->collapsed()
                    ->schema([
                        TextEntry::make('pencatat.name')
                            ->label('Petugas')
                            ->placeholder('—'),
                        TextEntry::make('created_at')
                            ->label('Waktu Input')
                            ->dateTime('d/m/Y H:i'),
                        TextEntry::make('updated_at')
                            ->label('Terakhir Diupdate')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(2),

            ]);
    }
}