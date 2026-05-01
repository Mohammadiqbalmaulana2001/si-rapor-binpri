<?php

namespace App\Filament\Resources\WargaBinaans\Pages;

use App\Filament\Resources\WargaBinaans\WargaBinaanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWargaBinaans extends ListRecords
{
    protected static string $resource = WargaBinaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
