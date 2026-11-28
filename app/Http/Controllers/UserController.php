<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Borrowing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Read - Tampilkan akun admin & petugas saja (member dikelola di Kelola Member)
    public function index() {
        $users = User::whereIn('role', ['admin', 'petugas'])->get();

        return view('users.index', compact('users'));
    }

    // Tampilkan form tambah
    public function create() {
        return view('users.create');
    }

    // Store - Simpan pengguna baru
    public function store(Request $request) {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,petugas',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // Tampilkan form edit
    public function edit($id) {
        $user = User::whereIn('role', ['admin', 'petugas'])->findOrFail($id);

        return view('users.edit', compact('user'));
    }

    // Update - Perbarui data pengguna
    public function update(Request $request, $id) {
        $user = User::whereIn('role', ['admin', 'petugas'])->findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|in:admin,petugas',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role  = $request->role;

        // Hanya update password jika diisi
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    // Delete - Hapus pengguna
    public function destroy($id) {
        $user = User::whereIn('role', ['admin', 'petugas'])->findOrFail($id);

        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan untuk login.');
        }

        DB::transaction(function () use ($user) {
            // Lepaskan referensi petugas pada riwayat peminjaman agar tidak terkena constraint FK
            Borrowing::where('user_id', $user->id)->update(['user_id' => null]);
            $user->delete();
        });

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
