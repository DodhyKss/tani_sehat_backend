<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FrekuensiRekomendasi;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FrekuensiRekomendasiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'jenis_rekomendasi' => 'required|in:materi,video,gambar,olahraga'
        ]);

        $user = $request->user();

        $frekuensi = FrekuensiRekomendasi::create([
            'user_id' => $user->id,
            'jenis_rekomendasi' => $request->jenis_rekomendasi,
            'tanggal_lihat' => Carbon::today(),
        ]);

        return response()->json(['success' => true, 'data' => $frekuensi], 201);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $query = FrekuensiRekomendasi::with('user');

        if ($user->role === 'kader') {
            $assignedIds = \App\Models\Warga::where('kader_id', $user->id)->pluck('warga_id');
            $query->whereIn('user_id', $assignedIds);
        }

        $query->where('jenis_rekomendasi', '!=', 'olahraga');

        if ($request->has('nama')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->nama . '%');
            });
        }

        $frekuensiData = $query->get();

        $result = [];
        foreach ($frekuensiData as $data) {
            $userId = $data->user_id;
            if (!isset($result[$userId])) {
                $result[$userId] = [
                    'id' => $data->user->id,
                    'nama_lengkap' => $data->user->nama_lengkap,
                    'video' => 0,
                    'materi' => 0,
                    'gambar' => 0,
                ];
            }
            $result[$userId][$data->jenis_rekomendasi]++;
        }

        return response()->json(['success' => true, 'data' => array_values($result)]);
    }
}
