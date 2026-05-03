<?php

namespace App\Filament\Resources\Raports\Pages;

use App\Filament\Resources\Raports\RaportResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewRaport extends ViewRecord
{
    protected static string $resource = RaportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->modal()
                ->hidden(fn () => $this->record->is_finalized),

            Action::make('finalisasi')
                ->label('Finalisasi')
                ->icon('heroicon-o-lock-closed')
                ->color('success')
                ->hidden(fn () => $this->record->is_finalized)
                ->requiresConfirmation()
                ->modalHeading('Finalisasi Raport')
                ->modalDescription(
                    "Raport akan dikunci setelah difinalisasi dan tidak dapat diubah lagi."
                )
                ->modalSubmitActionLabel('Ya, Finalisasi')
                ->action(function () {
                    $this->record->update(['is_finalized' => true]);
                    Notification::make()
                        ->title('Raport berhasil difinalisasi')
                        ->success()
                        ->send();
                    $this->refreshFormData(['is_finalized']);
                }),

            DeleteAction::make()
                ->hidden(fn () => $this->record->is_finalized),
        ];
    }
}