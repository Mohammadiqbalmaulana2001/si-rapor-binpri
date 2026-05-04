<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasUuids;

    private const HARI_BY_ISO_DAY = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        7 => 'Minggu',
    ];

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

    public function isTanggalSesuaiFrekuensi(CarbonInterface | string $tanggal): bool
    {
        $tanggal = $tanggal instanceof CarbonInterface
            ? Carbon::parse($tanggal->toDateString())
            : Carbon::parse($tanggal);

        return match ($this->frekuensi) {
            'harian' => true,
            'mingguan' => $this->isHariMingguanSesuai($tanggal),
            'bulanan' => $this->isTanggalBulananSesuai($tanggal),
            default => false,
        };
    }

    public function getFrekuensiHint(): string
    {
        return match ($this->frekuensi) {
            'harian' => 'Kegiatan harian: bisa diinput setiap tanggal.',
            'mingguan' => 'Kegiatan mingguan: hanya hari ' .
                collect($this->hari ?? [])->filter()->implode(', ') . '.',
            'bulanan' => 'Kegiatan bulanan: hanya tanggal ' .
                collect($this->tanggal_bulanan ?? [])->filter()->implode(', ') . ' setiap bulan.',
            default => 'Frekuensi kegiatan tidak valid.',
        };
    }

    private function isHariMingguanSesuai(Carbon $tanggal): bool
    {
        $hariTerpilih = collect($this->hari ?? [])
            ->filter(fn ($hari) => filled($hari))
            ->map(fn ($hari) => (string) $hari)
            ->values();

        if ($hariTerpilih->isEmpty()) {
            return false;
        }

        $namaHari = self::HARI_BY_ISO_DAY[$tanggal->dayOfWeekIso] ?? null;

        return $namaHari ? $hariTerpilih->contains($namaHari) : false;
    }

    private function isTanggalBulananSesuai(Carbon $tanggal): bool
    {
        $tanggalTerpilih = collect($this->tanggal_bulanan ?? [])
            ->map(fn ($hari) => (int) $hari)
            ->filter(fn (int $hari) => $hari >= 1 && $hari <= 31)
            ->values();

        if ($tanggalTerpilih->isEmpty()) {
            return false;
        }

        return $tanggalTerpilih->contains((int) $tanggal->day);
    }
}
