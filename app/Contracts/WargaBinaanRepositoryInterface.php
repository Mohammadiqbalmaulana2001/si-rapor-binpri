<?php

namespace App\Contracts;

use App\Models\WargaBinaan;
use Illuminate\Database\Eloquent\Collection;

interface WargaBinaanRepositoryInterface
{
    public function getAll(array $filters = []): Collection;
    public function findById(string $id): ?WargaBinaan;
    public function findByNoRegister(string $noRegister): ?WargaBinaan;
    public function create(array $data): WargaBinaan;
    public function update(string $id, array $data): WargaBinaan;
    public function delete(string $id): bool;
    public function getAktif(): Collection;
}