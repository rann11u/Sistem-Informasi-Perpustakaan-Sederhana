<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberProfileController;
use App\Http\Controllers\MemberBookController;
use App\Http\Controllers\BorrowingHistoryController;
use App\Models\Book;

// ==========================================
// 1. RUTE GUEST (Hanya untuk yang BELUM login)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Registrasi Akun Member
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ==========================================
// 2. RUTE SETELAH LOGIN (Semua Role)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Halaman Khusus Member: Katalog Buku, Booking, Riwayat & Profil
    Route::get('/katalog', [MemberBookController::class, 'index'])->name('member.books');
    Route::post('/katalog/book/{book}', [MemberBookController::class, 'book'])->name('member.books.book');

    // Riwayat Peminjaman & Invoice PDF Member
    Route::get('/riwayat', [BorrowingHistoryController::class, 'index'])->name('member.history');
    Route::get('/riwayat/{id}/invoice', [BorrowingHistoryController::class, 'invoice'])->name('member.history.invoice');
    Route::post('/riwayat/{id}/extend', [BorrowingHistoryController::class, 'extend'])->name('member.history.extend');

    Route::get('/profil', [MemberProfileController::class, 'show'])->name('member.profile');
    Route::put('/profil', [MemberProfileController::class, 'update'])->name('member.profile.update');

    // Redirect root URL
    Route::get('/', function () {
        if (auth()->user()->role === 'member') {
            return redirect()->route('member.books');
        }
        return redirect()->route('dashboard');
    });

    // ==========================================
    // 3. KHUSUS ADMIN & PETUGAS (Pengelola)
    // ==========================================
    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('books', BookController::class);
        Route::resource('members', MemberController::class);
        
        // Peminjaman & Aksi Konfirmasi Booking Petugas
        Route::post('/borrowings/cancel-expired', [BorrowingController::class, 'cancelAllExpired'])->name('borrowings.cancel-expired');
        Route::post('/borrowings/{borrowing}/confirm-pickup', [BorrowingController::class, 'confirmPickup'])->name('borrowings.confirm-pickup');
        Route::post('/borrowings/{borrowing}/cancel-booking', [BorrowingController::class, 'cancelBooking'])->name('borrowings.cancel-booking');
        Route::get('/borrowings/{borrowing}/invoice', [BorrowingController::class, 'invoice'])->name('borrowings.invoice');
        Route::resource('borrowings', BorrowingController::class);

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
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
    // 4. KHUSUS ADMIN SAJA
    // ==========================================
    Route::middleware('role:admin')->group(function () {
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::resource('users', UserController::class);
    });
});
