<?php

use App\Http\Controllers\KelasController;
use App\Http\Controllers\MatkulController;
use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Route;

// Rute untuk halaman utama (opsional)
Route::get('/', function () {
    return view('tampilan_dashboard');
});

// Rute resource untuk entitas kelas, matkul, dan mahasiswa
Route::resource('kelas', KelasController::class);
Route::resource('matkul', MatkulController::class);
Route::resource('mahasiswa', MahasiswaController::class);