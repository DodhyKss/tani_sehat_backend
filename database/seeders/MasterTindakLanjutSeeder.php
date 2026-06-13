<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterTindakLanjutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_tindakan' => 'Pertahankan pola makan sehat', 'jenis_tindakan' => 'td', 'kategori' => 'normal'],
            ['nama_tindakan' => 'Kurangi konsumsi garam & perbanyak sayur', 'jenis_tindakan' => 'td', 'kategori' => 'pra_hipertensi'],
            ['nama_tindakan' => 'Monitoring rutin & terapkan Diet DASH', 'jenis_tindakan' => 'td', 'kategori' => 'hipertensi'],
            
            ['nama_tindakan' => 'Pertahankan aktivitas sehari-hari', 'jenis_tindakan' => 'gad7', 'kategori' => 'normal'],
            ['nama_tindakan' => 'Latihan napas dalam 5–10 menit', 'jenis_tindakan' => 'gad7', 'kategori' => 'ringan'],
            ['nama_tindakan' => 'Relaksasi minimal 10–15 menit setiap hari', 'jenis_tindakan' => 'gad7', 'kategori' => 'sedang'],
            ['nama_tindakan' => 'Segera berkonsultasi dengan tenaga kesehatan', 'jenis_tindakan' => 'gad7', 'kategori' => 'tinggi'],
        ];

        foreach ($data as $item) {
            \App\Models\MasterTindakLanjut::create($item);
        }
    }
}
