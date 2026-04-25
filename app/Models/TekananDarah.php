<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TekananDarah extends Model
{
    protected $table = 'tekanan_darah';

    protected $fillable = [
        'warga_id',
        'systolic',
        'diastolic',
        'tgl_cek',
    ];

    protected $casts = [
        'tgl_cek' => 'date',
    ];

    protected $appends = ['kategori', 'warna'];

    public function warga()
    {
        return $this->belongsTo(User::class, 'warga_id');
    }

    /**
     * Kategori Tekanan Darah:
     * Normal: systolic < 120 AND diastolic < 80
     * Pra-hipertensi: systolic 120-139 OR diastolic 80-89
     * Hipertensi: systolic >= 140 OR diastolic >= 90
     */
    public function getKategoriAttribute(): string
    {
        if ($this->systolic >= 140 || $this->diastolic >= 90) {
            return 'hipertensi';
        }
        if ($this->systolic >= 120 || $this->diastolic >= 80) {
            return 'pra_hipertensi';
        }
        return 'normal';
    }

    public function getWarnaAttribute(): string
    {
        return match ($this->kategori) {
            'hipertensi' => 'merah',
            'pra_hipertensi' => 'kuning',
            default => 'hijau',
        };
    }
}
