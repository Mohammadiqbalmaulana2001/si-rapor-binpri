<?php

namespace App\Filament\Widgets;

use App\Models\Kegiatan;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class KegiatanHariIniWidget extends Widget
{
    protected string $view = 'filament.widgets.kegiatan-hari-ini-widget';
    protected int | string | array $columnSpan = 'full';
    
    protected function getViewData(): array
    {
        $today = Carbon::today();
        
        $kegiatans = Kegiatan::where('is_active', true)
            ->get()
            ->filter(fn ($kegiatan) => $kegiatan->isTanggalSesuaiFrekuensi($today))
            ->sortBy('jam_mulai');
        
        return [
            'kegiatans' => $kegiatans,
            'tanggal' => $today->locale('id')->isoFormat('dddd, D MMMM YYYY'),
        ];
    }
}