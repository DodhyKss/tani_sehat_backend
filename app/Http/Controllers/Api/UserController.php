<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * GET /api/users
     * Admin: Daftar semua user, bisa filter by role
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('nama_lengkap')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    /**
     * POST /api/users
     * Admin: Tambah user baru (warga/kader/admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|unique:users,nik',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,kader,warga',
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_hp' => 'required|string|max:20',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('foto');
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-user', 'public');
        }

        $user = User::create($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
            'data' => $user,
        ], 201);
    }

    /**
     * GET /api/users/{id}
     * Detail user
     */
    public function show($id)
    {
        $user = User::with('wargaRelasi.kader', 'statusKesehatan')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * PUT /api/users/{id}
     * Admin: Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nik' => 'sometimes|string|unique:users,nik,' . $id,
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|in:admin,kader,warga',
            'nama_lengkap' => 'sometimes|string|max:255',
            'tanggal_lahir' => 'sometimes|date',
            'jenis_kelamin' => 'sometimes|in:Laki-laki,Perempuan',
            'no_hp' => 'sometimes|string|max:20',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['foto', 'password']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-user', 'public');
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate',
            'data' => $user->fresh(),
        ]);
    }

    /**
     * DELETE /api/users/{id}
     * Admin: Hapus user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus',
        ]);
    }

    /**
     * POST /api/users/assign-kader
     * Admin: Assign warga ke kader
     */
    public function assignKader(Request $request)
    {
        $request->validate([
            'warga_id' => 'required|exists:users,id',
            'kader_id' => 'required|exists:users,id',
        ]);

        // Validate roles
        $warga = User::findOrFail($request->warga_id);
        $kader = User::findOrFail($request->kader_id);

        if ($warga->role !== 'warga') {
            return response()->json([
                'success' => false,
                'message' => 'User yang dipilih bukan warga',
            ], 422);
        }

        if ($kader->role !== 'kader') {
            return response()->json([
                'success' => false,
                'message' => 'User yang dipilih bukan kader',
            ], 422);
        }

        $relation = Warga::updateOrCreate(
            ['warga_id' => $request->warga_id],
            ['kader_id' => $request->kader_id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Warga berhasil di-assign ke kader',
            'data' => $relation->load('warga', 'kader'),
        ]);
    }

    /**
     * GET /api/users/kader/{kaderId}/warga
     * Daftar warga yang ditangani oleh kader tertentu
     */
    public function wargaByKader(Request $request, $kaderId)
    {
        $user = $request->user();

        // Jika user adalah kader, pastikan dia hanya mengakses datanya sendiri
        if ($user->role === 'kader' && $user->id != $kaderId) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke data warga kader lain',
            ], 403);
        }

        $wargas = User::whereHas('wargaRelasi', function($q) use ($kaderId) {
            $q->where('kader_id', $kaderId);
        })->get(['id', 'nama_lengkap', 'no_hp', 'foto']);

        return response()->json([
            'success' => true,
            'data' => $wargas,
        ]);
    }
    
    /**
     * GET /api/users/warga-kader
     * Admin: Daftar semua warga dengan info kader
     */
    public function wargaKaderList()
    {
        $wargas = User::where('users.role', 'warga')
            ->leftJoin('warga', 'users.id', '=', 'warga.warga_id')
            ->leftJoin('users as kader', 'warga.kader_id', '=', 'kader.id')
            ->select([
                'users.id',
                'users.nama_lengkap',
                'users.nik',
                'users.no_hp',
                'warga.kader_id',
                'kader.nama_lengkap as kader_nama'
            ])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $wargas,
        ]);
    }
    
    /**
     * GET /api/users/kaders
     * Admin: Daftar semua kader
     */
    public function kadersList()
    {
        $kaders = User::where('role', 'kader')->get(['id', 'nama_lengkap', 'no_hp', 'foto']);

        return response()->json([
            'success' => true,
            'data' => $kaders,
        ]);
    }
    

    public function adminsList()
    {
        $admins = User::where('role', 'admin')->get(['id', 'nama_lengkap', 'no_hp', 'foto']);

        return response()->json([
            'success' => true,
            'data' => $admins,
        ]);
    }

    public function removeKader($wargaId)
    {
        $relation = Warga::where('warga_id', $wargaId)->first();
        
        if (!$relation) {
            return response()->json([
                'success' => false,
                'message' => 'Data relasi tidak ditemukan',
            ], 404);
        }
        
        $relation->kader_id = null;
        $relation->save();

        return response()->json([
            'success' => true,
            'message' => 'Warga berhasil dihapus dari kader',
        ]);
    }

    public function myKader(Request $request)
    {
        $user = $request->user();
        
        $wargaRelation = Warga::with('kader')->where('warga_id', $user->id)->first();
        
        return response()->json([
            'success' => true,
            'data' => $wargaRelation ? $wargaRelation->kader : null,
        ]);
    }
}
