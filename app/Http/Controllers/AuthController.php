<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Member;

class AuthController extends Controller
{
    /**
     * Menampilkan form login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses login & membedakan arah redirect berdasarkan Role
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // JIKA MEMBER: Arahkan ke Halaman Beranda / Katalog Member
            if ($user->role === 'member') {
                return redirect()->route('member.books')->with('success', 'Selamat datang, ' . $user->name . '!');
            }

            // JIKA ADMIN / PETUGAS: Arahkan ke Dashboard Pengelola
            return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withErrors([
            'loginError' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Menampilkan form registrasi khusus member
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Memproses registrasi member dengan kode otomatis & validasi password ketat
     */
    public function register(Request $request)
    {
        // 1. Validasi input (Password: min 8, huruf besar & kecil, angka, simbol)
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'address'  => ['nullable', 'string', 'max:1000'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()   // wajib ada huruf besar & kecil
                    ->letters()     // wajib ada huruf
                    ->numbers()     // wajib ada angka
                    ->symbols(),    // wajib ada simbol (@$!%*#?&)
            ],
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // 2. Pembuatan Kode Anggota Otomatis (Format: MBR-YYYYMM-001)
        $prefix = 'MBR-' . date('Ym') . '-';
        $lastMember = Member::where('member_code', 'like', $prefix . '%')
                            ->orderBy('member_code', 'desc')
                            ->first();

        if ($lastMember) {
            $lastNumber = (int) substr($lastMember->member_code, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }
        $memberCode = $prefix . $newNumber;

        // 3. Simpan data akun User (Role: member)
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'member',
        ]);

        // 4. Simpan data profil Member perpustakaan
        Member::create([
            'user_id'     => $user->id,
            'member_code' => $memberCode,
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'address'     => $request->address,
            'joined_at'   => now()->toDateString(),
        ]);

        // 5. Otomatis login dan arahkan langsung ke katalog member
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('member.books')->with('success', "Pendaftaran berhasil! Kode Anggota Anda: {$memberCode}");
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
