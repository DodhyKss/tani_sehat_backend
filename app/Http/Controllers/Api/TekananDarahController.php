<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TekananDarah;
use App\Models\JadwalPengisian;
use App\Models\Materi;
use App\Models\Video;
use App\Models\Gambar;
use App\Models\RekomendasiOlahraga;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TekananDarahController extends Controller
{
    /**
     * GET /api/tekanan-darah
     * Warga: Riwayat tekanan darah sendiri
     * Kader/Admin: bisa lihat punya warga lain via ?warga_id=
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $wargaId = $request->get('warga_id', $user->id);

        // Warga hanya bisa lihat data sendiri
        if ($user->role === 'warga') {
            $wargaId = $user->id;
        }

        $data = TekananDarah::where('warga_id', $wargaId)
            ->orderBy('tgl_cek', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * POST /api/tekanan-darah
     * Warga: Input tekanan darah harian
     */
    public function store(Request $request)
    {
        $request->validate([
            'systolic' => 'required|integer|min:50|max:300',
            'diastolic' => 'required|integer|min:30|max:200',
        ]);

        $user = $request->user();

        $td = TekananDarah::create([
            'warga_id' => $user->id,
            'systolic' => $request->systolic,
            'diastolic' => $request->diastolic,
            'tgl_cek' => Carbon::today(),
        ]);

        // Ambil rekomendasi berdasarkan kategori TD
        $kategoriTd = $td->kategori; // normal, pra_hipertensi, hipertensi
        $mapKategori = match ($kategoriTd) {
            'hipertensi' => 'hipertensi',
            'pra_hipertensi' => 'pre_hipertensi',
            default => 'normal',
        };

        $rekomendasi = [
            'materi' => Materi::where('kategori_td', $mapKategori)->get(),
            'video' => Video::where('kategori_td', $mapKategori)->get(),
            'gambar' => Gambar::where('kategori_td', $mapKategori)->get(),
            'olahraga' => RekomendasiOlahraga::where('kategori_td', $mapKategori)->get(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Tekanan darah berhasil disimpan',
            'data' => [
                'tekanan_darah' => $td,
                'kategori' => $td->kategori,
                'warna' => $td->warna,
                'rekomendasi' => $rekomendasi,
            ],
        ], 201);
    }

    /**
     * GET /api/tekanan-darah/cek-jadwal
     * Cek apakah warga sudah waktunya mengisi tekanan darah
     */
    public function cekJadwal(Request $request)
    {
        $user = $request->user();

        $jadwal = JadwalPengisian::where('jenis_pengisian', 'td')->first();

        if (!$jadwal) {
            return response()->json([
                'success' => true,
                'data' => [
                    'harus_isi' => true,
                    'pesan' => 'Jadwal belum diatur, silakan isi tekanan darah.',
                ],
            ]);
        }

        // Cari pengisian terakhir
        $lastInput = TekananDarah::where('warga_id', $user->id)
            ->orderBy('tgl_cek', 'desc')
            ->first();

        if (!$lastInput) {
            return response()->json([
                'success' => true,
                'data' => [
                    'harus_isi' => true,
                    'pesan' => 'Belum pernah mengisi tekanan darah.',
                ],
            ]);
        }

        // Hitung deadline berdasarkan jadwal
        $nextDeadline = match ($jadwal->tipe) {
            'hours' => Carbon::parse($lastInput->created_at)->addHours($jadwal->jumlah),
            'day' => Carbon::parse($lastInput->tgl_cek)->addDays($jadwal->jumlah),
            'week' => Carbon::parse($lastInput->tgl_cek)->addWeeks($jadwal->jumlah),
            'month' => Carbon::parse($lastInput->tgl_cek)->addMonths($jadwal->jumlah),
            'year' => Carbon::parse($lastInput->tgl_cek)->addYears($jadwal->jumlah),
        };

        $harusIsi = Carbon::now()->gte($nextDeadline);

        return response()->json([
            'success' => true,
            'data' => [
                'harus_isi' => $harusIsi,
                'terakhir_isi' => $lastInput->tgl_cek,
                'deadline_berikutnya' => $nextDeadline->toDateTimeString(),
                'pesan' => $harusIsi
                    ? 'Sudah waktunya mengisi tekanan darah.'
                    : 'Belum waktunya mengisi tekanan darah.',
            ],
        ]);
    }

    /**
     * GET /api/tekanan-darah/{id}
     */
    public function show(Request $request, $id)
    {
        $td = TekananDarah::findOrFail($id);

        $user = $request->user();
        if ($user->role === 'warga' && $td->warga_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $td,
        ]);
    }
}
