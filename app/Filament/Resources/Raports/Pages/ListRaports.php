<?php

namespace App\Filament\Resources\Raports\Pages;

use App\Filament\Resources\Raports\RaportResource;
use Filament\Resources\Pages\ListRecords;

class ListRaports extends ListRecords
{
    protected static string $resource = RaportResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}