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
        $weeks = $request->get('weeks', 4);
        $startDate = Carbon::now()->subWeeks($weeks)->startOfWeek();

        $query = TekananDarah::where('tgl_cek', '>=', $startDate);

        // Kader: hanya lihat warga-nya
        if ($user->role === 'kader') {
            $wargaIds = Warga::where('kader_id', $user->id)->pluck('warga_id');
            $query->whereIn('warga_id', $wargaIds);
        }

        $records = $query->orderBy('tgl_cek')->get();

        // Group by week
        $weeklyData = [];
        $totalPie = ['normal' => 0, 'pra_hipertensi' => 0, 'hipertensi' => 0];

        foreach ($records as $rec) {
            $weekLabel = 'Minggu ' . Carbon::parse($rec->tgl_cek)->weekOfYear;
            if (!isset($weeklyData[$weekLabel])) {
                $weeklyData[$weekLabel] = ['normal' => 0, 'pra_hipertensi' => 0, 'hipertensi' => 0];
            }
            $kategori = $rec->kategori;
            $weeklyData[$weekLabel][$kategori]++;
            $totalPie[$kategori]++;
        }

        // Format bar chart
        $barChart = [];
        foreach ($weeklyData as $label => $counts) {
            $barChart[] = ['minggu' => $label, 'normal' => $counts['normal'], 'pra_hipertensi' => $counts['pra_hipertensi'], 'hipertensi' => $counts['hipertensi']];
        }

        $total = array_sum($totalPie);
        $pieChart = [
            ['label' => 'Normal', 'value' => $totalPie['normal'], 'warna' => 'hijau', 'persentase' => $total > 0 ? round($totalPie['normal'] / $total * 100, 1) : 0],
            ['label' => 'Pra-Hipertensi', 'value' => $totalPie['pra_hipertensi'], 'warna' => 'kuning', 'persentase' => $total > 0 ? round($totalPie['pra_hipertensi'] / $total * 100, 1) : 0],
            ['label' => 'Hipertensi', 'value' => $totalPie['hipertensi'], 'warna' => 'merah', 'persentase' => $total > 0 ? round($totalPie['hipertensi'] / $total * 100, 1) : 0],
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
        $weeks = $request->get('weeks', 4);
        $startDate = Carbon::now()->subWeeks($weeks)->startOfWeek();

        $query = GAD::where('tgl_gad', '>=', $startDate);

        if ($user->role === 'kader') {
            $wargaIds = Warga::where('kader_id', $user->id)->pluck('warga_id');
            $query->whereIn('warga_id', $wargaIds);
        }

        $records = $query->orderBy('tgl_gad')->get();

        $weeklyData = [];
        $totalPie = ['normal' => 0, 'ringan' => 0, 'sedang_tinggi' => 0];

        foreach ($records as $rec) {
            $weekLabel = 'Minggu ' . Carbon::parse($rec->tgl_gad)->weekOfYear;
            if (!isset($weeklyData[$weekLabel])) {
                $weeklyData[$weekLabel] = ['normal' => 0, 'ringan' => 0, 'sedang_tinggi' => 0];
            }
            $kategori = $rec->kategori;
            $weeklyData[$weekLabel][$kategori]++;
            $totalPie[$kategori]++;
        }

        $barChart = [];
        foreach ($weeklyData as $label => $counts) {
            $barChart[] = ['minggu' => $label, 'normal' => $counts['normal'], 'ringan' => $counts['ringan'], 'sedang_tinggi' => $counts['sedang_tinggi']];
        }

        $total = array_sum($totalPie);
        $pieChart = [
            ['label' => 'Normal', 'value' => $totalPie['normal'], 'warna' => 'hijau', 'persentase' => $total > 0 ? round($totalPie['normal'] / $total * 100, 1) : 0],
            ['label' => 'Ringan', 'value' => $totalPie['ringan'], 'warna' => 'kuning', 'persentase' => $total > 0 ? round($totalPie['ringan'] / $total * 100, 1) : 0],
            ['label' => 'Sedang-Tinggi', 'value' => $totalPie['sedang_tinggi'], 'warna' => 'merah', 'persentase' => $total > 0 ? round($totalPie['sedang_tinggi'] / $total * 100, 1) : 0],
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
}
