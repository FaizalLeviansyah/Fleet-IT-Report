<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserPortalController;
use App\Models\Laporan;

// Rute khusus Portal Pegawai (Dilindungi sistem Login)
Route::middleware(['auth'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\UserPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/ticket/create', [UserPortalController::class, 'createTicket'])->name('create-ticket');
    Route::post('/ticket/store', [UserPortalController::class, 'storeTicket'])->name('store-ticket');
    Route::get('/profile', [\App\Http\Controllers\UserPortalController::class, 'profile'])->name('profile');
    Route::get('/kb', [UserPortalController::class, 'kb'])->name('kb');
    Route::get('/support', [\App\Http\Controllers\UserPortalController::class, 'support'])->name('support');
});

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

    Route::get('/personal-reports/{id}/export', [App\Http\Controllers\PersonalReportController::class, 'exportExcel'])->name('personal.reports.export');

    // RUTE ITSM ASSET & INVENTORY
    Route::get('/assets', [App\Http\Controllers\AssetController::class, 'index'])->name('assets.index');
    // RUTE ITSM ASSET & INVENTORY
    Route::get('/assets', [App\Http\Controllers\AssetController::class, 'index'])->name('assets.index');
    Route::post('/assets', [App\Http\Controllers\AssetController::class, 'store'])->name('assets.store');
    Route::put('/assets/{asset}', [App\Http\Controllers\AssetController::class, 'update'])->name('assets.update');
    Route::delete('/assets/{asset}', [App\Http\Controllers\AssetController::class, 'destroy'])->name('assets.destroy');
    // RUTE ITSM HELPDESK & TICKETING
    Route::resource('tickets', App\Http\Controllers\TicketController::class);
    // Tambahkan rute ini untuk Create dan Store
    Route::get('/tickets/create', [App\Http\Controllers\TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [App\Http\Controllers\TicketController::class, 'store'])->name('tickets.store');

    Route::post('/tickets/{ticket}/thread', [App\Http\Controllers\TicketController::class, 'storeThread'])->name('tickets.thread.store');
    Route::patch('/tickets/{ticket}/status', [App\Http\Controllers\TicketController::class, 'updateStatus'])->name('tickets.status.update');

    Route::get('/laporan/{id}/cetak', function ($id) {
    // Cari laporan beserta relasi gambarnya
        $laporan = Laporan::with('gambars')->findOrFail($id);
        return view('cetak-laporan', compact('laporan'));
    })->name('cetak.laporan');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
