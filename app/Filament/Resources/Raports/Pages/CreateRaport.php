<?php

namespace App\Filament\Resources\Raports\Pages;

use App\Filament\Resources\Raports\RaportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRaport extends CreateRecord
{
    protected static string $resource = RaportResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}