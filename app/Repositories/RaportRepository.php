<?php

namespace App\Repositories;

use App\Contracts\RaportRepositoryInterface;
use App\Models\Raport;
use Illuminate\Database\Eloquent\Collection;

class RaportRepository implements RaportRepositoryInterface
{
    public function __construct(private Raport $model) {}

    public function getByWargaBinaan(string $wargaBinaanId): Collection
    {
        return $this->model
            ->where('warga_binaan_id', $wargaBinaanId)
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();
    }

    public function findByBulan(string $wargaBinaanId, int $bulan, int $tahun): ?Raport
    {
        return $this->model
            ->where('warga_binaan_id', $wargaBinaanId)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->first();
    }

    public function upsert(string $wargaBinaanId, int $bulan, int $tahun, array $data): Raport
    {
        return $this->model->updateOrCreate(
            ['warga_binaan_id' => $wargaBinaanId, 'bulan' => $bulan, 'tahun' => $tahun],
            $data
        );
    }

    public function finalize(string $id): Raport
    {
        $raport = $this->model->findOrFail($id);
        $raport->update(['is_finalized' => true]);
        return $raport->fresh();
    }
}