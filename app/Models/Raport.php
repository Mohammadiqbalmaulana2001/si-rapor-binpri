<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Raport extends Model
{
    protected $table = 'raports';

    protected $fillable = [
        'warga_binaan_id',
        'tahun',
        'bulan',
        'total_kegiatan',
        'total_hadir',
        'total_aktif',
        'total_pasif',
        'total_perlu_pembinaan',
        'persentase_kehadiran',
        'rekomendasi',
        'catatan_petugas',
        'is_finalized',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'bulan' => 'integer',
        'total_kegiatan' => 'integer',
        'total_hadir' => 'integer',
        'total_aktif' => 'integer',
        'total_pasif' => 'integer',
        'total_perlu_pembinaan' => 'integer',
        'persentase_kehadiran' => 'decimal:2',
        'is_finalized' => 'boolean',
    ];

    public function wargaBinaan()
    {
        return $this->belongsTo(WargaBinaan::class);
    }
}
