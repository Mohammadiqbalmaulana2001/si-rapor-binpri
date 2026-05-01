<?php

namespace App\Contracts;

use App\Models\Absensi;
use Illuminate\Database\Eloquent\Collection;

interface AbsensiRepositoryInterface
{
    public function getByWargaBinaan(string $wargaBinaanId, int $bulan, int $tahun): Collection;
    public function getByKegiatan(string $kegiatanId, string $tanggal): Collection;
    public function getForCalendar(string $wargaBinaanId, string $startDate, string $endDate): Collection;
    public function create(array $data): Absensi;
    public function bulkUpsert(array $data): void;
    public function updateKeaktifan(string $id, string $keaktifan, ?string $catatan): Absensi;
    public function getSummaryByBulan(string $wargaBinaanId, int $bulan, int $tahun): array;
}