<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * POST /api/login
     * Login menggunakan NIK dan password, return token Sanctum
     */
    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('nik', $request->nik)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'nik' => ['NIK atau password salah.'],
            ]);
        }

        // Hapus token lama (opsional, single device)
        $user->tokens()->delete();

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    /**
     * POST /api/logout
     * Hapus token saat ini
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    /**
     * GET /api/me
     * Ambil data user yang sedang login beserta info kader/warga
     */
    public function me(Request $request)
    {
        $user = $request->user();
        $user->load('wargaRelasi.kader', 'statusKesehatan');

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }
}
