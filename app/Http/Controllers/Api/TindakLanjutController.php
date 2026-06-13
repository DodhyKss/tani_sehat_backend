<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TindakLanjutController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = TindakLanjut::with(['masterTindakLanjut', 'user'])->orderBy('id', 'desc');
        
        if ($user->role === 'kader') {
            $assignedIds = \App\Models\Warga::where('kader_id', $user->id)->pluck('warga_id');
            $query->whereIn('user_id', $assignedIds);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        return response()->json(['success' => true, 'data' => $query->get()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tindak_lanjut_id' => 'required|exists:master_tindak_lanjut,id',
        ]);
        
        $user = $request->user();
        if ($user->role === 'kader') {
            $isAssigned = \App\Models\Warga::where('kader_id', $user->id)->where('warga_id', $request->user_id)->exists();
            if (!$isAssigned) {
                return response()->json(['success' => false, 'message' => 'Warga bukan binaan Anda'], 403);
            }
        }

        $data = TindakLanjut::create([
            'user_id' => $request->user_id,
            'tindak_lanjut_id' => $request->tindak_lanjut_id,
            'tanggal_tindak_lanjut' => Carbon::today(),
        ]);
        
        return response()->json(['success' => true, 'data' => $data->load('masterTindakLanjut')], 201);
    }
}
