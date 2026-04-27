<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // MANAJEMEN LAPORAN TERPISAH
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/history', [ReportController::class, 'history'])->name('reports.history');

    // BARU UNTUK DOWNLOAD PDF
    Route::get('/reports/{id}/pdf', [ReportController::class, 'downloadPdf'])->name('reports.pdf');

    // MASTER DATA KAPAL
    Route::get('/master/vessels', [App\Http\Controllers\MasterVesselController::class, 'index'])->name('master.vessels.index');
    Route::post('/master/vessels', [App\Http\Controllers\MasterVesselController::class, 'store'])->name('master.vessels.store');
    Route::put('/master/vessels/{id}', [App\Http\Controllers\MasterVesselController::class, 'update'])->name('master.vessels.update');
    Route::delete('/master/vessels/{id}', [App\Http\Controllers\MasterVesselController::class, 'destroy'])->name('master.vessels.destroy');

    // RUTE LAPORAN KINERJA IT (PERSONAL)
    Route::get('/personal-reports', [App\Http\Controllers\PersonalReportController::class, 'index'])->name('personal.reports.index');
    Route::post('/personal-reports', [App\Http\Controllers\PersonalReportController::class, 'store'])->name('personal.reports.store');
});
