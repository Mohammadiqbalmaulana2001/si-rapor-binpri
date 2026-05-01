<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasUuids;

    protected $table = 'absensis';

    protected $fillable = [
        'warga_binaan_id',
        'kegiatan_id',
        'tanggal',
        'kehadiran',
        'keaktifan',
        'catatan',
        'dicatat_oleh',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function wargaBinaan()
    {
        return $this->belongsTo(WargaBinaan::class, 'warga_binaan_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
