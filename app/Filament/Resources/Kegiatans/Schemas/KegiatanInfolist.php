<?php

namespace App\Filament\Resources\Kegiatans\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;

class KegiatanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Kegiatan')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([

                        TextEntry::make('nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->weight(\Filament\Support\Enums\FontWeight::Bold)
                            ->size(TextSize::Large)
                            ->columnSpanFull(),

                        TextEntry::make('kategori')
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
                            }),

                        TextEntry::make('frekuensi')
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

                        TextEntry::make('hari')
                            ->label('Hari Pelaksanaan')
                            ->placeholder('—')
                            ->state(fn ($record) =>
                                is_array($record->hari) && count(array_filter($record->hari))
                                    ? implode(', ', array_filter($record->hari))
                                    : '—'
                            )
                            ->visible(fn ($record) => $record->frekuensi === 'mingguan'),

                            TextEntry::make('tanggal_bulanan')
                            ->label('Tanggal Pelaksanaan')
                            ->placeholder('—')
                            ->state(function ($record) {
                                $tanggal = collect($record->tanggal_bulanan)->filter()->values();

                                return $tanggal->isNotEmpty()
                                    ? 'Setiap tanggal: ' . $tanggal->implode(', ')
                                    : '—';
                            })
                            ->visible(fn ($record) => $record?->frekuensi === 'bulanan'),

                    ])
                    ->columns(2),

                Section::make('Waktu & Penanggung Jawab')
                    ->icon('heroicon-o-clock')
                    ->schema([

                        TextEntry::make('jam_mulai')
                            ->label('Jam Mulai')
                            ->placeholder('—')
                            ->formatStateUsing(fn ($state) => $state
                                ? \Carbon\Carbon::parse($state)->format('H:i')
                                : '—'
                            ),

                        TextEntry::make('jam_selesai')
                            ->label('Jam Selesai')
                            ->placeholder('—')
                            ->formatStateUsing(fn ($state) => $state
                                ? \Carbon\Carbon::parse($state)->format('H:i')
                                : '—'
                            ),

                        TextEntry::make('penanggung_jawab')
                            ->label('Penanggung Jawab')
                            ->placeholder('—'),

                        IconEntry::make('is_active')
                            ->label('Status Kegiatan')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),

                        TextEntry::make('deskripsi')
                            ->label('Deskripsi')
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
                            ->label('Terakhir Diupdate')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(2),

            ]);
    }
}