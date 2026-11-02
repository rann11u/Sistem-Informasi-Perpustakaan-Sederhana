<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;

class DashboardController extends Controller
{
    // Tampilkan halaman dashboard
    public function index() {
        // 1. Jumlah Judul Buku (menghitung baris buku)
        $totalBookTitles = Book::count();

        // 2. Jumlah Stok Buku (menjumlahkan seluruh angka stok)
        $totalBookStock  = Book::sum('stock');

        // 3. Jumlah Member
        $totalMembers    = Member::count();

        // 4. Jumlah yang Sedang Dipinjam
        $totalBorrowed   = Borrowing::where('status', 'borrowed')->count();

        // Tambahan: Total yang sudah dikembalikan
        $totalReturned   = Borrowing::where('status', 'returned')->count();

        return view('dashboard.index', compact(
            'totalBookTitles',
            'totalBookStock',
            'totalMembers',
            'totalBorrowed',
            'totalReturned'
        ));
    }
}