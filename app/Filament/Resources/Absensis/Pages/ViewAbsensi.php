<?php

namespace App\Filament\Resources\Absensis\Pages;

use App\Filament\Resources\Absensis\AbsensiResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAbsensi extends ViewRecord
{
    protected static string $resource = AbsensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
             EditAction::make()
                ->modalHeading('Edit Absensi')
                ->modal()
                ->mutateRecordDataUsing(function (array $data): array {
                    $data['dicatat_oleh'] = auth()->id();
                    return $data;
                }),
            DeleteAction::make()
                ->modalHeading('Hapus Data Absensi')
                ->modalDescription(fn () =>
                    "Hapus absensi {$this->record->wargaBinaan?->nama_lengkap} " .
                    "pada " . \Carbon\Carbon::parse($this->record->tanggal)->format('d/m/Y') . "?"
                ),
        ];
    }
}
