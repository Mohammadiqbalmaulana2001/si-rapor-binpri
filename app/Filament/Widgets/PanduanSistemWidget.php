<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class PanduanSistemWidget extends Widget
{
    protected string $view = 'filament.widgets.panduan-sistem-widget';
    protected static ?int $sort = -1000;
    protected int | string | array $columnSpan = 'full';
}
