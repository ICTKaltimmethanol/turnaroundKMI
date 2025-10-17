<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PageController;

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');


Route::middleware(['auth:employee'])->group(function () {
    Route::get('/home', [PageController::class, 'home'])->name('home');
    Route::get('/absensi-list', [PageController::class, 'absent'])->name('absensi');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/cuti', [PageController::class, 'cuti'])->name('cuti');
    Route::post('/cuti', [PageController::class, 'cutiStore'])->name('cuti.store');
    Route::get('/info', [PageController::class, 'info'])->name('info');
    Route::get('/profile', [PageController::class, 'profile'])->name('profile');
    Route::post('/profile', [PageController::class, 'profileUpdate'])->name('profile.update');
});

Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');

Route::post('/absensi/scan', [AbsensiController::class, 'scan'])->name('absensi.scan');
