<?php

namespace App\Filament\Resources\WargaBinaans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class WargaBinaansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('id')
                //     ->label('ID')
                //     ->searchable(),
                TextColumn::make('nama_lengkap')
                    ->searchable(),
                TextColumn::make('no_register')
                    ->searchable(),
                TextColumn::make('nomor_induk')
                    ->searchable(),
                TextColumn::make('agama')
                    ->badge(),
                TextColumn::make('blok_kamar')
                    ->searchable(),
                TextColumn::make('tanggal_masuk')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_bebas')
                    ->date()
                    ->sortable(),
                TextColumn::make('jenis_kejahatan')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('foto')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
