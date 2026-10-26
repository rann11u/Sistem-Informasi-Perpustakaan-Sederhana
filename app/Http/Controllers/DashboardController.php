<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;

class DashboardController extends Controller
{
    // Tampilkan halaman dashboard
    public function index() {
        $totalBooks    = Book::sum('stock');
        $totalMembers  = Member::count();
        $totalBorrowed = Borrowing::where('status', 'borrowed')->count();
        $totalReturned = Borrowing::where('status', 'returned')->count();

        return view('dashboard.index', compact(
            'totalBooks',
            'totalMembers',
            'totalBorrowed',
            'totalReturned'
        ));
    }
}
