<?php

namespace App\Filament\Resources\WargaBinaans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WargaBinaanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_lengkap')
                    ->required(),
                TextInput::make('no_register')
                    ->required(),
                TextInput::make('nomor_induk')
                    ->default(null),
                Select::make('agama')
                    ->options([
            'Islam' => 'Islam',
            'Kristen' => 'Kristen',
            'Katolik' => 'Katolik',
            'Hindu' => 'Hindu',
            'Buddha' => 'Buddha',
            'Konghucu' => 'Konghucu',
        ])
                    ->required(),
                TextInput::make('blok_kamar')
                    ->default(null),
                DatePicker::make('tanggal_masuk')
                    ->required(),
                DatePicker::make('tanggal_bebas'),
                TextInput::make('jenis_kejahatan')
                    ->default(null),
                Select::make('status')
                    ->options(['aktif' => 'Aktif', 'bebas' => 'Bebas', 'pindah' => 'Pindah'])
                    ->default('aktif')
                    ->required(),
                TextInput::make('foto')
                    ->default(null),
            ]);
    }
}
