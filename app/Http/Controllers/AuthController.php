<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * 1. Menampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * 2, 3, 4, 5. Memeriksa kredensial dan memproses login
     */
    public function login(Request $request)
    {
        // Validasi input: memastikan email & password wajib diisi
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Cek apakah kredensial cocok dengan database (Sistem memeriksa kredensial)
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Jika benar: perbarui ID sesi untuk mencegah serangan session fixation
            $request->session()->regenerate();

            // Arahkan ke dashboard dengan pesan sukses
            return redirect()->intended(route('dashboard'))
                             ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        // Jika salah: kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'loginError' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * 7. Mengeluarkan pengguna (Logout)
     */
    public function logout(Request $request)
    {
        // Logout user dari sistem
        Auth::logout();

        // Bersihkan dan batalkan sesi yang sedang aktif
        $request->session()->invalidate();

        // Buat ulang token CSRF baru demi keamanan
        $request->session()->regenerateToken();

        // Redirect ke halaman login dengan pesan sukses logout
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}