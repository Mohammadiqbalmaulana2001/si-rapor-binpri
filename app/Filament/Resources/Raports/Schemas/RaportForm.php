<?php

namespace App\Filament\Resources\Raports\Schemas;

use App\Models\WargaBinaan;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RaportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ══════════════════════════════════
                // PERIODE & WBP — tidak bisa diubah saat edit
                // ══════════════════════════════════
                Section::make('Periode & Warga Binaan')
                    ->icon('heroicon-o-document-chart-bar')
                    ->schema([

                        Select::make('warga_binaan_id')
                            ->label('Warga Binaan')
                            ->options(
                                WargaBinaan::orderBy('nama_lengkap')
                                    ->get()
                                    ->mapWithKeys(fn ($wb) => [
                                        $wb->id => "[{$wb->no_register}] {$wb->nama_lengkap}"
                                    ])
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabledOn('edit')
                            ->columnSpanFull(),

                        Select::make('bulan')
                            ->label('Bulan')
                            ->options([
                                1  => 'Januari',  2  => 'Februari',
                                3  => 'Maret',    4  => 'April',
                                5  => 'Mei',      6  => 'Juni',
                                7  => 'Juli',     8  => 'Agustus',
                                9  => 'September',10 => 'Oktober',
                                11 => 'November', 12 => 'Desember',
                            ])
                            ->default(now()->month)
                            ->required()
                            ->disabledOn('edit'),

                        Select::make('tahun')
                            ->label('Tahun')
                            ->options(
                                collect(range(now()->year, 2020, -1))
                                    ->mapWithKeys(fn ($y) => [$y => $y])
                                    ->toArray()
                            )
                            ->default(now()->year)
                            ->required()
                            ->disabledOn('edit'),

                    ])
                    ->columns(2),

                // ══════════════════════════════════
                // REKAP ANGKA — READ ONLY
                // ══════════════════════════════════
                Section::make('Rekap Kehadiran')
                    ->icon('heroicon-o-chart-bar')
                    ->description('Dihitung otomatis dari data absensi — tidak dapat diedit')
                    ->visibleOn('edit')
                    ->schema([

                        Placeholder::make('total_kegiatan')
                            ->label('Total Sesi Kegiatan')
                            ->content(fn ($record) => $record?->total_kegiatan . ' sesi'),

                        Placeholder::make('total_hadir')
                            ->label('Total Hadir')
                            ->content(fn ($record) =>
                                $record?->total_hadir . ' dari ' . $record?->total_kegiatan . ' sesi'
                            ),

                        Placeholder::make('persentase_kehadiran')
                            ->label('Persentase Kehadiran')
                            ->content(fn ($record) => $record?->persentase_kehadiran . '%'),

                        Placeholder::make('total_aktif')
                            ->label('✅ Aktif')
                            ->content(fn ($record) => $record?->total_aktif . ' sesi'),

                        Placeholder::make('total_pasif')
                            ->label('⚠️ Pasif')
                            ->content(fn ($record) => $record?->total_pasif . ' sesi'),

                        Placeholder::make('total_perlu_pembinaan')
                            ->label('🔴 Perlu Pembinaan')
                            ->content(fn ($record) => $record?->total_perlu_pembinaan . ' sesi'),

                    ])
                    ->columns(3),

                // ══════════════════════════════════
                // DETAIL PER KEGIATAN — READ ONLY DI EDIT
                // ══════════════════════════════════
                Section::make('Detail Per Kegiatan')
                    ->icon('heroicon-o-table-cells')
                    ->description('Rincian per program kegiatan pada bulan ini')
                    ->visibleOn('edit')
                    ->schema([

                        Placeholder::make('detail_kegiatan_form')
                            ->label('')
                            ->content(fn ($record) =>
                                $record
                                    ? new \Illuminate\Support\HtmlString(
                                        view('filament.raport.detail-kegiatan-simple', [
                                            'detail' => RaportInfolist::getDetailKegiatan($record),
                                        ])->render()
                                    )
                                    : '—'
                            )
                            ->columnSpanFull(),

                    ])
                    ->columns(1),

                // ══════════════════════════════════
                // PENILAIAN PETUGAS — BISA DIEDIT
                // ══════════════════════════════════
                Section::make('Penilaian Petugas')
                    ->icon('heroicon-o-star')
                    ->description('Bagian ini yang boleh diisi / diubah oleh petugas')
                    ->schema([

                        Select::make('rekomendasi')
                            ->label('Rekomendasi')
                            ->options([
                                'sangat_baik' => '⭐⭐⭐ Sangat Baik — Layak Remisi / PB',
                                'baik'        => '⭐⭐ Baik',
                                'cukup'       => '⭐ Cukup',
                                'kurang'      => '❌ Kurang — Perlu Pembinaan Intensif',
                            ])
                            ->placeholder('Sistem menghitung otomatis, petugas bisa override...')
                            ->columnSpanFull(),

                        Textarea::make('catatan_petugas')
                            ->label('Catatan Petugas')
                            ->placeholder('Catatan tambahan dari petugas pembinaan...')
                            ->rows(4)
                            ->columnSpanFull(),

                        Toggle::make('is_finalized')
                            ->label('Finalisasi Raport')
                            ->helperText('Setelah difinalisasi, raport tidak dapat diubah lagi')
                            ->onColor('success')
                            ->offColor('gray'),

                    ])
                    ->columns(2),

            ]);
    }
}