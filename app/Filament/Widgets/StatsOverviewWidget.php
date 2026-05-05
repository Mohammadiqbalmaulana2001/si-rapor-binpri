<?php

namespace App\Filament\Widgets;

use App\Models\WargaBinaan;
use App\Models\Absensi;
use App\Models\Kegiatan;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        // Total WBP aktif (tidak terhapus softDelete)
        $totalWbpAktif = WargaBinaan::count();
        
        // Kehadiran hari ini
        $hadirHariIni = Absensi::where('tanggal', Carbon::today())
            ->where('kehadiran', 'hadir')
            ->count();
        
        $totalAbsensiHariIni = Absensi::where('tanggal', Carbon::today())->count();
        $persentaseKehadiranHariIni = $totalAbsensiHariIni > 0 
            ? round(($hadirHariIni / $totalAbsensiHariIni) * 100, 1) 
            : 0;
        
        // Kegiatan aktif
        $kegiatanAktif = Kegiatan::where('is_active', true)->count();
        
        // WBP akan bebas dalam 30 hari
        $wbpAkanBebas = WargaBinaan::where('tanggal_bebas', '<=', Carbon::now()->addDays(30))
            ->where('tanggal_bebas', '>=', Carbon::today())
            ->count();
        
        return [
            Stat::make('Total Warga Binaan', $totalWbpAktif)
                ->description('WBP aktif saat ini')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 8, 7]),
            
            Stat::make('Kehadiran Hari Ini', $persentaseKehadiranHariIni . '%')
                ->description($hadirHariIni . ' dari ' . $totalAbsensiHariIni . ' WBP hadir')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($persentaseKehadiranHariIni >= 80 ? 'success' : ($persentaseKehadiranHariIni >= 50 ? 'warning' : 'danger'))
                ->chart([$hadirHariIni, $totalAbsensiHariIni, $persentaseKehadiranHariIni]),
            
            Stat::make('Kegiatan Aktif', $kegiatanAktif)
                ->description('Kegiatan yang sedang berjalan')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info')
                ->chart([5, 6, 5, 7, 6, 8, $kegiatanAktif]),
            
            Stat::make('Akan Bebas', $wbpAkanBebas)
                ->description('WBP akan bebas dalam 30 hari')
                ->descriptionIcon('heroicon-m-arrow-right-circle')
                ->color($wbpAkanBebas > 0 ? 'success' : 'gray')
                ->chart([3, 2, 4, 3, 5, 4, $wbpAkanBebas]),
        ];
    }
}