<?php

namespace App\Filament\Resources\Kegiatans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KegiatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Kegiatan')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([

                        TextInput::make('nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Sholat Dhuha, Khataman, Senam Pagi...')
                            ->columnSpanFull(),

                        Select::make('kategori')
                            ->label('Kategori / Agama')
                            ->options([
                                'Islam'    => '☪️  Islam',
                                'Kristen'  => '✝️  Kristen',
                                'Katolik'  => '✝️  Katolik',
                                'Hindu'    => '🕉️  Hindu',
                                'Buddha'   => '☸️  Buddha',
                                'Konghucu' => '☯️  Konghucu',
                                'Umum'     => '🌐  Umum (Semua Agama)',
                            ])
                            ->required()
                            ->searchable(),

                        Select::make('frekuensi')
                            ->label('Frekuensi Pelaksanaan')
                            ->options([
                                'harian'   => '📅 Harian',
                                'mingguan' => '📆 Mingguan',
                                'bulanan'  => '🗓️ Bulanan',
                            ])
                            ->required()
                            ->live(),

                        Select::make('hari')
                            ->label('Hari Pelaksanaan')
                            ->options([
                                'Senin'  => 'Senin',
                                'Selasa' => 'Selasa',
                                'Rabu'   => 'Rabu',
                                'Kamis'  => 'Kamis',
                                'Jumat'  => 'Jumat',
                                'Sabtu'  => 'Sabtu',
                                'Minggu' => 'Minggu',
                            ])
                            ->placeholder('Pilih hari...')
                            ->visible(fn ($get) => $get('frekuensi') === 'mingguan')
                            ->required(fn ($get) => $get('frekuensi') === 'mingguan'),

                    ])
                    ->columns(2),

                Section::make('Waktu & Penanggung Jawab')
                    ->icon('heroicon-o-clock')
                    ->schema([

                        TimePicker::make('jam_mulai')
                            ->label('Jam Mulai')
                            ->seconds(false),

                        TimePicker::make('jam_selesai')
                            ->label('Jam Selesai')
                            ->seconds(false)
                            ->after('jam_mulai'),

                        TextInput::make('penanggung_jawab')
                            ->label('Penanggung Jawab')
                            ->placeholder('Nama Ustad / Pendeta / Petugas')
                            ->maxLength(255),

                        Toggle::make('is_active')
                            ->label('Kegiatan Aktif')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->helperText('Nonaktifkan jika kegiatan dihentikan sementara'),

                        Textarea::make('deskripsi')
                            ->label('Deskripsi / Keterangan')
                            ->placeholder('Keterangan tambahan tentang kegiatan ini...')
                            ->rows(3)
                            ->columnSpanFull(),

                    ])
                    ->columns(2),

            ]);
    }
}