<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nik',
        'password',
        'role',
        'nama_lengkap',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_hp',
        'foto',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'tanggal_lahir' => 'date',
        ];
    }

    // Relasi: User sebagai warga -> punya kader
    public function wargaRelasi()
    {
        return $this->hasOne(Warga::class, 'warga_id');
    }

    // Relasi: User sebagai kader -> punya banyak warga
    public function kaderRelasi()
    {
        return $this->hasMany(Warga::class, 'kader_id');
    }

    public function tekananDarah()
    {
        return $this->hasMany(TekananDarah::class, 'warga_id');
    }

    public function gad()
    {
        return $this->hasMany(GAD::class, 'warga_id');
    }

    public function jawabanKuesioner()
    {
        return $this->hasMany(JawabanKuesioner::class, 'warga_id');
    }

    public function statusKesehatan()
    {
        return $this->hasOne(StatusKesehatan::class, 'warga_id');
    }

    public function reproduksi()
    {
        return $this->hasMany(Reproduksi::class, 'warga_id');
    }

    // Messages where user is kader
    public function messagesAsKader()
    {
        return $this->hasMany(Message::class, 'kader_id');
    }

    // Messages where user is warga
    public function messagesAsWarga()
    {
        return $this->hasMany(Message::class, 'warga_id');
    }
}
