<?php

namespace App\Repositories;

use App\Contracts\KegiatanRepositoryInterface;
use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Collection;

class KegiatanRepository implements KegiatanRepositoryInterface
{
    public function __construct(private Kegiatan $model) {}

    public function getAll(): Collection
    {
        return $this->model->orderBy('kategori')->orderBy('jam_mulai')->get();
    }

    public function getAktif(): Collection
    {
        return $this->model->where('is_active', true)->get();
    }

    public function getByKategori(string $kategori): Collection
    {
        return $this->model->where('is_active', true)
            ->whereIn('kategori', [$kategori, 'Umum'])
            ->get();
    }

    public function findById(string $id): ?Kegiatan
    {
        return $this->model->find($id);
    }

    public function create(array $data): Kegiatan
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): Kegiatan
    {
        $kegiatan = $this->model->findOrFail($id);
        $kegiatan->update($data);
        return $kegiatan->fresh();
    }

    public function delete(string $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}