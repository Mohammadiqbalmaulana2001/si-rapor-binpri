<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WargaBinaan extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'warga_binaans';

    protected $fillable = [
        'nama_lengkap', 'no_register', 'nomor_induk', 'agama',
        'blok_kamar', 'tanggal_masuk', 'tanggal_bebas',
        'jenis_kejahatan', 'status', 'foto',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_bebas' => 'date',
    ];

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'warga_binaan_id');
    }

    public function raports()
    {
        return $this->hasMany(Raport::class, 'warga_binaan_id');
    }

    // Accessor: sisa masa tahanan
    public function getSisaMasaTahananAttribute(): ?string
    {
        if (!$this->tanggal_bebas) return null;
        if ($this->tanggal_bebas->isPast()) return 'Sudah Bebas';
        return $this->tanggal_bebas->diffForHumans(now(), ['parts' => 2]);
    }

    // Accessor: persentase kehadiran bulan ini
    public function getPersentaseKehadiranBulanIniAttribute(): float
    {
        $total = $this->absensis()
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        if ($total === 0) return 0;

        $hadir = $this->absensis()
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->where('kehadiran', 'hadir')
            ->count();

        return round(($hadir / $total) * 100, 1);
    }
}