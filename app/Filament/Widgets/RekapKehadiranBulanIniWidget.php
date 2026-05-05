<?php

namespace App\Filament\Widgets;

use App\Models\Absensi;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class RekapKehadiranBulanIniWidget extends Widget
{
    protected string $view = 'filament.widgets.rekap-kehadiran-bulan-ini-widget';
    protected int | string | array $columnSpan = 'full';
    
    protected function getViewData(): array
    {
        $now = Carbon::now();
        $bulanIni = $now->month;
        $tahunIni = $now->year;
        
        $mingguKe = [1, 2, 3, 4];
        $dataKehadiran = [];
        
        foreach ($mingguKe as $minggu) {
            $startOfWeek = Carbon::now()->startOfMonth()->addWeeks($minggu - 1)->startOfWeek();
            $endOfWeek = $startOfWeek->copy()->endOfWeek();
            
            if ($endOfWeek->month > $bulanIni) {
                $endOfWeek = Carbon::now()->endOfMonth();
            }
            if ($startOfWeek->month < $bulanIni) {
                $startOfWeek = Carbon::now()->startOfMonth();
            }
            
            $total = Absensi::whereBetween('tanggal', [$startOfWeek, $endOfWeek])->count();
            $hadir = Absensi::whereBetween('tanggal', [$startOfWeek, $endOfWeek])
                ->where('kehadiran', 'hadir')
                ->count();
                
            $dataKehadiran[] = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;
        }
        
        $statusKehadiran = [
            'Hadir' => Absensi::whereMonth('tanggal', $bulanIni)
                ->whereYear('tanggal', $tahunIni)
                ->where('kehadiran', 'hadir')
                ->count(),
            'Izin' => Absensi::whereMonth('tanggal', $bulanIni)
                ->whereYear('tanggal', $tahunIni)
                ->where('kehadiran', 'izin')
                ->count(),
            'Sakit' => Absensi::whereMonth('tanggal', $bulanIni)
                ->whereYear('tanggal', $tahunIni)
                ->where('kehadiran', 'sakit')
                ->count(),
            'Alpha' => Absensi::whereMonth('tanggal', $bulanIni)
                ->whereYear('tanggal', $tahunIni)
                ->where('kehadiran', 'alpha')
                ->count(),
        ];
        
        return [
            'bulan' => Carbon::now()->locale('id')->isoFormat('MMMM YYYY'),
            'mingguan' => $dataKehadiran,
            'statusKehadiran' => $statusKehadiran,
        ];
    }
}