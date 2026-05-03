<?php

namespace App\Filament\Resources\Raports\Pages;

use App\Filament\Resources\Raports\RaportResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRaport extends EditRecord
{
    protected static string $resource = RaportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->modal(),
            DeleteAction::make()
                ->hidden(fn () => $this->record->is_finalized),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}