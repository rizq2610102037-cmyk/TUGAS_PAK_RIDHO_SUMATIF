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

            // Jika dosen/praktisi baru pertama kali login menggunakan sandi awal, wajib reset kata sandi
            if ($user->must_change_password) {
                return redirect()->route('password.force_change');
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