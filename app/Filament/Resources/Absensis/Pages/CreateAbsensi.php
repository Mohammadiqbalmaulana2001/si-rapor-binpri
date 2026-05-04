<?php

namespace App\Filament\Resources\Absensis\Pages;

use App\Filament\Resources\Absensis\AbsensiResource;
use App\Models\Kegiatan;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Filament\Resources\Pages\CreateRecord;

class CreateAbsensi extends CreateRecord
{
    protected static string $resource = AbsensiResource::class;

    protected function beforeCreate(): void
    {
        $this->validateTanggalSesuaiFrekuensi($this->form->getState());
    }

    protected function mutateFormDataBeforeCreate(array $data): array
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