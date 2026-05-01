<?php

namespace App\Contracts;

use App\Models\Raport;
use Illuminate\Database\Eloquent\Collection;

interface RaportRepositoryInterface
{
    public function getByWargaBinaan(string $wargaBinaanId): Collection;
    public function findByBulan(string $wargaBinaanId, int $bulan, int $tahun): ?Raport;
    public function upsert(string $wargaBinaanId, int $bulan, int $tahun, array $data): Raport;
    public function finalize(string $id): Raport;
}