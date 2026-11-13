<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PijarDashboardController; // <-- 1. Tambahkan ini di baris ke-4
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    
    // 2. Pastikan baris rute PIJAR (sekitar baris ke-47) menjadi seperti ini:
    Route::get('/pijar/dashboard', [PijarDashboardController::class, 'index'])->name('pijar.dashboard');
    
    Route::get('/kerma/dashboard', fn() => $dashboardView('Dashboard Admin Kerma'))->name('kerma.dashboard');
});