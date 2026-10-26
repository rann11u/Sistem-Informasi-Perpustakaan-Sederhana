<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowingController;

// Redirect root ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Buku
Route::resource('books', BookController::class);

// Kategori
Route::resource('categories', CategoryController::class);

// Anggota
Route::resource('members', MemberController::class);

// Peminjaman
Route::resource('borrowings', BorrowingController::class);

// Pengguna
Route::resource('users', UserController::class);
