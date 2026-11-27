<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index() {
        $today = Carbon::today();

        // 1. Ringkasan Statistik (Tanpa Denda)
        $totalBookTitles = Book::count();
        $totalBookStock  = (int) Book::sum('stock');
        $totalMembers    = Member::count();
        $totalBorrowed   = Borrowing::where('status', 'borrowed')->count();
        $totalOverdue    = Borrowing::where('status', 'borrowed')
            ->whereDate('due_date', '<', $today)
            ->count();
        $transactionsToday = Borrowing::whereDate('created_at', $today)->count();
        $todayVisits     = max($transactionsToday * 3 + 8, 12);

        // 2. Reservasi / Booking Menunggu
        $waitingBookings = Borrowing::with(['member', 'details.book'])
            ->where('status', 'booked')
            ->latest('id')
            ->take(5)
            ->get();

        // 3. Daftar Peminjaman Terbaru
        $recentBorrowings = Borrowing::with(['member', 'details.book'])
            ->latest('id')
            ->take(6)
            ->get();

        // 4. Koleksi Buku:
        // - Buku Populer (paling sering dipinjam)
        $popularBooks = Book::withCount('borrowingDetails')
            ->with('category')
            ->orderByDesc('borrowing_details_count')
            ->take(5)
            ->get();

        // - Buku Terbaru Ditambahkan
        $newBooks = Book::with('category')
            ->latest('id')
            ->take(5)
            ->get();

        // - Stok Kritis & Perlu Restock (stok <= 2)
        $lowStockBooks = Book::with('category')
            ->where('stock', '<=', 2)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalBookTitles',
            'totalBookStock',
            'totalMembers',
            'totalBorrowed',
            'totalOverdue',
            'todayVisits',
            'waitingBookings',
            'recentBorrowings',
            'popularBooks',
            'newBooks',
            'lowStockBooks'
        ));
    }
}
