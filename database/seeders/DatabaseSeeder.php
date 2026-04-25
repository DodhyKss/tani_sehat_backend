<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'nik' => '1111111111111111',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'nama_lengkap' => 'Admin Tani Sehat',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'no_hp' => '081234567890',
        ]);

        // Kader 1
        $kader1 = User::create([
            'nik' => '2222222222222222',
            'password' => Hash::make('password'),
            'role' => 'kader',
            'nama_lengkap' => 'Kader Ani',
            'tanggal_lahir' => '1985-05-15',
            'jenis_kelamin' => 'Perempuan',
            'no_hp' => '082111111111',
        ]);

        // Warga 1
        $warga1 = User::create([
            'nik' => '3333333333333333',
            'password' => Hash::make('password'),
            'role' => 'warga',
            'nama_lengkap' => 'Warga Budi',
            'tanggal_lahir' => '1970-10-20',
            'jenis_kelamin' => 'Laki-laki',
            'no_hp' => '083111111111',
        ]);

        // Assign warga1 to kader1
        \App\Models\Warga::create([
            'warga_id' => $warga1->id,
            'kader_id' => $kader1->id,
        ]);

        // Jadwal Default
        \App\Models\JadwalPengisian::create([
            'tipe' => 'day',
            'jumlah' => 1,
            'jenis_pengisian' => 'td'
        ]);

        \App\Models\JadwalPengisian::create([
            'tipe' => 'week',
            'jumlah' => 2,
            'jenis_pengisian' => 'gad7'
        ]);

        // Soal Kuesioner GAD7 Default
        $soal = [
            'Merasa gugup, cemas, atau tegang',
            'Tidak mampu berhenti atau mengendalikan kekhawatiran',
            'Terlalu khawatir tentang berbagai hal',
            'Kesulitan bersantai',
            'Sangat gelisah sehingga sulit untuk duduk diam',
            'Menjadi mudah kesal atau marah',
            'Merasa takut seolah-olah sesuatu yang mengerikan mungkin terjadi'
        ];

        foreach ($soal as $s) {
            \App\Models\Kuesioner::create(['soal' => $s]);
        }
    }
}
