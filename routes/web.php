<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () { return view('auth.login'); })->name('login');

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
Route::get('/users', function () { return view('admin.users'); })->name('users.index');
Route::get('/admin/kesehatan', function () { return view('admin.kesehatan'); })->name('admin.kesehatan');
Route::get('/admin/jadwal', function () { return view('admin.jadwal'); })->name('admin.jadwal');
Route::get('/admin/warga-kader', function () { return view('admin.warga-kader'); })->name('admin.warga-kader');

Route::get('/warga', function () { return view('warga.home'); })->name('warga.home');
Route::get('/warga/input-td', function () { return view('warga.input-td'); })->name('warga.input-td');
Route::get('/warga/input-gad', function () { return view('warga.input-gad'); })->name('warga.input-gad');
Route::get('/warga/rekomendasi', function () { return view('warga.rekomendasi'); })->name('warga.rekomendasi');

Route::get('/kader', function () { return view('kader.dashboard'); })->name('kader.dashboard');
Route::get('/kader/warga', function () { return view('kader.warga'); })->name('kader.warga');
Route::get('/kader/kesehatan', function () { return view('kader.kesehatan'); })->name('kader.kesehatan');
Route::get('/kader/rekomendasi', function () { return view('kader.rekomendasi'); })->name('kader.rekomendasi');

Route::get('/chat', function () { return view('chat.index'); })->name('chat.index');