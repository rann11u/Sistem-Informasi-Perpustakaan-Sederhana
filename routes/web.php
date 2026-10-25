<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowingController;

// Route Buku (langsung bisa ditest tanpa login)
Route::resource('books', BookController::class);

Route::get('/', function () {
    return redirect()->route('books.index');
});

// Halaman login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman lainnya yang butuh auth
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kategori
    Route::resource('categories', CategoryController::class);

    // Anggota
    Route::resource('members', MemberController::class);

    // Peminjaman
    Route::resource('borrowings', BorrowingController::class);

    // Pengguna
    Route::resource('users', UserController::class);
});
