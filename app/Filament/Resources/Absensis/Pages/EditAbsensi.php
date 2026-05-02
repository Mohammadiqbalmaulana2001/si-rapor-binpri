<?php

namespace App\Filament\Resources\Absensis\Pages;

use App\Filament\Resources\Absensis\AbsensiResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAbsensi extends EditRecord
{
    protected static string $resource = AbsensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->modalHeading(fn () =>
                    $this->record->wargaBinaan?->nama_lengkap . ' — ' .
                    \Carbon\Carbon::parse($this->record->tanggal)->format('d/m/Y')
                )
                ->modal(),
            DeleteAction::make()
                ->modalHeading('Hapus Data Absensi')
                ->modalDescription(fn () =>
                    "Hapus absensi {$this->record->wargaBinaan?->nama_lengkap} " .
                    "pada " . \Carbon\Carbon::parse($this->record->tanggal)->format('d/m/Y') . "?"
                ),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['dicatat_oleh'] = auth()->id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}