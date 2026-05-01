<?php

namespace App\Filament\Resources\WargaBinaans\Pages;

use App\Filament\Resources\WargaBinaans\WargaBinaanResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWargaBinaan extends EditRecord
{
    protected static string $resource = WargaBinaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
            ->action(fn () => $this->getRecord()->delete())
            ->label('Hapus')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->modalHeading(fn($record) => "Hapus Warga Binaan: {$record->nama_lengkap}")
            ->modalDescription('Apakah kamu yakin ingin menghapus data ini?, data yang sudah dihapus tidak bisa dikembalikan!')
            ->modalSubmitActionLabel('Ya, Hapus'),
            Action::make('Kembali')
            ->label('Kembali')
            ->icon('heroicon-o-arrow-left')
            ->color('secondary')
            ->url(WargaBinaanResource::getUrl('index')),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
