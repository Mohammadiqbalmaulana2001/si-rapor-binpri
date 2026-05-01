<?php

namespace App\Contracts;

use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Collection;

interface KegiatanRepositoryInterface
{
    public function getAll(): Collection;
    public function getAktif(): Collection;
    public function getByKategori(string $kategori): Collection;
    public function findById(string $id): ?Kegiatan;
    public function create(array $data): Kegiatan;
    public function update(string $id, array $data): Kegiatan;
    public function delete(string $id): bool;
}