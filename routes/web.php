<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PijarDashboardController;
use App\Http\Controllers\PortalPenggunaController;
use App\Http\Controllers\SuarDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Wajib Ganti Kata Sandi Awal bagi Akun Baru Dosen & Praktisi
    Route::get('/ganti-sandi-awal', [PortalPenggunaController::class, 'showChangePassword'])->name('password.force');
    Route::post('/ganti-sandi-awal', [PortalPenggunaController::class, 'updateChangePassword'])->name('password.force.update');

    // Penangkap redirect default Laravel sesuai Role
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->must_change_password) {
            return redirect()->route('password.force');
        }

        return match ($user->role) {
            'admin', 'admin_pijar', 'admin_suar' => redirect()->route('pijar.dashboard'),
            'kaprodi'                            => redirect()->route('kaprodi.dashboard'),
            'dosen', 'praktisi'                  => redirect()->route('dosen.dashboard'),
            'admin_kerma'                        => redirect()->route('pijar.jadwal-eksternal'),
            default                              => redirect()->route('dosen.dashboard'),
        };
    })->name('dashboard');

    Route::get('/home', fn() => redirect()->route('dashboard'));

    // Portal Kaprodi (Pengajuan Praktisi & Pantau Kredensial Akun ACC)
    Route::get('/kaprodi/dashboard', [PortalPenggunaController::class, 'kaprodiDashboard'])->name('kaprodi.dashboard');
    Route::post('/kaprodi/pengajuan', [PortalPenggunaController::class, 'kaprodiStorePengajuan'])->name('kaprodi.pengajuan.store');

    // Portal Dosen & Praktisi (Unggah RPS, Silabus, Modul 1-13 & Laporan G-Drive Foto)
    Route::get('/dosen/dashboard', [PortalPenggunaController::class, 'dosenDashboard'])->name('dosen.dashboard');
    Route::post('/dosen/schedules/{schedule}/perangkat', [PortalPenggunaController::class, 'dosenUploadPerangkat'])->name('dosen.upload.perangkat');
    Route::post('/dosen/schedules/{schedule}/laporan', [PortalPenggunaController::class, 'dosenSubmitLaporan'])->name('dosen.submit.laporan');

    // Portal Admin Terpadu — Modul PIJAR
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
    Route::post('/pijar/master/room', [PijarDashboardController::class, 'storeRoom'])->name('pijar.master.room');
    Route::post('/pijar/master/course', [PijarDashboardController::class, 'storeCourse'])->name('pijar.master.course');

    // Fitur Unduh Template & Impor Excel/CSV (Dosen, Peran, & Mata Kuliah)
    Route::get('/pijar/import/template-users', [PijarDashboardController::class, 'downloadTemplateUsers'])->name('pijar.import.template-users');
    Route::post('/pijar/import/users', [PijarDashboardController::class, 'importUsersCsv'])->name('pijar.import.users');
    Route::get('/pijar/import/template-courses', [PijarDashboardController::class, 'downloadTemplateCourses'])->name('pijar.import.template-courses');
    Route::post('/pijar/import/courses', [PijarDashboardController::class, 'importCoursesCsv'])->name('pijar.import.courses');

    // Portal Admin Terpadu — Modul SUAR
    Route::get('/suar/dashboard', [SuarDashboardController::class, 'index'])->name('suar.dashboard');
    Route::put('/suar/schedules/{schedule}/assign', [SuarDashboardController::class, 'assignPractitioner'])->name('suar.schedules.assign');
    Route::get('/suar/praktisi', [SuarDashboardController::class, 'praktisi'])->name('suar.praktisi');
    Route::post('/suar/praktisi', [SuarDashboardController::class, 'storePractitioner'])->name('suar.praktisi.store');
    Route::put('/suar/praktisi/{practitioner}', [SuarDashboardController::class, 'updatePractitioner'])->name('suar.praktisi.update');
    Route::delete('/suar/praktisi/{practitioner}', [SuarDashboardController::class, 'destroyPractitioner'])->name('suar.praktisi.destroy');

    Route::get('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');

    Route::get('/kaprodi/pengajuan', function () {
    return redirect()->route('kaprodi.dashboard');
});
});
});