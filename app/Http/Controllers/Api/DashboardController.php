<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TekananDarah;
use App\Models\GAD;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * GET /api/dashboard/tekanan-darah
     * Grafik bar+pie TD per minggu: berapa Normal, Pra-Hipertensi, Hipertensi
     * Query param: ?weeks=4 (default 4 minggu terakhir)
     */
    public function grafikTekananDarah(Request $request)
    {
        $user = $request->user();
        
        $query = DB::table('status_kesehatan');

        // Kader: hanya lihat warga-nya
        if ($user->role === 'kader') {
            $wargaIds = Warga::where('kader_id', $user->id)->pluck('warga_id');
            $query->whereIn('warga_id', $wargaIds);
        }

        $stats = $query->select('kategori_td', DB::raw('count(*) as total'))
            ->groupBy('kategori_td')
            ->get();

        $totalPie = ['normal' => 0, 'pra_hipertensi' => 0, 'hipertensi' => 0];
        foreach ($stats as $s) {
            if ($s->kategori_td && isset($totalPie[$s->kategori_td])) {
                $totalPie[$s->kategori_td] = $s->total;
            }
        }

        $total = array_sum($totalPie);
        $pieChart = [
            ['label' => 'Normal', 'value' => $totalPie['normal'], 'warna' => 'hijau', 'persentase' => $total > 0 ? round($totalPie['normal'] / $total * 100, 1) : 0],
            ['label' => 'Pra-Hipertensi', 'value' => $totalPie['pra_hipertensi'], 'warna' => 'kuning', 'persentase' => $total > 0 ? round($totalPie['pra_hipertensi'] / $total * 100, 1) : 0],
            ['label' => 'Hipertensi', 'value' => $totalPie['hipertensi'], 'warna' => 'merah', 'persentase' => $total > 0 ? round($totalPie['hipertensi'] / $total * 100, 1) : 0],
        ];

        // For bar chart, maybe show same data but as separate bars
        $barChart = [
            ['label' => 'Status Terbaru', 'normal' => $totalPie['normal'], 'pra_hipertensi' => $totalPie['pra_hipertensi'], 'hipertensi' => $totalPie['hipertensi']]
        ];

        return response()->json([
            'success' => true,
            'data' => ['bar_chart' => $barChart, 'pie_chart' => $pieChart, 'total_data' => $total],
        ]);
    }

    /**
     * GET /api/dashboard/gad
     * Grafik bar+pie GAD7 per minggu: Normal, Ringan, Sedang-Tinggi
     */
    public function grafikGAD(Request $request)
    {
        $user = $request->user();
        
        $query = DB::table('status_kesehatan');

        if ($user->role === 'kader') {
            $wargaIds = Warga::where('kader_id', $user->id)->pluck('warga_id');
            $query->whereIn('warga_id', $wargaIds);
        }

        $stats = $query->select('kategori_gad', DB::raw('count(*) as total'))
            ->groupBy('kategori_gad')
            ->get();

        $totalPie = ['normal' => 0, 'ringan' => 0, 'sedang_tinggi' => 0];
        foreach ($stats as $s) {
            if ($s->kategori_gad) {
                $cat = $s->kategori_gad;
                if ($cat === 'normal') $totalPie['normal'] = $s->total;
                else if ($cat === 'ringan') $totalPie['ringan'] = $s->total;
                else if ($cat === 'sedang' || $cat === 'tinggi') $totalPie['sedang_tinggi'] += $s->total;
            }
        }

        $total = array_sum($totalPie);
        $pieChart = [
            ['label' => 'Normal', 'value' => $totalPie['normal'], 'warna' => 'hijau', 'persentase' => $total > 0 ? round($totalPie['normal'] / $total * 100, 1) : 0],
            ['label' => 'Ringan', 'value' => $totalPie['ringan'], 'warna' => 'kuning', 'persentase' => $total > 0 ? round($totalPie['ringan'] / $total * 100, 1) : 0],
            ['label' => 'Sedang-Tinggi', 'value' => $totalPie['sedang_tinggi'], 'warna' => 'merah', 'persentase' => $total > 0 ? round($totalPie['sedang_tinggi'] / $total * 100, 1) : 0],
        ];

        $barChart = [
            ['label' => 'Status Terbaru', 'normal' => $totalPie['normal'], 'ringan' => $totalPie['ringan'], 'sedang_tinggi' => $totalPie['sedang_tinggi']]
        ];

        return response()->json([
            'success' => true,
            'data' => ['bar_chart' => $barChart, 'pie_chart' => $pieChart, 'total_data' => $total],
        ]);
    }

    /**
     * GET /api/dashboard/summary
     * Ringkasan dashboard (jumlah warga, kader, dll)
     */
    public function summary(Request $request)
    {
        $user = $request->user();

        $data = [
            'total_warga' => User::where('role', 'warga')->count(),
            'total_kader' => User::where('role', 'kader')->count(),
            'total_td_hari_ini' => TekananDarah::whereDate('tgl_cek', Carbon::today())->count(),
            'total_gad_hari_ini' => GAD::whereDate('tgl_gad', Carbon::today())->count(),
        ];

        if ($user->role === 'kader') {
            $wargaIds = Warga::where('kader_id', $user->id)->pluck('warga_id');
            $data['total_warga'] = $wargaIds->count();
            $data['total_td_hari_ini'] = TekananDarah::whereIn('warga_id', $wargaIds)->whereDate('tgl_cek', Carbon::today())->count();
            $data['total_gad_hari_ini'] = GAD::whereIn('warga_id', $wargaIds)->whereDate('tgl_gad', Carbon::today())->count();
        }

        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * GET /api/dashboard/kader
     * Dashboard khusus kader: ringkasan warga saya, peringatan hipertensi, warga terbaru
     */
    public function kaderDashboard(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'kader' && $user->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $kaderId = $request->get('kader_id', $user->id);
        $wargaIds = Warga::where('kader_id', $kaderId)->pluck('warga_id');

        $data = [
            'warga_count' => $wargaIds->count(),
            'td_today' => TekananDarah::whereIn('warga_id', $wargaIds)->whereDate('tgl_cek', Carbon::today())->count(),
            'gad_today' => GAD::whereIn('warga_id', $wargaIds)->whereDate('tgl_gad', Carbon::today())->count(),
            'new_chat' => 0, // Placeholder
            'peringatan' => [],
            'warga_terbaru' => []
        ];



        // Ambil warga terbaru dengan status terakhir
        $wargaTerbaru = User::whereIn('id', $wargaIds)
            ->with(['lastTd', 'lastGad'])
            ->limit(5)
            ->get();

        foreach ($wargaTerbaru as $w) {
            $data['warga_terbaru'][] = [
                'nama_lengkap' => $w->nama_lengkap,
                'no_hp' => $w->no_hp,
                'last_td' => $w->lastTd,
                'last_gad' => $w->lastGad
            ];
        }

        return response()->json(['success' => true, 'data' => $data]);
    }

        /**
     * GET /api/dashboard/progres-warga
     * Perbandingan data awal vs data terakhir setiap warga
     */
    public function progresWarga(Request $request)
    {
        $user = $request->user();
        $query = User::where('role', 'warga');

        if ($user->role === 'kader') {
            $wargaIds = Warga::where('kader_id', $user->id)->pluck('warga_id');
            $query->whereIn('id', $wargaIds);
        }

        $wargas = $query->with([
            'tekananDarah' => fn($q) => $q->orderBy('tgl_cek', 'asc'),
            'gad' => fn($q) => $q->orderBy('tgl_gad', 'asc')
        ])->get();

        $result = $wargas->map(function($w) {
            $firstTd = $w->tekananDarah->first();
            $lastTd = $w->tekananDarah->last();
            
            $firstGad = $w->gad->first();
            $lastGad = $w->gad->last();

            return [
                'nama' => $w->nama_lengkap,
                'td' => [
                    'awal' => $firstTd ? "{$firstTd->systolic}/{$firstTd->diastolic}" : '-',
                    'akhir' => $lastTd ? "{$lastTd->systolic}/{$lastTd->diastolic}" : '-',
                    'status_awal' => $firstTd ? $firstTd->kategori : '-',
                    'status_akhir' => $lastTd ? $lastTd->kategori : '-',
                ],
                'gad' => [
                    'awal' => $firstGad ? $firstGad->skor : '-',
                    'akhir' => $lastGad ? $lastGad->skor : '-',
                    'status_awal' => $firstGad ? $firstGad->kategori : '-',
                    'status_akhir' => $lastGad ? $lastGad->kategori : '-',
                ]
            ];
        });

        return response()->json(['success' => true, 'data' => $result]);
    }
}
