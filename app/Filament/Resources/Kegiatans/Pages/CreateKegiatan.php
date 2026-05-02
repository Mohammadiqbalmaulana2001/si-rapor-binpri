<?php

namespace App\Filament\Resources\Kegiatans\Pages;

use App\Filament\Resources\Kegiatans\KegiatanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKegiatan extends CreateRecord
{
    protected static string $resource = KegiatanResource::class;

    // Redirect ke list setelah create
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
