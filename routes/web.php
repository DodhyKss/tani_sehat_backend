<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () { return view('auth.login'); })->name('login');

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
Route::get('/users', function () { return view('admin.users'); })->name('users.index');
Route::get('/admin/kesehatan', function () { return view('admin.kesehatan'); })->name('admin.kesehatan');
Route::get('/admin/jadwal', function () { return view('admin.jadwal'); })->name('admin.jadwal');
Route::get('/admin/warga-kader', function () { return view('admin.warga-kader'); })->name('admin.warga-kader');
Route::get('/admin/rekomendasi', function () { return view('admin.rekomendasi'); })->name('admin.rekomendasi');
Route::get('/admin/reproduksi', function () { return view('admin.reproduksi'); })->name('admin.reproduksi');
Route::get('/admin/kuesioner', function () { return view('admin.kuesioner'); })->name('admin.kuesioner');
Route::get('/admin/master-tindak-lanjut', function () { return view('admin.master-tindak-lanjut'); })->name('admin.master-tindak-lanjut');
Route::get('/admin/frekuensi', function () { return view('admin.frekuensi'); })->name('admin.frekuensi');

Route::get('/warga', function () { return view('warga.home'); })->name('warga.home');
Route::get('/warga/input-td', function () { return view('warga.input-td'); })->name('warga.input-td');
Route::get('/warga/input-gad', function () { return view('warga.input-gad'); })->name('warga.input-gad');
Route::get('/warga/rekomendasi', function () { return view('warga.rekomendasi'); })->name('warga.rekomendasi');
Route::get('/warga/reproduksi', function () { return view('warga.reproduksi'); })->name('warga.reproduksi');

Route::get('/kader', function () { return view('kader.dashboard'); })->name('kader.dashboard');
Route::get('/kader/warga', function () { return view('kader.warga'); })->name('kader.warga');
Route::get('/kader/kesehatan', function () { return view('kader.kesehatan'); })->name('kader.kesehatan');
Route::get('/kader/rekomendasi', function () { return view('kader.rekomendasi'); })->name('kader.rekomendasi');
Route::get('/kader/reproduksi', function () { return view('kader.reproduksi'); })->name('kader.reproduksi');
Route::get('/kader/frekuensi', function () { return view('kader.frekuensi'); })->name('kader.frekuensi');

Route::get('/chat', function () { return view('chat.index'); })->name('chat.index');