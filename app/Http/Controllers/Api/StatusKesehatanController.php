<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StatusKesehatan;
use App\Models\TekananDarah;
use App\Models\GAD;
use App\Models\JadwalPengisian;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatusKesehatanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $status = StatusKesehatan::where('warga_id', $user->id)->first();
        
        if (!$status) {
            $status = StatusKesehatan::create([
                'warga_id' => $user->id,
                'tekanan_darah' => null,
                'skor_gad' => null,
                'kategori_gad' => 'normal',
                'kategori_td' => 'normal',
                'tgl_update' => now(),
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }
    
    public function cekJadwal(Request $request)
    {
        $user = $request->user();
        
        $tdJadwal = JadwalPengisian::where('jenis_pengisian', 'td')->first();
        $gadJadwal = JadwalPengisian::where('jenis_pengisian', 'gad7')->first();
        
        $lastTd = TekananDarah::where('warga_id', $user->id)->orderBy('tgl_cek', 'desc')->first();
        $lastGad = GAD::where('warga_id', $user->id)->orderBy('tgl_gad', 'desc')->first();
        
        $tdWaiting = false;
        $gadWaiting = false;
        $tdNextAllowed = null;
        $gadNextAllowed = null;
        
        if ($tdJadwal && $lastTd) {
            $tdNextAllowed = match ($tdJadwal->tipe) {
                'hours' => Carbon::parse($lastTd->tgl_cek)->addHours($tdJadwal->jumlah),
                'day' => Carbon::parse($lastTd->tgl_cek)->addDays($tdJadwal->jumlah),
                'week' => Carbon::parse($lastTd->tgl_cek)->addWeeks($tdJadwal->jumlah),
                'month' => Carbon::parse($lastTd->tgl_cek)->addMonths($tdJadwal->jumlah),
                default => Carbon::parse($lastTd->tgl_cek)->addDays($tdJadwal->jumlah),
            };
            $tdWaiting = Carbon::now()->lt($tdNextAllowed);
        }
        
        if ($gadJadwal && $lastGad) {
            $gadNextAllowed = match ($gadJadwal->tipe) {
                'hours' => Carbon::parse($lastGad->tgl_gad)->addHours($gadJadwal->jumlah),
                'day' => Carbon::parse($lastGad->tgl_gad)->addDays($gadJadwal->jumlah),
                'week' => Carbon::parse($lastGad->tgl_gad)->addWeeks($gadJadwal->jumlah),
                'month' => Carbon::parse($lastGad->tgl_gad)->addMonths($gadJadwal->jumlah),
                default => Carbon::parse($lastGad->tgl_gad)->addDays($gadJadwal->jumlah),
            };
            $gadWaiting = Carbon::now()->lt($gadNextAllowed);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'td' => [
                    'is_waiting' => $tdWaiting,
                    'next_allowed' => $tdNextAllowed?->toDateTimeString(),
                    'jadwal' => $tdJadwal
                ],
                'gad7' => [
                    'is_waiting' => $gadWaiting,
                    'next_allowed' => $gadNextAllowed?->toDateTimeString(),
                    'jadwal' => $gadJadwal
                ]
            ]
        ]);
    }
    
    public function updateTd(Request $request)
    {
        $user = $request->user();
        
        $jadwal = JadwalPengisian::where('jenis_pengisian', 'td')->first();
        if ($jadwal) {
            $lastTd = TekananDarah::where('warga_id', $user->id)->orderBy('tgl_cek', 'desc')->first();
            if ($lastTd) {
                $nextAllowed = match ($jadwal->tipe) {
                    'hours' => Carbon::parse($lastTd->tgl_cek)->addHours($jadwal->jumlah),
                    'day' => Carbon::parse($lastTd->tgl_cek)->addDays($jadwal->jumlah),
                    'week' => Carbon::parse($lastTd->tgl_cek)->addWeeks($jadwal->jumlah),
                    'month' => Carbon::parse($lastTd->tgl_cek)->addMonths($jadwal->jumlah),
                    default => Carbon::parse($lastTd->tgl_cek)->addDays($jadwal->jumlah),
                };
                
                if (Carbon::now()->lt($nextAllowed)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Belum waktunya mengisi tekanan darah. Dapat mengisi lagi dalam ' . $nextAllowed->diffForHumans(),
                        'data' => [
                            'next_allowed' => $nextAllowed->toDateTimeString(),
                            'is_waiting' => true,
                            'jadwal' => $jadwal
                        ]
                    ], 422);
                }
            }
        }
        
        $request->validate([
            'systolic' => 'required|integer|min:50|max:300',
            'diastolic' => 'required|integer|min:30|max:200',
        ]);
        
        $systolic = $request->systolic;
        $diastolic = $request->diastolic;
        
        if ($systolic < 120 && $diastolic < 80) {
            $kategori = 'normal';
        } elseif ($systolic <= 139 && $diastolic <= 89) {
            $kategori = 'pre_hipertensi';
        } else {
            $kategori = 'hipertensi';
        }
        
        $tekananDarah = TekananDarah::create([
            'warga_id' => $user->id,
            'systolic' => $systolic,
            'diastolic' => $diastolic,
            'tgl_cek' => now(),
        ]);
        
        $existingStatus = StatusKesehatan::where('warga_id', $user->id)->first();
        $updateData = [
            'tekanan_darah' => $systolic . '/' . $diastolic,
            'kategori_td' => $kategori,
            'tgl_update' => now(),
        ];
        
        if ($existingStatus) {
            $updateData['kategori_gad'] = $existingStatus->kategori_gad ?: 'normal';
        } else {
            $updateData['kategori_gad'] = 'normal';
        }
        
        $status = StatusKesehatan::updateOrCreate(
            ['warga_id' => $user->id],
            $updateData
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Tekanan darah berhasil disimpan',
            'data' => [
                'tekanan_darah' => $tekananDarah,
                'kategori_td' => $kategori,
                'status' => $status
            ]
        ], 201);
    }
    
    public function updateGad(Request $request)
    {
        $user = $request->user();
        
        $jadwal = JadwalPengisian::where('jenis_pengisian', 'gad7')->first();
        if ($jadwal) {
            $lastGad = GAD::where('warga_id', $user->id)->orderBy('tgl_gad', 'desc')->first();
            if ($lastGad) {
                $nextAllowed = match ($jadwal->tipe) {
                    'hours' => Carbon::parse($lastGad->tgl_gad)->addHours($jadwal->jumlah),
                    'day' => Carbon::parse($lastGad->tgl_gad)->addDays($jadwal->jumlah),
                    'week' => Carbon::parse($lastGad->tgl_gad)->addWeeks($jadwal->jumlah),
                    'month' => Carbon::parse($lastGad->tgl_gad)->addMonths($jadwal->jumlah),
                    default => Carbon::parse($lastGad->tgl_gad)->addDays($jadwal->jumlah),
                };
                
                if (Carbon::now()->lt($nextAllowed)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Belum waktunya mengisi GAD7. Dapat mengisi lagi dalam ' . $nextAllowed->diffForHumans(),
                        'data' => [
                            'next_allowed' => $nextAllowed->toDateTimeString(),
                            'is_waiting' => true,
                            'jadwal' => $jadwal
                        ]
                    ], 422);
                }
            }
        }
        
        $request->validate([
            'skor' => 'required|integer|min:0|max:21',
        ]);
        
        $skor = $request->skor;
        
        if ($skor <= 4) {
            $kategori = 'normal';
        } elseif ($skor <= 9) {
            $kategori = 'ringan';
        } elseif ($skor <= 14) {
            $kategori = 'sedang';
        } else {
            $kategori = 'tinggi';
        }
        
        $gad = GAD::create([
            'warga_id' => $user->id,
            'skor' => $skor,
            'tgl_gad' => now(),
        ]);
        
        $existingStatus = StatusKesehatan::where('warga_id', $user->id)->first();
        $updateData = [
            'skor_gad' => $skor,
            'kategori_gad' => $kategori,
            'tgl_update' => now(),
        ];
        
        if ($existingStatus) {
            $updateData['kategori_td'] = $existingStatus->kategori_td ?: 'normal';
        } else {
            $updateData['kategori_td'] = 'normal';
        }
        
        $status = StatusKesehatan::updateOrCreate(
            ['warga_id' => $user->id],
            $updateData
        );
        
        return response()->json([
            'success' => true,
            'message' => 'GAD7 berhasil disimpan',
            'data' => [
                'gad' => $gad,
                'kategori_gad' => $kategori,
                'status' => $status
            ]
        ], 201);
    }
}