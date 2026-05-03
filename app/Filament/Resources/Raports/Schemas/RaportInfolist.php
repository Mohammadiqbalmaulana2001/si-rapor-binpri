<?php

namespace App\Filament\Resources\Raports\Schemas;

use App\Models\Absensi;
use App\Models\Kegiatan;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RaportInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ══════════════════════════════════
                // IDENTITAS WBP
                // ══════════════════════════════════
                Section::make('Identitas Warga Binaan')
                    ->icon('heroicon-o-user')
                    ->schema([

                        TextEntry::make('wargaBinaan.nama_lengkap')
                            ->label('Nama WBP')
                            ->weight(\Filament\Support\Enums\FontWeight::Bold)
                            ->size(\Filament\Support\Enums\TextSize::Large),

                        TextEntry::make('wargaBinaan.no_register')
                            ->label('No. Register')
                            ->badge()
                            ->color('primary')
                            ->copyable(),

                        TextEntry::make('wargaBinaan.agama')
                            ->label('Agama')
                            ->badge()
                            ->color(fn ($state) => match($state) {
                                'Islam'   => 'warning',
                                'Kristen',
                                'Katolik' => 'info',
                                'Hindu'   => 'success',
                                default   => 'gray',
                            }),

                        TextEntry::make('wargaBinaan.blok_kamar')
                            ->label('Blok / Kamar')
                            ->badge()
                            ->color('gray')
                            ->placeholder('—'),

                        TextEntry::make('bulan')
                            ->label('Periode')
                            ->state(fn ($record) => $record->nama_bulan),

                        TextEntry::make('is_finalized')
                            ->label('Status')
                            ->badge()
                            ->state(fn ($record) => $record->is_finalized
                                ? '🔒 Sudah Difinalisasi'
                                : '🔓 Belum Difinalisasi'
                            )
                            ->color(fn ($record) => $record->is_finalized
                                ? 'success' : 'gray'
                            ),

                    ])
                    ->columns(2),

                // ══════════════════════════════════
                // RINGKASAN STATISTIK
                // ══════════════════════════════════
                Section::make('Ringkasan Kehadiran')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([

                        TextEntry::make('persentase_kehadiran')
                            ->label('Persentase Kehadiran')
                            ->state(fn ($record) => $record->persentase_kehadiran . '%')
                            ->size(\Filament\Support\Enums\TextSize::Large)
                            ->weight(\Filament\Support\Enums\FontWeight::Bold)
                            ->badge()
                            ->color(fn ($record) => match(true) {
                                $record->persentase_kehadiran >= 75 => 'success',
                                $record->persentase_kehadiran >= 60 => 'warning',
                                default                             => 'danger',
                            }),

                        TextEntry::make('total_hadir')
                            ->label('Hadir / Total')
                            ->state(fn ($record) =>
                                $record->total_hadir . ' dari ' . $record->total_kegiatan . ' sesi'
                            ),

                        TextEntry::make('total_aktif')
                            ->label('✅ Aktif')
                            ->badge()
                            ->color('success'),

                        TextEntry::make('total_pasif')
                            ->label('⚠️ Pasif')
                            ->badge()
                            ->color('warning'),

                        TextEntry::make('total_perlu_pembinaan')
                            ->label('🔴 Perlu Pembinaan')
                            ->badge()
                            ->color('danger'),

                    ])
                    ->columns(3),

                // ══════════════════════════════════
                // DETAIL PER KEGIATAN — TABEL CUSTOM
                // ══════════════════════════════════
                Section::make('Detail Per Kegiatan')
                    ->icon('heroicon-o-table-cells')
                    ->description('Rincian kehadiran dan keaktifan per program kegiatan')
                    ->schema([

                        ViewEntry::make('detail_kegiatan')
                            ->label('')
                            ->view('filament.raport.detail-kegiatan')
                            ->state(fn ($record) => self::getDetailKegiatan($record))
                            ->columnSpanFull(),

                    ])
                    ->columns(1),

                // ══════════════════════════════════
                // REKOMENDASI
                // ══════════════════════════════════
                Section::make('Rekomendasi & Penilaian Petugas')
                    ->icon('heroicon-o-star')
                    ->schema([

                        TextEntry::make('rekomendasi')
                            ->label('Rekomendasi')
                            ->badge()
                            ->size(\Filament\Support\Enums\TextSize::Large)
                            ->placeholder('—')
                            ->color(fn ($state) => match($state) {
                                'sangat_baik' => 'success',
                                'baik'        => 'info',
                                'cukup'       => 'warning',
                                'kurang'      => 'danger',
                                default       => 'gray',
                            })
                            ->formatStateUsing(fn ($state) => match($state) {
                                'sangat_baik' => '⭐⭐⭐ Sangat Baik — Layak Remisi / PB',
                                'baik'        => '⭐⭐ Baik',
                                'cukup'       => '⭐ Cukup',
                                'kurang'      => '❌ Kurang — Perlu Pembinaan Intensif',
                                default       => '—',
                            })
                            ->columnSpanFull(),

                        TextEntry::make('catatan_petugas')
                            ->label('Catatan Petugas')
                            ->placeholder('—')
                            ->columnSpanFull(),

                    ])
                    ->columns(2),

                Section::make('Informasi Sistem')
                    ->icon('heroicon-o-clock')
                    ->collapsed()
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d/m/Y H:i'),
                        TextEntry::make('updated_at')
                            ->label('Diupdate')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(2),

            ]);
    }

    /**
     * Ambil data detail kehadiran per kegiatan untuk 1 WBP 1 bulan
     */
    public static function getDetailKegiatan($record): array
    {
        if (!$record) return [];

        // Kegiatan yang sesuai agama WBP
        $agama    = $record->wargaBinaan?->agama;
        $kegiatans = Kegiatan::where('is_active', true)
            ->when($agama, fn ($q) => $q->whereIn('kategori', [$agama, 'Umum']))
            ->orderBy('kategori')
            ->orderBy('nama_kegiatan')
            ->get();

        return $kegiatans->map(function (Kegiatan $kg) use ($record) {

            // Ambil semua absensi WBP ini untuk kegiatan ini pada bulan tsb
            $absensis = Absensi::where('warga_binaan_id', $record->warga_binaan_id)
                ->where('kegiatan_id', $kg->id)
                ->whereMonth('tanggal', $record->bulan)
                ->whereYear('tanggal', $record->tahun)
                ->orderBy('tanggal')
                ->get();

            $total       = $absensis->count();
            $hadir       = $absensis->where('kehadiran', 'hadir')->count();
            $tidakHadir  = $absensis->where('kehadiran', 'tidak_hadir')->count();
            $izin        = $absensis->where('kehadiran', 'izin')->count();
            $sakit       = $absensis->where('kehadiran', 'sakit')->count();
            $aktif       = $absensis->where('keaktifan', 'aktif')->count();
            $pasif       = $absensis->where('keaktifan', 'pasif')->count();
            $perlu       = $absensis->where('keaktifan', 'perlu_pembinaan_lanjutan')->count();
            $persen      = $total > 0 ? round($hadir / $total * 100, 1) : 0;

            return [
                'nama_kegiatan'   => $kg->nama_kegiatan,
                'kategori'        => $kg->kategori,
                'frekuensi'       => $kg->frekuensi,
                'penanggung_jawab'=> $kg->penanggung_jawab ?? '—',
                'total'           => $total,
                'hadir'           => $hadir,
                'tidak_hadir'     => $tidakHadir,
                'izin'            => $izin,
                'sakit'           => $sakit,
                'aktif'           => $aktif,
                'pasif'           => $pasif,
                'perlu'           => $perlu,
                'persen'          => $persen,
                // Untuk progress bar warna
                'persen_color'    => match(true) {
                    $persen >= 75 => '#22c55e',
                    $persen >= 60 => '#f59e0b',
                    $total === 0  => '#e5e7eb',
                    default       => '#ef4444',
                },
            ];
        })->toArray();
    }
}