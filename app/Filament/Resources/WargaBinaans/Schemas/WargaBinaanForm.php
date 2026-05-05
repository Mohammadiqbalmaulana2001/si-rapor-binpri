<?php

namespace App\Filament\Resources\WargaBinaans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WargaBinaanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Foto & Identitas Utama')
                    ->description('Data identitas warga binaan')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        FileUpload::make('foto')
                            ->label('Foto Warga Binaan')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->imageEditorAspectRatios(['1:1'])
                            ->directory('warga-binaan')
                            ->visibility('public')
                            ->columnSpanFull(),

                        TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nama sesuai dokumen resmi'),

                        TextInput::make('no_register')
                            ->label('Nomor Register')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('Contoh: B.123/2024'),

                        TextInput::make('nomor_induk')
                            ->label('NIK (Nomor Induk Kependudukan)')
                            ->maxLength(16)
                            ->placeholder('16 digit NIK')
                            ->default(null),

                        Select::make('agama')
                            ->label('Agama')
                            ->options([
                                'Islam'    => '☪️ Islam',
                                'Kristen'  => '✝️ Kristen',
                                'Katolik'  => '✝️ Katolik',
                                'Hindu'    => '🕉️ Hindu',
                                'Buddha'   => '☸️ Buddha',
                                'Konghucu' => '☯️ Konghucu',
                            ])
                            ->required()
                            ->searchable(),
                    ])
                    ->columns(2),

                Section::make('Data Penempatan & Masa Pidana')
                    ->description('Informasi blok, masa pidana, dan status')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        TextInput::make('blok_kamar')
                            ->label('Blok / Kamar')
                            ->placeholder('Contoh: A1-05')
                            ->default(null),

                        TextInput::make('jenis_kejahatan')
                            ->label('Jenis Kejahatan / Perkara')
                            ->placeholder('Contoh: Narkotika Pasal 112')
                            ->default(null),

                        DatePicker::make('tanggal_masuk')
                            ->label('Tanggal Masuk LAPAS')
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->maxDate(now()),

                        DatePicker::make('tanggal_bebas')
                            ->label('Tanggal Bebas (estimasi)')
                            ->displayFormat('d/m/Y')
                            ->minDate(now()),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'aktif'  => '🟢 Aktif',
                                'bebas'  => '⚪ Bebas',
                                'pindah' => '🟡 Pindah',
                            ])
                            ->default('aktif')
                            ->required(),
                    ])
                    ->columns(2),

            ]);
    }
}