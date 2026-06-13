<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterTindakLanjut;
use Illuminate\Http\Request;

class MasterTindakLanjutController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterTindakLanjut::query();
        if ($request->has('jenis_tindakan')) {
            $query->where('jenis_tindakan', $request->jenis_tindakan);
        }
        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        return response()->json(['success' => true, 'data' => $query->get()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tindakan' => 'required|string',
            'jenis_tindakan' => 'required|in:td,gad7',
            'kategori' => 'required|in:normal,pra_hipertensi,hipertensi,ringan,sedang,tinggi',
        ]);
        $data = MasterTindakLanjut::create($request->all());
        return response()->json(['success' => true, 'data' => $data], 201);
    }

    public function update(Request $request, $id)
    {
        $data = MasterTindakLanjut::findOrFail($id);
        $request->validate([
            'nama_tindakan' => 'required|string',
            'jenis_tindakan' => 'required|in:td,gad7',
            'kategori' => 'required|in:normal,pra_hipertensi,hipertensi,ringan,sedang,tinggi',
        ]);
        $data->update($request->all());
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function destroy($id)
    {
        MasterTindakLanjut::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
