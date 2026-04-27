<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reproduksi;
use App\Models\Warga;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReproduksiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Reproduksi::with('user')->orderBy('tgl_input', 'desc');

        if ($user->role === 'warga') {
            $query->where('warga_id', $user->id);
        } elseif ($user->role === 'kader') {
            $assignedIds = Warga::where('kader_id', $user->id)->pluck('warga_id');
            $query->whereIn('warga_id', $assignedIds);
        }

        $data = $query->paginate($request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'keterangan' => 'required|string',
            'tgl_menstruasi' => 'required|date',
        ]);

        $user = $request->user();

        $reproduksi = Reproduksi::create([
            'warga_id' => $user->id,
            'keterangan' => $request->keterangan,
            'tgl_menstruasi' => $request->tgl_menstruasi,
            'tgl_input' => Carbon::today(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data reproduksi berhasil disimpan',
            'data' => $reproduksi
        ], 201);
    }

    public function destroy(Request $request, $id)
    {
        $reproduksi = Reproduksi::findOrFail($id);
        $user = $request->user();

        if ($user->role === 'warga' && $reproduksi->warga_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $reproduksi->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
