<?php

namespace App\Filament\Resources\Kegiatans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class KegiatansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nama_kegiatan')
                    ->label('Nama Kegiatan')
                    ->searchable()
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::SemiBold)
                    ->description(fn ($record) => $record->deskripsi
                        ? \Illuminate\Support\Str::limit($record->deskripsi, 55)
                        : null
                    ),

                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'Islam'    => 'warning',
                        'Kristen',
                        'Katolik'  => 'info',
                        'Hindu'    => 'success',
                        'Buddha'   => 'purple',
                        'Umum'     => 'gray',
                        default    => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('frekuensi')
                    ->label('Frekuensi')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'harian'   => 'success',
                        'mingguan' => 'info',
                        'bulanan'  => 'warning',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'harian'   => '📅 Harian',
                        'mingguan' => '📆 Mingguan',
                        'bulanan'  => '🗓️ Bulanan',
                        default    => $state,
                    }),

                TextColumn::make('hari')
                    ->label('Hari')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('jam_mulai')
                    ->label('Jam')
                    ->formatStateUsing(fn ($state, $record) =>
                        $state
                            ? \Carbon\Carbon::parse($state)->format('H:i')
                              . ($record->jam_selesai
                                  ? ' – ' . \Carbon\Carbon::parse($record->jam_selesai)->format('H:i')
                                  : '')
                            : '—'
                    )
                    ->toggleable(),

                TextColumn::make('penanggung_jawab')
                    ->label('Penanggung Jawab')
                    ->placeholder('—')
                    ->searchable()
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

            ])

            ->filters([
                SelectFilter::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'Islam'    => 'Islam',
                        'Kristen'  => 'Kristen',
                        'Katolik'  => 'Katolik',
                        'Hindu'    => 'Hindu',
                        'Buddha'   => 'Buddha',
                        'Konghucu' => 'Konghucu',
                        'Umum'     => 'Umum',
                    ]),

                SelectFilter::make('frekuensi')
                    ->label('Frekuensi')
                    ->options([
                        'harian'   => 'Harian',
                        'mingguan' => 'Mingguan',
                        'bulanan'  => 'Bulanan',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Aktif saja')
                    ->falseLabel('Nonaktif saja')
                    ->placeholder('Semua'),
            ])

            // ── RECORD ACTIONS — semua pakai modal ──
            ->recordActions([
                ViewAction::make()
                    ->modalHeading(fn ($record) => $record->nama_kegiatan)
                    ->modal(),

                EditAction::make()
                    ->modalHeading('Edit Kegiatan')
                    ->modal()
                    ->successNotificationTitle('Kegiatan berhasil diperbarui'),

                DeleteAction::make()
                    ->modalHeading('Hapus Kegiatan')
                    ->modalDescription(fn ($record) =>
                        "Yakin ingin menghapus \"{$record->nama_kegiatan}\"? "
                        . 'Data absensi terkait akan ikut terhapus.'
                    )
                    ->modalSubmitActionLabel('Ya, Hapus'),
            ])

            // ── TOOLBAR ACTIONS ──
            ->toolbarActions([
                CreateAction::make()
                    ->label('Tambah Kegiatan')
                    ->icon('heroicon-o-plus')
                    ->color('info')
                    ->modalHeading('Tambah Program Kegiatan Baru')
                    ->modal()
                    ->modalSubmitActionLabel('Simpan Kegiatan')
                    ->successNotificationTitle('Kegiatan berhasil ditambahkan'),

                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->modalHeading('Hapus Kegiatan Terpilih')
                        ->modalDescription('Kegiatan yang dipilih akan dihapus permanen.')
                        ->modalSubmitActionLabel('Ya, Hapus Semua'),
                ]),
            ])

            ->defaultSort('kategori')
            ->striped()
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->emptyStateHeading('Belum ada kegiatan')
            ->emptyStateDescription('Klik "Tambah Kegiatan" untuk memulai.')
            ->emptyStateActions([
                CreateAction::make()
                    ->label('Tambah Kegiatan Pertama')
                    ->modalHeading('Tambah Program Kegiatan Baru')
                    ->modal(),
            ]);
    }
}