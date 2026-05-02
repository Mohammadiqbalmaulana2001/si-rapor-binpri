<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasUuids;

    protected $table = 'kegiatans';

    protected $fillable = [
        'nama_kegiatan',
        'kategori',
        'deskripsi',
        'frekuensi',
        'hari',
        'tanggal_bulanan',
        'jam_mulai',
        'jam_selesai',
        'penanggung_jawab',
        'is_active',
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'is_active' => 'boolean',
        'hari'       => 'array',
        'tanggal_bulanan'  => 'array',
    ];

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'kegiatan_id');
    }
}
