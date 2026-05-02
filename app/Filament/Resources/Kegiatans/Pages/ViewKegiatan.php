<?php

namespace App\Filament\Resources\Kegiatans\Pages;

use App\Filament\Resources\Kegiatans\KegiatanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKegiatan extends ViewRecord
{
    protected static string $resource = KegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->modalHeading('Edit Kegiatan')
                ->modal(),
            DeleteAction::make()
                ->modalHeading('Hapus Kegiatan')
                ->modalDescription(fn () =>
                    "Yakin ingin menghapus \"{$this->record->nama_kegiatan}\"?"
                ),
        ];
    }
}
