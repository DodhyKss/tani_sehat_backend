<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GAD extends Model
{
    protected $table = 'gad';

    protected $fillable = [
        'warga_id',
        'skor',
        'tgl_gad',
    ];

    protected $casts = [
        'tgl_gad' => 'date',
    ];

    protected $appends = ['kategori', 'warna', 'tingkat_kecemasan'];

    public function warga()
    {
        return $this->belongsTo(User::class, 'warga_id');
    }

    /**
     * Kategori GAD7 (simplified):
     * 0-4: Normal (hijau)
     * 5-9: Ringan (kuning)
     * 10-21: Sedang-Tinggi (merah)
     */
    public function getKategoriAttribute(): string
    {
        if ($this->skor <= 4) return 'normal';
        if ($this->skor <= 9) return 'ringan';
        if ($this->skor <= 14) return 'sedang';
        return 'tinggi';
    }

    /**
     * Tingkat Kecemasan (detailed):
     * 0-4: Minim
     * 5-9: Ringan
     * 10-14: Sedang
     * 15-21: Berat
     */
    public function getTingkatKecemasanAttribute(): string
    {
        if ($this->skor <= 4) return 'Minim';
        if ($this->skor <= 9) return 'Ringan';
        if ($this->skor <= 14) return 'Sedang';
        return 'Berat';
    }

    public function getWarnaAttribute(): string
    {
        return match ($this->kategori) {
            'normal' => 'hijau',
            'ringan' => 'kuning',
            default => 'merah',
        };
    }
}
