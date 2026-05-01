<?php

namespace App\Repositories;

use App\Contracts\AbsensiRepositoryInterface;
use App\Models\Absensi;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AbsensiRepository implements AbsensiRepositoryInterface
{
    public function __construct(private Absensi $model) {}

    public function getByWargaBinaan(string $wargaBinaanId, int $bulan, int $tahun): Collection
    {
        return $this->model
            ->with(['kegiatan'])
            ->where('warga_binaan_id', $wargaBinaanId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')
            ->get();
    }

    public function getByKegiatan(string $kegiatanId, string $tanggal): Collection
    {
        return $this->model
            ->with(['wargaBinaan'])
            ->where('kegiatan_id', $kegiatanId)
            ->where('tanggal', $tanggal)
            ->get();
    }

    // Untuk FullCalendar: ambil semua absensi dalam rentang tanggal
    public function getForCalendar(string $wargaBinaanId, string $startDate, string $endDate): Collection
    {
        return $this->model
            ->with(['kegiatan'])
            ->where('warga_binaan_id', $wargaBinaanId)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();
    }

    public function create(array $data): Absensi
    {
        return $this->model->create($data);
    }

    // Upsert massal — hindari duplikat, update jika sudah ada
    public function bulkUpsert(array $data): void
    {
        // Karena UUID, kita manual loop dengan updateOrCreate
        foreach ($data as $row) {
            $this->model->updateOrCreate(
                [
                    'warga_binaan_id' => $row['warga_binaan_id'],
                    'kegiatan_id'     => $row['kegiatan_id'],
                    'tanggal'         => $row['tanggal'],
                ],
                [
                    'kehadiran'   => $row['kehadiran'],
                    'keaktifan'   => $row['keaktifan'] ?? null,
                    'catatan'     => $row['catatan'] ?? null,
                    'dicatat_oleh' => $row['dicatat_oleh'] ?? auth()->id(),
                ]
            );
        }
    }

    public function updateKeaktifan(string $id, string $keaktifan, ?string $catatan): Absensi
    {
        $absensi = $this->model->findOrFail($id);
        $absensi->update(['keaktifan' => $keaktifan, 'catatan' => $catatan]);
        return $absensi->fresh();
    }

    public function getSummaryByBulan(string $wargaBinaanId, int $bulan, int $tahun): array
    {
        $result = $this->model
            ->where('warga_binaan_id', $wargaBinaanId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN kehadiran = 'hadir' THEN 1 ELSE 0 END) as total_hadir,
                SUM(CASE WHEN keaktifan = 'aktif' THEN 1 ELSE 0 END) as total_aktif,
                SUM(CASE WHEN keaktifan = 'pasif' THEN 1 ELSE 0 END) as total_pasif,
                SUM(CASE WHEN keaktifan = 'perlu_pembinaan_lanjutan' THEN 1 ELSE 0 END) as total_perlu_pembinaan
            ")
            ->first();

        return $result ? $result->toArray() : [
            'total' => 0, 'total_hadir' => 0,
            'total_aktif' => 0, 'total_pasif' => 0, 'total_perlu_pembinaan' => 0,
        ];
    }
}