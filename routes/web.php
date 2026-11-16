<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PijarDashboardController; // <-- 1. Tambahkan ini di baris ke-4
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuarDashboardController;   

Route::get('/', fn() => redirect('/login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Penangkap redirect default Laravel
    Route::get('/dashboard', function () {
        return match (Auth::user()->role) {
            'kaprodi'     => redirect()->route('kaprodi.dashboard'),
            'admin_suar'  => redirect()->route('suar.dashboard'),
            'admin_pijar' => redirect()->route('pijar.dashboard'),
            'admin_kerma' => redirect()->route('kerma.dashboard'),
            default       => 'Dashboard Umum',
        };
    })->name('dashboard');

    Route::get('/home', fn() => redirect()->route('dashboard'));

    // Placeholder Dashboard sementara untuk role selain PIJAR
    $dashboardView = function ($title) {
        return '
            <div style="font-family: sans-serif; padding: 40px;">
                <h1>' . $title . '</h1>
                <p>Login sebagai: <strong>' . Auth::user()->name . '</strong> (' . Auth::user()->email . ')</p>
                <form action="' . route('logout') . '" method="POST">
                    ' . csrf_field() . '
                    <button type="submit" style="padding: 10px 20px; background: #F57C00; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
                        Logout / Ganti Akun
                    </button>
                </form>
            </div>
        ';
    };

    Route::get('/kaprodi/dashboard', fn() => $dashboardView('Dashboard Kaprodi'))->name('kaprodi.dashboard');
    Route::get('/suar/dashboard', fn() => $dashboardView('Dashboard Admin SUAR'))->name('suar.dashboard');
    Route::get('/pijar/dashboard', [PijarDashboardController::class, 'index'])->name('pijar.dashboard');
    Route::get('/kerma/dashboard', fn() => $dashboardView('Dashboard Admin Kerma'))->name('kerma.dashboard');
    Route::get('/pijar/dashboard', [PijarDashboardController::class, 'index'])->name('pijar.dashboard');
    Route::post('/pijar/schedules', [PijarDashboardController::class, 'store'])->name('pijar.schedules.store');
    Route::put('/pijar/schedules/{schedule}', [PijarDashboardController::class, 'update'])->name('pijar.schedules.update');
    Route::get('/pijar/dashboard', [PijarDashboardController::class, 'index'])->name('pijar.dashboard');
    Route::get('/pijar/manajemen-jadwal', [PijarDashboardController::class, 'manajemenJadwal'])->name('pijar.manajemen-jadwal');
    Route::post('/pijar/schedules', [PijarDashboardController::class, 'store'])->name('pijar.schedules.store');
    Route::put('/pijar/schedules/{schedule}', [PijarDashboardController::class, 'update'])->name('pijar.schedules.update');
    Route::get('/pijar/jadwal-eksternal', [PijarDashboardController::class, 'jadwalEksternal'])->name('pijar.jadwal-eksternal');
    Route::patch('/pijar/jadwal-eksternal/{schedule}/status', [PijarDashboardController::class, 'updateStatusEksternal'])->name('pijar.jadwal-eksternal.status');
    Route::get('/pijar/manajemen-akun', [PijarDashboardController::class, 'manajemenAkun'])->name('pijar.manajemen-akun');
    Route::post('/pijar/manajemen-akun', [PijarDashboardController::class, 'storeUser'])->name('pijar.users.store');
    Route::put('/pijar/manajemen-akun/{user}', [PijarDashboardController::class, 'updateUser'])->name('pijar.users.update');
    Route::get('/pijar/rekap-pengajaran', [PijarDashboardController::class, 'rekapPengajaran'])->name('pijar.rekap-pengajaran');
    Route::get('/pijar/rekap-pengajaran/export', [PijarDashboardController::class, 'exportRekapExcel'])->name('pijar.rekap-pengajaran.export');
    // Portal Admin SUAR
        Route::get('/suar/dashboard', [SuarDashboardController::class, 'index'])->name('suar.dashboard');
        Route::put('/suar/schedules/{schedule}/assign', [SuarDashboardController::class, 'assignPractitioner'])->name('suar.schedules.assign');

        Route::get('/suar/praktisi', [SuarDashboardController::class, 'praktisi'])->name('suar.praktisi');
        Route::post('/suar/praktisi', [SuarDashboardController::class, 'storePractitioner'])->name('suar.praktisi.store');
        Route::put('/suar/praktisi/{practitioner}', [SuarDashboardController::class, 'updatePractitioner'])->name('suar.praktisi.update');
        Route::delete('/suar/praktisi/{practitioner}', [SuarDashboardController::class, 'destroyPractitioner'])->name('suar.praktisi.destroy');
    });