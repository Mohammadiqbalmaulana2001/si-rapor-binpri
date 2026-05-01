<?php

namespace App\Filament\Resources\WargaBinaans\Pages;

use App\Filament\Resources\WargaBinaans\WargaBinaanResource;
use App\Models\Absensi;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\IconPosition;

class ViewWargaBinaan extends ViewRecord
{
    protected static string $resource = WargaBinaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
            ->label('Edit')
            ->icon('heroicon-o-pencil')
            ->color('warning')
            ->button(),
        ];
    }
}