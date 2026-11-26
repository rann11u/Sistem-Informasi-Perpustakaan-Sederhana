<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;
use App\Models\Borrowing;

class BorrowingHistoryController extends Controller
{
    /**
     * Menampilkan halaman riwayat peminjaman milik member yang sedang login.
     */
    public function index(Request $request)
    {
        $user   = Auth::user();
        $member = $user->member ?? Member::where('email', $user->email)->first();

        $borrowings = collect(); // default kosong

        if ($member) {
            $query = Borrowing::with(['details.book.category', 'user'])
                ->where('member_id', $member->id);

            // Filter status jika ada
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $borrowings = $query->latest()->get();
        }

        // Hitung per-status untuk badge tab
        $countBooked   = $member ? Borrowing::where('member_id', $member->id)->where('status', 'booked')->count()   : 0;
        $countBorrowed = $member ? Borrowing::where('member_id', $member->id)->where('status', 'borrowed')->count() : 0;
        $countReturned = $member ? Borrowing::where('member_id', $member->id)->where('status', 'returned')->count() : 0;

        return view('member.history', compact('user', 'member', 'borrowings', 'countBooked', 'countBorrowed', 'countReturned'));
    }

    /**
     * Menampilkan halaman cetak (PDF-print) untuk satu transaksi.
     * URL: /riwayat/{id}/invoice
     */
    public function invoice($id)
    {
        $user   = Auth::user();
        $member = $user->member ?? Member::where('email', $user->email)->first();

        // Pastikan transaksi milik member yang login
        $borrowing = Borrowing::with(['details.book.category', 'member', 'user'])
            ->where('id', $id)
            ->where('member_id', optional($member)->id)
            ->firstOrFail();

        return view('member.invoice', compact('borrowing', 'member', 'user'));
    }
}
