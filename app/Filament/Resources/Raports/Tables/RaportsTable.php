<?php

namespace App\Filament\Resources\Raports\Tables;

use App\Models\WargaBinaan;
use App\Services\RaportService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class RaportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // ── WBP ──
                TextColumn::make('wargaBinaan.no_register')
                    ->label('No. Register')
                    ->searchable()
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold)
                    ->copyable(),

                TextColumn::make('wargaBinaan.nama_lengkap')
                    ->label('Nama WBP')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) =>
                        $record->wargaBinaan?->blok_kamar
                            ? 'Blok: ' . $record->wargaBinaan->blok_kamar
                            : null
                    ),

                // ── PERIODE ──
                TextColumn::make('bulan')
                    ->label('Periode')
                    ->state(fn ($record) => $record->nama_bulan)
                    ->sortable(),

                // ── KEHADIRAN ──
                TextColumn::make('total_hadir')
                    ->label('Hadir')
                    ->state(fn ($record) =>
                        $record->total_hadir . ' / ' . $record->total_kegiatan
                    )
                    ->alignCenter(),

                // ── PERSENTASE ──
                TextColumn::make('persentase_kehadiran')
                    ->label('Kehadiran %')
                    ->state(fn ($record) => $record->persentase_kehadiran . '%')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(fn ($record) => match(true) {
                        $record->persentase_kehadiran >= 75 => 'success',
                        $record->persentase_kehadiran >= 60 => 'warning',
                        default                             => 'danger',
                    }),

                // ── KEAKTIFAN ──
                TextColumn::make('total_aktif')
                    ->label('A')
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->tooltip('Aktif'),

                TextColumn::make('total_pasif')
                    ->label('P')
                    ->alignCenter()
                    ->badge()
                    ->color('warning')
                    ->tooltip('Pasif'),

                TextColumn::make('total_perlu_pembinaan')
                    ->label('PL')
                    ->alignCenter()
                    ->badge()
                    ->color('danger')
                    ->tooltip('Perlu Pembinaan Lanjutan'),

                // ── REKOMENDASI ──
                TextColumn::make('rekomendasi')
                    ->label('Rekomendasi')
                    ->badge()
                    ->placeholder('—')
                    ->color(fn ($state) => match($state) {
                        'sangat_baik' => 'success',
                        'baik'        => 'info',
                        'cukup'       => 'warning',
                        'kurang'      => 'danger',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'sangat_baik' => '⭐⭐⭐ Sangat Baik',
                        'baik'        => '⭐⭐ Baik',
                        'cukup'       => '⭐ Cukup',
                        'kurang'      => '❌ Kurang',
                        default       => '—',
                    }),

                // ── STATUS FINALISASI ──
                IconColumn::make('is_finalized')
                    ->label('Final')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->tooltip(fn ($state) => $state ? 'Sudah Difinalisasi' : 'Belum Difinalisasi'),

            ])

            ->filters([

                SelectFilter::make('rekomendasi')
                    ->label('Rekomendasi')
                    ->options([
                        'sangat_baik' => '⭐⭐⭐ Sangat Baik',
                        'baik'        => '⭐⭐ Baik',
                        'cukup'       => '⭐ Cukup',
                        'kurang'      => '❌ Kurang',
                    ]),

                SelectFilter::make('bulan')
                    ->label('Bulan')
                    ->options([
                        1  => 'Januari',  2  => 'Februari', 3  => 'Maret',
                        4  => 'April',    5  => 'Mei',      6  => 'Juni',
                        7  => 'Juli',     8  => 'Agustus',  9  => 'September',
                        10 => 'Oktober',  11 => 'November', 12 => 'Desember',
                    ]),

                TernaryFilter::make('is_finalized')
                    ->label('Status')
                    ->trueLabel('Sudah Difinalisasi')
                    ->falseLabel('Belum Difinalisasi')
                    ->placeholder('Semua'),

            ])

            ->recordActions([

                ViewAction::make()
                    ->modalHeading(fn ($record) =>
                        'Raport: ' . $record->wargaBinaan?->nama_lengkap .
                        ' — ' . $record->nama_bulan
                    )
                    ->modal(),

                EditAction::make()
                    ->modalHeading('Edit Raport')
                    ->modal()
                    ->hidden(fn ($record) => $record->is_finalized)
                    ->successNotificationTitle('Raport berhasil diperbarui'),

                // ── TOMBOL FINALISASI ──
                Action::make('finalisasi')
                    ->label('Finalisasi')
                    ->icon('heroicon-o-lock-closed')
                    ->color('success')
                    ->hidden(fn ($record) => $record->is_finalized)
                    ->requiresConfirmation()
                    ->modalHeading('Finalisasi Raport')
                    ->modalDescription(fn ($record) =>
                        "Raport {$record->wargaBinaan?->nama_lengkap} bulan {$record->nama_bulan} " .
                        "akan dikunci. Data tidak dapat diubah setelah difinalisasi."
                    )
                    ->modalSubmitActionLabel('Ya, Finalisasi')
                    ->action(function ($record) {
                        $record->update(['is_finalized' => true]);
                        Notification::make()
                            ->title('Raport berhasil difinalisasi')
                            ->success()
                            ->send();
                    }),

                DeleteAction::make()
                    ->hidden(fn ($record) => $record->is_finalized)
                    ->modalHeading('Hapus Raport')
                    ->modalDescription(fn ($record) =>
                        "Hapus raport {$record->wargaBinaan?->nama_lengkap} bulan {$record->nama_bulan}?"
                    )
                    ->modalSubmitActionLabel('Ya, Hapus'),

            ])

            ->toolbarActions([

                // ── GENERATE RAPORT BULANAN ──
                Action::make('generate_bulanan')
                    ->label('Generate Raport Bulanan')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->form([
                        Select::make('bulan')
                            ->label('Bulan')
                            ->options([
                                1  => 'Januari',  2  => 'Februari', 3  => 'Maret',
                                4  => 'April',    5  => 'Mei',      6  => 'Juni',
                                7  => 'Juli',     8  => 'Agustus',  9  => 'September',
                                10 => 'Oktober',  11 => 'November', 12 => 'Desember',
                            ])
                            ->default(now()->month)
                            ->required(),
                        TextInput::make('tahun')
                            ->label('Tahun')
                            ->numeric()
                            ->default(now()->year)
                            ->required(),
                    ])
                    ->modalHeading('Generate Raport Bulanan')
                    ->modalDescription(
                        'Sistem akan menghitung rekap absensi semua WBP aktif ' .
                        'pada bulan & tahun yang dipilih. ' .
                        'Raport yang sudah difinalisasi tidak akan ditimpa.'
                    )
                    ->modalSubmitActionLabel('Generate Sekarang')
                    ->action(function (array $data) {
                        $count = app(RaportService::class)
                            ->generateBulanan((int)$data['bulan'], (int)$data['tahun']);

                        Notification::make()
                            ->title("Generate selesai: {$count} raport berhasil dibuat/diperbarui")
                            ->success()
                            ->send();
                    }),

                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->modalHeading('Hapus Raport Terpilih')
                        ->modalDescription('Raport yang dihapus tidak dapat dikembalikan.')
                        ->modalSubmitActionLabel('Ya, Hapus'),
                ]),

            ])

            ->defaultSort('tahun', 'desc')
            ->striped()
            ->emptyStateIcon('heroicon-o-document-chart-bar')
            ->emptyStateHeading('Belum ada raport')
            ->emptyStateDescription('Klik "Generate Raport Bulanan" untuk membuat raport dari data absensi.')
            ->emptyStateActions([
                Action::make('generate_empty')
                    ->label('Generate Raport Sekarang')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->form([
                        Select::make('bulan')
                            ->label('Bulan')
                            ->options([
                                1  => 'Januari',  2  => 'Februari', 3  => 'Maret',
                                4  => 'April',    5  => 'Mei',      6  => 'Juni',
                                7  => 'Juli',     8  => 'Agustus',  9  => 'September',
                                10 => 'Oktober',  11 => 'November', 12 => 'Desember',
                            ])
                            ->default(now()->month)
                            ->required(),
                        TextInput::make('tahun')
                            ->label('Tahun')
                            ->numeric()
                            ->default(now()->year)
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $count = app(RaportService::class)
                            ->generateBulanan((int)$data['bulan'], (int)$data['tahun']);

                        Notification::make()
                            ->title("Generate selesai: {$count} raport berhasil dibuat")
                            ->success()
                            ->send();
                    }),
            ]);
    }
}