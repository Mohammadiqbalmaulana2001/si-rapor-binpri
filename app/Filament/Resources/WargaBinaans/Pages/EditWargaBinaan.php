<?php

namespace App\Filament\Resources\WargaBinaans\Pages;

use App\Filament\Resources\WargaBinaans\WargaBinaanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWargaBinaan extends EditRecord
{
    protected static string $resource = WargaBinaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
