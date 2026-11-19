<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

// Khusus role dosen (non-praktisi eksternal): wajib login menggunakan email kampus @pradita.ac.id
if ($user->role === 'dosen' && !str_ends_with(strtolower($user->email), '@pradita.ac.id')) {
    // Cek apakah email ini terdaftar sebagai praktisi industri eksternal
    $isPraktisiEksternal = \App\Models\Schedule::where('praktisi_email', $user->email)->exists();

    if (!$isPraktisiEksternal) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->withErrors([
            'email' => 'Khusus Dosen wajib login menggunakan email kampus resmi (@pradita.ac.id).',
        ])->onlyInput('email');
    }
}

            return match ($user->role) {
                'admin', 'admin_pijar', 'admin_suar' => redirect()->intended('/pijar/dashboard'),
                'kaprodi'                            => redirect()->intended('/kaprodi/dashboard'),
                'dosen', 'praktisi'                  => redirect()->intended('/dosen/dashboard'),
                default                              => redirect()->intended('/dashboard'),
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}