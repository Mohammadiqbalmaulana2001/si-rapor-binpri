<?php

namespace App\Filament\Resources\WargaBinaans\Pages;

use App\Filament\Resources\WargaBinaans\WargaBinaanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWargaBinaan extends ViewRecord
{
    protected static string $resource = WargaBinaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
