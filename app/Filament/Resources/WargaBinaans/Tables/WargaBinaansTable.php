<?php

namespace App\Filament\Resources\WargaBinaans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class WargaBinaansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // ── FOTO ──
                ImageColumn::make('foto')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn ($record) =>
                        'https://ui-avatars.com/api/?name=' .
                        urlencode($record->nama_lengkap ?? 'WBP') .
                        '&background=1e3a5f&color=fff&size=64'
                    )
                    ->size(40),

                // ── IDENTITAS ──
                TextColumn::make('no_register')
                    ->label('No. Register')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),

                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->blok_kamar
                        ? 'Blok: ' . $record->blok_kamar
                        : null
                    ),

                TextColumn::make('agama')
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

                // ── MASA TAHANAN ──
                TextColumn::make('tanggal_masuk')
                    ->label('Tgl Masuk')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('tanggal_bebas')
                    ->label('Tgl Bebas')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('-')
                    ->color(fn ($state) =>
                        $state && \Carbon\Carbon::parse($state)->isPast()
                            ? 'danger' : null
                    ),

                // ── STATUS ──
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'aktif'  => 'success',
                        'bebas'  => 'gray',
                        'pindah' => 'warning',
                        default  => 'gray',
                    }),

                // ── KOLOM TERSEMBUNYI (toggle) ──
                TextColumn::make('jenis_kejahatan')
                    ->label('Perkara')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('nomor_induk')
                    ->label('NIK')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'aktif'  => 'Aktif',
                        'bebas'  => 'Bebas',
                        'pindah' => 'Pindah',
                    ]),

                SelectFilter::make('agama')
                    ->label('Agama')
                    ->options([
                        'Islam'    => 'Islam',
                        'Kristen'  => 'Kristen',
                        'Katolik'  => 'Katolik',
                        'Hindu'    => 'Hindu',
                        'Buddha'   => 'Buddha',
                        'Konghucu' => 'Konghucu',
                    ]),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                ->modal(),
                EditAction::make()
                ->label('Edit')
                ->modal(),
                DeleteAction::make()
                ->label('Hapus')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading(fn($record) => "Hapus Warga Binaan: {$record->nama_lengkap}")
                ->modalDescription('Apakah kamu yakin ingin menghapus data ini?, data yang sudah dihapus tidak bisa dikembalikan!')
                ->modalSubmitActionLabel('Ya, Hapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}