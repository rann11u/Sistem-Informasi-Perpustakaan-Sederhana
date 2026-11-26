<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\AuthController;

// 1. RUTE GUEST (Hanya untuk yang BELUM login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// 2. RUTE BERSAMA (Bisa diakses Admin & Petugas yang sudah login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Buku, Anggota, dan Peminjaman: Full CRUD untuk Admin & Petugas
    Route::resource('books', BookController::class);

    // Cek duplikasi data anggota (AJAX real-time)
    Route::get('/members/check-duplicate', [MemberController::class, 'checkDuplicate'])->name('members.check-duplicate');
    Route::resource('members', MemberController::class);
    Route::resource('borrowings', BorrowingController::class);

    // Kategori: Petugas & Admin sama-sama BISA MELIHAT di rute ini
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

    // ==========================================
    // 3. KHUSUS ADMIN (Hanya role:admin)
    // ==========================================
    Route::middleware('role:admin')->group(function () {
        // Mengelola Kategori (Tambah, Simpan, Edit, Update, Hapus)
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Mengelola Pengguna (User Management)
        Route::resource('users', UserController::class);
    });
});
