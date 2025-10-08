<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\absensiController;

Route::get('/home', [absensiController::class, 'home'])->name('home');

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/absensi', function () {
    return view('pages.absensi');
});
