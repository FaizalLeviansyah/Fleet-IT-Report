<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Mengarahkan halaman utama web langsung ke Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
