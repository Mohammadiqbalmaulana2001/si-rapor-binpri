<?php

namespace App\Filament\Resources\Absensis\Schemas;

use App\Models\Kegiatan;
use App\Models\WargaBinaan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AbsensiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Data Absensi')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->schema([

                        // ── WARGA BINAAN ──
                        Select::make('warga_binaan_id')
                            ->label('Warga Binaan')
                            ->options(
                                WargaBinaan::where('status', 'aktif')
                                    ->orderBy('nama_lengkap')
                                    ->get()
                                    ->mapWithKeys(fn ($wb) => [
                                        $wb->id => "[{$wb->no_register}] {$wb->nama_lengkap}"
                                    ])
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->columnSpanFull(),

                        // ── KEGIATAN (filter sesuai agama WBP) ──
                        Select::make('kegiatan_id')
                            ->label('Kegiatan')
                            ->options(function ($get) {
                                $wbId = $get('warga_binaan_id');

                                if (!$wbId) {
                                    return Kegiatan::where('is_active', true)
                                        ->orderBy('nama_kegiatan')
                                        ->pluck('nama_kegiatan', 'id');
                                }

                                $wb = WargaBinaan::find($wbId);
                                if (!$wb) return [];

                                return Kegiatan::where('is_active', true)
                                    ->whereIn('kategori', [$wb->agama, 'Umum'])
                                    ->orderBy('nama_kegiatan')
                                    ->get()
                                    ->mapWithKeys(fn ($k) => [
                                        $k->id => "{$k->nama_kegiatan} ({$k->kategori})"
                                    ]);
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        // ── TANGGAL ──
                        DatePicker::make('tanggal')
                            ->label('Tanggal Kegiatan')
                            ->required()
                            ->default(now())
                            ->displayFormat('d/m/Y')
                            ->maxDate(now()),

                        // ── KEHADIRAN ──
                        Select::make('kehadiran')
                            ->label('Kehadiran')
                            ->options([
                                'hadir'        => '✅ Hadir',
                                'tidak_hadir'  => '❌ Tidak Hadir',
                                'izin'         => '📋 Izin',
                                'sakit'        => '🏥 Sakit',
                            ])
                            ->default('hadir')
                            ->required()
                            ->live(),

                    ])
                    ->columns(2),

                // ── SECTION KEAKTIFAN (hanya muncul kalau hadir) ──
                Section::make('Penilaian Keaktifan')
                    ->icon('heroicon-o-star')
                    ->description('Dinilai langsung oleh ustad / pendeta / petugas yang bertugas')
                    ->visible(fn ($get) => $get('kehadiran') === 'hadir')
                    ->schema([

                        Select::make('keaktifan')
                            ->label('Tingkat Keaktifan')
                            ->options([
                                'aktif'                    => '✅ Aktif — Hadir & berpartisipasi penuh',
                                'pasif'                    => '⚠️ Pasif — Hadir tapi tidak aktif',
                                'perlu_pembinaan_lanjutan' => '🔴 Perlu Pembinaan Lanjutan',
                            ])
                            ->placeholder('Pilih tingkat keaktifan...')
                            ->columnSpanFull(),

                        Textarea::make('catatan')
                            ->label('Catatan Petugas')
                            ->placeholder('Catatan tambahan dari ustad / pendeta / petugas...')
                            ->rows(3)
                            ->columnSpanFull(),

                    ])
                    ->columns(1),

            ]);
    }
}