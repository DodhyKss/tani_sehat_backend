<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GAD;
use App\Models\JawabanKuesioner;
use App\Models\Kuesioner;
use App\Models\JadwalPengisian;
use App\Models\Materi;
use App\Models\Video;
use App\Models\Gambar;
use App\Models\RekomendasiOlahraga;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GADController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $wargaId = $request->get('warga_id', $user->id);
        if ($user->role === 'warga') $wargaId = $user->id;

        $data = GAD::where('warga_id', $wargaId)->orderBy('tgl_gad', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jawaban' => 'required|array|min:1',
            'jawaban.*.kuesioner_id' => 'required|exists:kuesioner,id',
            'jawaban.*.skor' => 'required|integer|min:0|max:3',
        ]);

        $user = $request->user();
        $totalSkor = 0;

        foreach ($request->jawaban as $jwb) {
            JawabanKuesioner::create([
                'kuesioner_id' => $jwb['kuesioner_id'],
                'warga_id' => $user->id,
                'skor' => $jwb['skor'],
            ]);
            $totalSkor += $jwb['skor'];
        }

        $gad = GAD::create([
            'warga_id' => $user->id,
            'skor' => $totalSkor,
            'tgl_gad' => Carbon::today(),
        ]);

        $mapKategori = match ($gad->kategori) {
            'normal' => 'normal', 'ringan' => 'ringan', default => 'sedang',
        };

        $rekomendasi = [
            'materi' => Materi::where('kategori_gad', $mapKategori)->get(),
            'video' => Video::where('kategori_gad', $mapKategori)->get(),
            'gambar' => Gambar::where('kategori_gad', $mapKategori)->get(),
            'olahraga' => RekomendasiOlahraga::where('kategori_gad', $mapKategori)->get(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Kuesioner GAD7 berhasil disimpan',
            'data' => [
                'gad' => $gad, 'skor_total' => $totalSkor,
                'kategori' => $gad->kategori, 'tingkat_kecemasan' => $gad->tingkat_kecemasan,
                'warna' => $gad->warna, 'rekomendasi' => $rekomendasi,
            ],
        ], 201);
    }

    public function cekJadwal(Request $request)
    {
        $user = $request->user();
        $jadwal = JadwalPengisian::where('jenis_pengisian', 'gad7')->first();

        if (!$jadwal) {
            return response()->json(['success' => true, 'data' => ['harus_isi' => true, 'pesan' => 'Jadwal belum diatur, silakan isi kuesioner GAD7.']]);
        }

        $lastInput = GAD::where('warga_id', $user->id)->orderBy('tgl_gad', 'desc')->first();
        if (!$lastInput) {
            return response()->json(['success' => true, 'data' => ['harus_isi' => true, 'pesan' => 'Belum pernah mengisi kuesioner GAD7.']]);
        }

        $nextDeadline = match ($jadwal->tipe) {
            'hours' => Carbon::parse($lastInput->created_at)->addHours($jadwal->jumlah),
            'day' => Carbon::parse($lastInput->tgl_gad)->addDays($jadwal->jumlah),
            'week' => Carbon::parse($lastInput->tgl_gad)->addWeeks($jadwal->jumlah),
            'month' => Carbon::parse($lastInput->tgl_gad)->addMonths($jadwal->jumlah),
            'year' => Carbon::parse($lastInput->tgl_gad)->addYears($jadwal->jumlah),
        };

        $harusIsi = Carbon::now()->gte($nextDeadline);
        return response()->json([
            'success' => true,
            'data' => [
                'harus_isi' => $harusIsi, 'terakhir_isi' => $lastInput->tgl_gad,
                'deadline_berikutnya' => $nextDeadline->toDateTimeString(),
                'pesan' => $harusIsi ? 'Sudah waktunya mengisi kuesioner GAD7.' : 'Belum waktunya mengisi kuesioner GAD7.',
            ],
        ]);
    }

    public function show(Request $request, $id)
    {
        $gad = GAD::findOrFail($id);
        $user = $request->user();
        if ($user->role === 'warga' && $gad->warga_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }
        return response()->json(['success' => true, 'data' => $gad]);
    }

    public function kuesioner()
    {
        return response()->json(['success' => true, 'data' => Kuesioner::all()]);
    }
}
