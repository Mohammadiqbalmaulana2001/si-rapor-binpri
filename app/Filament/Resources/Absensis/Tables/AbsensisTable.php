<?php

namespace App\Filament\Resources\Absensis\Tables;

use App\Models\Kegiatan;
use Illuminate\Support\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Table;

class AbsensisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // ── TANGGAL ──
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),

                // ── WARGA BINAAN ──
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

                // ── KEGIATAN ──
                TextColumn::make('kegiatan.nama_kegiatan')
                    ->label('Kegiatan')
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                // ── KEHADIRAN ──
                TextColumn::make('kehadiran')
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

                // ── KEAKTIFAN ──
                TextColumn::make('keaktifan')
                    ->label('Keaktifan')
                    ->badge()
                    ->placeholder('—')
                    ->color(fn ($state) => match($state) {
                        'aktif'                    => 'success',
                        'pasif'                    => 'warning',
                        'perlu_pembinaan_lanjutan' => 'danger',
                        default                    => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'aktif'                    => '✅ Aktif',
                        'pasif'                    => '⚠️ Pasif',
                        'perlu_pembinaan_lanjutan' => '🔴 Perlu Pembinaan',
                        default                    => '— Belum Dinilai',
                    }),

                // ── CATATAN ──
                TextColumn::make('catatan')
                    ->label('Catatan')
                    ->placeholder('—')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),

                // ── DICATAT OLEH ──
                TextColumn::make('pencatat.name')
                    ->label('Dicatat Oleh')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            ->filters([

                // Filter tanggal range
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('dari')
                            ->label('Dari Tanggal')
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('sampai')
                            ->label('Sampai Tanggal')
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(fn ($query, array $data) =>
                        $query
                            ->when($data['dari'],
                                fn ($q) => $q->whereDate('tanggal', '>=', $data['dari'])
                            )
                            ->when($data['sampai'],
                                fn ($q) => $q->whereDate('tanggal', '<=', $data['sampai'])
                            )
                    )
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['dari'] ?? null) {
                            $indicators[] = 'Dari: ' . \Carbon\Carbon::parse($data['dari'])->format('d/m/Y');
                        }
                        if ($data['sampai'] ?? null) {
                            $indicators[] = 'Sampai: ' . \Carbon\Carbon::parse($data['sampai'])->format('d/m/Y');
                        }
                        return $indicators;
                    }),

                // Filter kehadiran
                SelectFilter::make('kehadiran')
                    ->label('Kehadiran')
                    ->options([
                        'hadir'       => '✅ Hadir',
                        'tidak_hadir' => '❌ Tidak Hadir',
                        'izin'        => '📋 Izin',
                        'sakit'       => '🏥 Sakit',
                    ]),

                // Filter keaktifan
                SelectFilter::make('keaktifan')
                    ->label('Keaktifan')
                    ->options([
                        'aktif'                    => '✅ Aktif',
                        'pasif'                    => '⚠️ Pasif',
                        'perlu_pembinaan_lanjutan' => '🔴 Perlu Pembinaan Lanjutan',
                    ]),

                // Filter kegiatan
                SelectFilter::make('kegiatan_id')
                    ->label('Kegiatan')
                    ->relationship('kegiatan', 'nama_kegiatan')
                    ->searchable()
                    ->preload(),

            ])

            ->recordActions([
                ViewAction::make()
                    ->modalHeading(fn ($record) =>
                        $record->wargaBinaan?->nama_lengkap . ' — ' .
                        \Carbon\Carbon::parse($record->tanggal)->format('d/m/Y')
                    )
                    ->modal(),

                EditAction::make()
                    ->modalHeading('Edit Absensi')
                    ->modal()
                    ->successNotificationTitle('Absensi berhasil diperbarui')
                    ->mutateRecordDataUsing(function (array $data): array {
                        $data['dicatat_oleh'] = auth()->id();
                        return $data;
                    })
                    // ── TAMBAHKAN BAGIAN INI ──
                    ->before(function (array $data, \Filament\Actions\EditAction $action, $record) {
                        if (! self::validateTanggalFrekuensi($data, $action)) {
                            return;
                        }

                        $sudahAda = \App\Models\Absensi::where('warga_binaan_id', $data['warga_binaan_id'])
                            ->where('kegiatan_id', $data['kegiatan_id'])
                            ->whereDate('tanggal', $data['tanggal'])
                            ->where('id', '!=', $record->id) // kecualikan record yang sedang diedit
                            ->exists();

                        if ($sudahAda) {
                            $wb = \App\Models\WargaBinaan::find($data['warga_binaan_id']);
                            $kg = \App\Models\Kegiatan::find($data['kegiatan_id']);

                            \Filament\Notifications\Notification::make()
                                ->title('Data sudah ada!')
                                ->body(
                                    "Absensi {$wb?->nama_lengkap} untuk \"{$kg?->nama_kegiatan}\" " .
                                    "pada " . \Carbon\Carbon::parse($data['tanggal'])->format('d/m/Y') .
                                    " sudah pernah diinput."
                                )
                                ->warning()
                                ->persistent()
                                ->send();

                            $action->halt();
                        }
                    }),

                DeleteAction::make()
                    ->modalHeading('Hapus Data Absensi')
                    ->modalDescription(fn ($record) =>
                        "Hapus absensi {$record->wargaBinaan?->nama_lengkap} " .
                        "pada " . \Carbon\Carbon::parse($record->tanggal)->format('d/m/Y') . "?"
                    )
                    ->modalSubmitActionLabel('Ya, Hapus'),
            ])

            ->toolbarActions([
            CreateAction::make()
                ->label('Input Absensi')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->modalHeading('Input Absensi Warga Binaan')
                ->modal()
                ->modalSubmitActionLabel('Simpan Absensi')
                ->successNotificationTitle('Absensi berhasil disimpan')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['dicatat_oleh'] = auth()->id();
                    return $data;
                })
                // ── TAMBAHKAN BAGIAN INI ──
                ->before(function (array $data, \Filament\Actions\CreateAction $action) {
                    if (! self::validateTanggalFrekuensi($data, $action)) {
                        return;
                    }

                    $sudahAda = \App\Models\Absensi::where('warga_binaan_id', $data['warga_binaan_id'])
                        ->where('kegiatan_id', $data['kegiatan_id'])
                        ->whereDate('tanggal', $data['tanggal'])
                        ->exists();

                    if ($sudahAda) {
                        $wb = \App\Models\WargaBinaan::find($data['warga_binaan_id']);
                        $kg = \App\Models\Kegiatan::find($data['kegiatan_id']);

                        \Filament\Notifications\Notification::make()
                            ->title('Data sudah ada!')
                            ->body(
                                "Absensi {$wb?->nama_lengkap} untuk kegiatan " .
                                "\"{$kg?->nama_kegiatan}\" pada tanggal " .
                                \Carbon\Carbon::parse($data['tanggal'])->format('d/m/Y') .
                                " sudah pernah diinput."
                            )
                            ->warning()
                            ->persistent()
                            ->send();

                        $action->halt(); // ← hentikan proses simpan
                    }
                }),

                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->modalHeading('Hapus Absensi Terpilih')
                        ->modalDescription('Data yang dihapus tidak dapat dikembalikan.')
                        ->modalSubmitActionLabel('Ya, Hapus'),
                ]),
            ])

            ->defaultSort('tanggal', 'desc')
            ->striped()
            ->emptyStateIcon('heroicon-o-clipboard-document-check')
            ->emptyStateHeading('Belum ada data absensi')
            ->emptyStateDescription('Klik "Input Absensi" untuk mulai mencatat kehadiran.')
            ->emptyStateActions([
                CreateAction::make()
                    ->label('Input Absensi Pertama')
                    ->modalHeading('Input Absensi Warga Binaan')
                    ->modal()
                    ->before(function (array $data, \Filament\Actions\CreateAction $action) {
                        self::validateTanggalFrekuensi($data, $action);
                    })
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['dicatat_oleh'] = auth()->id();
                        return $data;
                    }),
            ]);
    }

    private static function validateTanggalFrekuensi(array $data, \Filament\Actions\Action $action): bool
    {
        if (blank($data['kegiatan_id'] ?? null) || blank($data['tanggal'] ?? null)) {
            return true;
        }

        $kegiatan = Kegiatan::find($data['kegiatan_id']);

        if (! $kegiatan) {
            return true;
        }

        $tanggal = Carbon::parse($data['tanggal']);

        if ($kegiatan->isTanggalSesuaiFrekuensi($tanggal)) {
            return true;
        }

        \Filament\Notifications\Notification::make()
            ->title('Tanggal tidak sesuai frekuensi kegiatan')
            ->body($kegiatan->getFrekuensiHint())
            ->danger()
            ->persistent()
            ->send();

        $action->halt();

        return false;
    }
}