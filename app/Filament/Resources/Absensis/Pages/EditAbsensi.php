<?php

namespace App\Filament\Resources\Absensis\Pages;

use App\Filament\Resources\Absensis\AbsensiResource;
use App\Models\Kegiatan;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAbsensi extends EditRecord
{
    protected static string $resource = AbsensiResource::class;

    protected function beforeSave(): void
    {
        $this->validateTanggalSesuaiFrekuensi($this->form->getState());
    }

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

    private function validateTanggalSesuaiFrekuensi(array $data): void
    {
        if (blank($data['kegiatan_id'] ?? null) || blank($data['tanggal'] ?? null)) {
            return;
        }

        $kegiatan = Kegiatan::find($data['kegiatan_id']);

        if (! $kegiatan) {
            return;
        }

        if ($kegiatan->isTanggalSesuaiFrekuensi(Carbon::parse($data['tanggal']))) {
            return;
        }

        throw ValidationException::withMessages([
            'tanggal' => $kegiatan->getFrekuensiHint(),
        ]);
    }
}