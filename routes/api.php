<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TekananDarahController;
use App\Http\Controllers\Api\GADController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\StatusKesehatanController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ReproduksiController;

// Public Routes
Route::post('/login', [AuthController::class, 'login']);
Route::get('/file', function (\Illuminate\Http\Request $request) {
    $path = $request->query('path');
    if (!$path) return response()->json(['error' => 'Path required'], 400);
    $fullPath = public_path($path);
    if (file_exists($fullPath)) {
        return response()->file($fullPath, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => '*',
            'Access-Control-Expose-Headers' => '*',
        ]);
    }
    return response()->json(['error' => 'File not found'], 404);
});

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('users/my-kader', [UserController::class, 'myKader']);

// --- Admin Routes ---
    Route::middleware('role:admin')->group(function () {
        // User Management
        Route::get('users/warga-kader', [UserController::class, 'wargaKaderList']);
        Route::delete('users/remove-kader/{wargaId}', [UserController::class, 'removeKader']);
        Route::post('users/assign-kader', [UserController::class, 'assignKader']);

        Route::apiResource('users', UserController::class);

        // Jadwal
        Route::get('admin/jadwal', [AdminController::class, 'getJadwal']);
        Route::post('admin/jadwal', [AdminController::class, 'updateJadwal']);

        // Kuesioner GAD7
        Route::get('admin/kuesioner', [AdminController::class, 'indexKuesioner']);
        Route::post('admin/kuesioner', [AdminController::class, 'storeKuesioner']);
        Route::put('admin/kuesioner/{id}', [AdminController::class, 'updateKuesioner']);
        Route::delete('admin/kuesioner/{id}', [AdminController::class, 'destroyKuesioner']);

        // Materi
        Route::post('admin/materi', [AdminController::class, 'storeMateri']);
        Route::post('admin/materi/{id}', [AdminController::class, 'updateMateri']); // Use POST for file upload support in multipart
        Route::delete('admin/materi/{id}', [AdminController::class, 'destroyMateri']);

        // Video
        Route::post('admin/video', [AdminController::class, 'storeVideo']);
        Route::put('admin/video/{id}', [AdminController::class, 'updateVideo']);
        Route::delete('admin/video/{id}', [AdminController::class, 'destroyVideo']);

        // Gambar
        Route::post('admin/gambar', [AdminController::class, 'storeGambar']);
        Route::post('admin/gambar/{id}', [AdminController::class, 'updateGambar']); // Use POST for file upload support
        Route::delete('admin/gambar/{id}', [AdminController::class, 'destroyGambar']);

        // Rekomendasi Olahraga
        Route::post('admin/olahraga', [AdminController::class, 'storeOlahraga']);
        Route::put('admin/olahraga/{id}', [AdminController::class, 'updateOlahraga']);
        Route::delete('admin/olahraga/{id}', [AdminController::class, 'destroyOlahraga']);
    });

    // Access for Admin & Kader
    Route::middleware('role:admin,kader')->group(function () {
        Route::get('users/kader/{kaderId}/warga', [UserController::class, 'wargaByKader']);
    });

    Route::get('kaders', [UserController::class, 'kadersList']);
    Route::get('admins', [UserController::class, 'adminsList']);


    // Public content resources (viewable by all authenticated users)
    Route::get('materi', [AdminController::class, 'indexMateri']);
    Route::get('video', [AdminController::class, 'indexVideo']);
    Route::get('gambar', [AdminController::class, 'indexGambar']);
    Route::get('olahraga', [AdminController::class, 'indexOlahraga']);
    Route::get('rekomendasi', [AdminController::class, 'getRekomendasi']);

    // --- Dashboard & Grafik ---
    Route::get('dashboard/summary', [DashboardController::class, 'summary']);
    Route::get('dashboard/tekanan-darah', [DashboardController::class, 'grafikTekananDarah']);
    Route::get('dashboard/gad', [DashboardController::class, 'grafikGAD']);
    Route::get('kader/dashboard', [DashboardController::class, 'kaderDashboard']);
    Route::get('dashboard/progres-warga', [DashboardController::class, 'progresWarga']);

    // --- Status Kesehatan ---
    Route::get('status-kesehatan', [StatusKesehatanController::class, 'index']);
    Route::get('status-kesehatan/cek-jadwal', [StatusKesehatanController::class, 'cekJadwal']);
    Route::post('status-kesehatan/td', [StatusKesehatanController::class, 'updateTd']);
    Route::post('status-kesehatan/gad', [StatusKesehatanController::class, 'updateGad']);

    // --- Tekanan Darah ---
    Route::get('tekanan-darah/cek-jadwal', [TekananDarahController::class, 'cekJadwal']);
    Route::apiResource('tekanan-darah', TekananDarahController::class)->only(['index', 'store', 'show']);

    // --- GAD7 ---
    Route::get('gad/kuesioner', [GADController::class, 'kuesioner']);
    Route::get('gad/cek-jadwal', [GADController::class, 'cekJadwal']);
    Route::apiResource('gad', GADController::class)->only(['index', 'store', 'show']);

    // --- Reproduksi ---
    Route::apiResource('reproduksi', ReproduksiController::class)->only(['index', 'store', 'destroy']);

    // --- Messages / Chat ---
    Route::get('messages', [MessageController::class, 'index']);
    Route::post('messages/start', [MessageController::class, 'startConversation']);
    Route::get('messages/{id}', [MessageController::class, 'show']);
    Route::post('messages/{id}/send', [MessageController::class, 'sendMessage']);
    Route::delete('messages/{id}', [MessageController::class, 'destroy']);
    Route::delete('messages/detail/{id}', [MessageController::class, 'destroyDetail']);
});
