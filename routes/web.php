<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PageController;

Route::get('/home', [PageController::class, 'home'])->name('home');
Route::get('/absensi-list', [PageController::class, 'absent'])->name('absensi');

Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
});
