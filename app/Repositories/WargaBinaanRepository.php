<?php

namespace App\Repositories;

use App\Contracts\WargaBinaanRepositoryInterface;
use App\Models\WargaBinaan;
use Illuminate\Database\Eloquent\Collection;

class WargaBinaanRepository implements WargaBinaanRepositoryInterface
{
    public function __construct(private WargaBinaan $model) {}

    public function getAll(array $filters = []): Collection
    {
        return $this->model->when(
            isset($filters['status']),
            fn ($q) => $q->where('status', $filters['status'])
        )->get();
    }

    public function findById(string $id): ?WargaBinaan
    {
        return $this->model->find($id);
    }

    public function findByNoRegister(string $noRegister): ?WargaBinaan
    {
        return $this->model->where('no_register', $noRegister)->first();
    }

    public function create(array $data): WargaBinaan
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): WargaBinaan
    {
        $wb = $this->model->findOrFail($id);
        $wb->update($data);
        return $wb->fresh();
    }

    public function delete(string $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function getAktif(): Collection
    {
        return $this->model->where('status', 'aktif')->get();
    }
}