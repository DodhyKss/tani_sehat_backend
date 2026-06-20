<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalPengisian;
use App\Models\Kuesioner;
use App\Models\Materi;
use App\Models\Video;
use App\Models\Gambar;
use App\Models\RekomendasiOlahraga;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ===== JADWAL PENGISIAN =====
    public function getJadwal()
    {
        return response()->json(['success' => true, 'data' => JadwalPengisian::all()]);
    }

    public function updateJadwal(Request $request)
    {
        $request->validate([
            'jenis_pengisian' => 'required|in:td,gad7',
            'tipe' => 'required|in:hours,day,week,month,year',
            'jumlah' => 'required|integer|min:1',
        ]);

        $jadwal = JadwalPengisian::updateOrCreate(
            ['jenis_pengisian' => $request->jenis_pengisian],
            ['tipe' => $request->tipe, 'jumlah' => $request->jumlah]
        );

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil diupdate', 'data' => $jadwal]);
    }

    // ===== KUESIONER GAD7 =====
    public function indexKuesioner()
    {
        return response()->json(['success' => true, 'data' => Kuesioner::all()]);
    }

    public function storeKuesioner(Request $request)
    {
        $request->validate(['soal' => 'required|string']);
        $k = Kuesioner::create(['soal' => $request->soal]);
        return response()->json(['success' => true, 'data' => $k], 201);
    }

    public function updateKuesioner(Request $request, $id)
    {
        $k = Kuesioner::findOrFail($id);
        $request->validate(['soal' => 'required|string']);
        $k->update(['soal' => $request->soal]);
        return response()->json(['success' => true, 'data' => $k]);
    }

    public function destroyKuesioner($id)
    {
        Kuesioner::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Soal berhasil dihapus']);
    }

    // ===== MATERI =====
    public function storeMateri(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf|max:10240',
            'kategori_gad' => 'required|in:normal,ringan,sedang,tinggi,semua,tidak_salah_satunya',
            'kategori_td' => 'required|in:hipertensi,pre_hipertensi,normal,semua,tidak_salah_satunya',
        ]);
        
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/materi'), $filename);
        
        $m = Materi::create([
            'judul' => $request->judul, 
            'file_path' => 'uploads/materi/' . $filename, 
            'kategori_gad' => $request->kategori_gad, 
            'kategori_td' => $request->kategori_td
        ]);
        return response()->json(['success' => true, 'data' => $m], 201);
    }

    public function indexMateri() { return response()->json(['success' => true, 'data' => Materi::all()]); }

    public function updateMateri(Request $request, $id)
    {
        $m = Materi::findOrFail($id);
        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf|max:10240',
            'kategori_gad' => 'required|in:normal,ringan,sedang,tinggi,semua,tidak_salah_satunya',
            'kategori_td' => 'required|in:hipertensi,pre_hipertensi,normal,semua,tidak_salah_satunya',
        ]);
        
        $data = $request->only('judul', 'kategori_gad', 'kategori_td');
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/materi'), $filename);
            $data['file_path'] = 'uploads/materi/' . $filename;
        }
        
        $m->update($data);
        return response()->json(['success' => true, 'data' => $m]);
    }

    public function destroyMateri($id) { Materi::findOrFail($id)->delete(); return response()->json(['success' => true, 'message' => 'Materi dihapus']); }

    // ===== VIDEO =====
    public function storeVideo(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'link_embed' => 'required|string',
            'kategori_gad' => 'required|in:normal,ringan,sedang,tinggi,semua,tidak_salah_satunya',
            'kategori_td' => 'required|in:hipertensi,pre_hipertensi,normal,semua,tidak_salah_satunya',
        ]);
        $v = Video::create($request->only('judul', 'link_embed', 'kategori_gad', 'kategori_td'));
        return response()->json(['success' => true, 'data' => $v], 201);
    }

    public function indexVideo() { return response()->json(['success' => true, 'data' => Video::all()]); }

    public function updateVideo(Request $request, $id)
    {
        $v = Video::findOrFail($id);
        $request->validate([
            'judul' => 'required|string|max:255',
            'link_embed' => 'required|string',
            'kategori_gad' => 'required|in:normal,ringan,sedang,tinggi,semua,tidak_salah_satunya',
            'kategori_td' => 'required|in:hipertensi,pre_hipertensi,normal,semua,tidak_salah_satunya',
        ]);
        $v->update($request->only('judul', 'link_embed', 'kategori_gad', 'kategori_td'));
        return response()->json(['success' => true, 'data' => $v]);
    }

    public function destroyVideo($id) { Video::findOrFail($id)->delete(); return response()->json(['success' => true, 'message' => 'Video dihapus']); }

    // ===== GAMBAR =====
    public function storeGambar(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'required|image|max:5120',
            'kategori_gad' => 'required|in:normal,ringan,sedang,tinggi,semua,tidak_salah_satunya',
            'kategori_td' => 'required|in:hipertensi,pre_hipertensi,normal,semua,tidak_salah_satunya',
        ]);
        
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/gambar'), $filename);
        
        $g = Gambar::create([
            'judul' => $request->judul, 
            'file_path' => 'uploads/gambar/' . $filename, 
            'kategori_gad' => $request->kategori_gad, 
            'kategori_td' => $request->kategori_td
        ]);
        return response()->json(['success' => true, 'data' => $g], 201);
    }

    public function indexGambar() { return response()->json(['success' => true, 'data' => Gambar::all()]); }

    public function updateGambar(Request $request, $id)
    {
        $g = Gambar::findOrFail($id);
        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'nullable|image|max:5120',
            'kategori_gad' => 'required|in:normal,ringan,sedang,tinggi,semua,tidak_salah_satunya',
            'kategori_td' => 'required|in:hipertensi,pre_hipertensi,normal,semua,tidak_salah_satunya',
        ]);
        
        $data = $request->only('judul', 'kategori_gad', 'kategori_td');
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/gambar'), $filename);
            $data['file_path'] = 'uploads/gambar/' . $filename;
        }
        
        $g->update($data);
        return response()->json(['success' => true, 'data' => $g]);
    }

    public function destroyGambar($id) { Gambar::findOrFail($id)->delete(); return response()->json(['success' => true, 'message' => 'Gambar dihapus']); }

    // ===== REKOMENDASI OLAHRAGA =====
    public function storeOlahraga(Request $request)
    {
        $request->validate([
            'nama_olahraga' => 'required|string|max:255',
            'kategori_gad' => 'required|in:normal,ringan,sedang,tinggi,semua,tidak_salah_satunya',
            'kategori_td' => 'required|in:hipertensi,pre_hipertensi,normal,semua,tidak_salah_satunya',
        ]);
        $o = RekomendasiOlahraga::create($request->only('nama_olahraga', 'kategori_gad', 'kategori_td'));
        return response()->json(['success' => true, 'data' => $o], 201);
    }

    public function indexOlahraga() { return response()->json(['success' => true, 'data' => RekomendasiOlahraga::all()]); }

    public function updateOlahraga(Request $request, $id)
    {
        $o = RekomendasiOlahraga::findOrFail($id);
        $request->validate([
            'nama_olahraga' => 'required|string|max:255',
            'kategori_gad' => 'required|in:normal,ringan,sedang,tinggi,semua,tidak_salah_satunya',
            'kategori_td' => 'required|in:hipertensi,pre_hipertensi,normal,semua,tidak_salah_satunya',
        ]);
        $o->update($request->only('nama_olahraga', 'kategori_gad', 'kategori_td'));
        return response()->json(['success' => true, 'data' => $o]);
    }

    public function destroyOlahraga($id) { RekomendasiOlahraga::findOrFail($id)->delete(); return response()->json(['success' => true, 'message' => 'Olahraga dihapus']); }

    // ===== REKOMENDASI =====
    public function getRekomendasi(Request $request)
    {
        $kategoriGad = $request->get('kategori_gad', 'normal');
        $kategoriTd = $request->get('kategori_td', 'normal');

        return response()->json([
            'success' => true,
            'data' => [
                'videos' => Video::whereIn('kategori_gad', [$kategoriGad, 'semua'])->orWhereIn('kategori_td', [$kategoriTd, 'semua'])->get(),
                'materis' => Materi::whereIn('kategori_gad', [$kategoriGad, 'semua'])->orWhereIn('kategori_td', [$kategoriTd, 'semua'])->get(),
                'gambars' => Gambar::whereIn('kategori_gad', [$kategoriGad, 'semua'])->orWhereIn('kategori_td', [$kategoriTd, 'semua'])->get(),
                'olahragas' => RekomendasiOlahraga::whereIn('kategori_gad', [$kategoriGad, 'semua'])->orWhereIn('kategori_td', [$kategoriTd, 'semua'])->get(),
            ]
        ]);
    }
}
