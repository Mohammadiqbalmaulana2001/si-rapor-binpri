<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Raport;
use App\Models\WargaBinaan;

class RaportService
{
    /**
     * Generate raport untuk semua WBP aktif pada bulan & tahun tertentu
     * Dipanggil dari action "Generate Raport Bulanan"
     */
    public function generateBulanan(int $bulan, int $tahun): int
    {
        $wargaBinaan = WargaBinaan::where('status', 'aktif')->get();
        $count = 0;

        foreach ($wargaBinaan as $wb) {
            $berhasil = $this->generateSatu($wb->id, $bulan, $tahun);
            if ($berhasil) $count++;
        }

        return $count;
    }

    /**
     * Generate raport untuk satu WBP
     */
    public function generateSatu(string $wbId, int $bulan, int $tahun): bool
    {
        // Ambil semua absensi WBP pada bulan & tahun tsb
        $absensis = Absensi::where('warga_binaan_id', $wbId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        if ($absensis->isEmpty()) return false;

        $total        = $absensis->count();
        $totalHadir   = $absensis->where('kehadiran', 'hadir')->count();
        $totalAktif   = $absensis->where('keaktifan', 'aktif')->count();
        $totalPasif   = $absensis->where('keaktifan', 'pasif')->count();
        $totalPerlu   = $absensis->where('keaktifan', 'perlu_pembinaan_lanjutan')->count();
        $persentase   = round(($totalHadir / $total) * 100, 2);

        // Hitung rekomendasi otomatis
        $rekomendasi = $this->hitungRekomendasi($persentase, $totalAktif, $totalPasif, $totalPerlu);

        // Upsert — update kalau sudah ada, insert kalau belum
        Raport::updateOrCreate(
            [
                'warga_binaan_id' => $wbId,
                'bulan'           => $bulan,
                'tahun'           => $tahun,
            ],
            [
                'total_kegiatan'        => $total,
                'total_hadir'           => $totalHadir,
                'total_aktif'           => $totalAktif,
                'total_pasif'           => $totalPasif,
                'total_perlu_pembinaan' => $totalPerlu,
                'persentase_kehadiran'  => $persentase,
                'rekomendasi'           => $rekomendasi,
                // is_finalized TIDAK direset — raport yang sudah final tidak diubah
            ]
        );

        return true;
    }

    /**
     * Logika rekomendasi otomatis
     *
     * Sangat Baik : hadir >= 90% DAN aktif > pasif+perlu
     * Baik        : hadir >= 75%
     * Cukup       : hadir >= 60%
     * Kurang      : hadir < 60%
     */
    private function hitungRekomendasi(
        float $persentase,
        int   $aktif,
        int   $pasif,
        int   $perlu
    ): string {
        return match(true) {
            $persentase >= 90 && $aktif > ($pasif + $perlu) => 'sangat_baik',
            $persentase >= 75                               => 'baik',
            $persentase >= 60                               => 'cukup',
            default                                         => 'kurang',
        };
    }
}