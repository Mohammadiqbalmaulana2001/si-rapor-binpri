<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WargaBinaan extends Model
{
    use SoftDeletes;

    protected $table = 'warga_binaans';

    protected $fillable = [
        'nama_lengkap',
        'no_register',
        'nomor_induk',
        'agama',
        'blok_kamar',
        'tanggal_masuk',
        'tanggal_bebas',
        'jenis_kejahatan',
        'status',
        'foto',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_bebas' => 'date',
    ];

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function raports()
    {
        return $this->hasMany(Raport::class);
    }
}
