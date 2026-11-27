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

    /**
     * Memperpanjang masa peminjaman (+3 hari) oleh member.
     * Syarat: status borrowed, belum pernah diperpanjang, dan belum melewati jatuh tempo.
     */
    public function extend($id)
    {
        $user   = Auth::user();
        $member = $user->member ?? Member::where('email', $user->email)->first();

        $borrowing = Borrowing::where('id', $id)
            ->where('member_id', optional($member)->id)
            ->firstOrFail();

        if ($borrowing->status !== 'borrowed') {
            return back()->with('error', 'Hanya peminjaman yang sedang aktif (Dipinjam) yang dapat diperpanjang.');
        }

        if ($borrowing->is_extended) {
            return back()->with('error', 'Pinjaman ini sudah pernah diperpanjang sebelumnya (maksimal 1 kali perpanjangan).');
        }

        if ($borrowing->due_date && now()->startOfDay()->gt($borrowing->due_date->startOfDay())) {
            return back()->with('error', 'Pinjaman sudah melewati tanggal jatuh tempo dan tidak dapat diperpanjang.');
        }

        $borrowing->due_date    = $borrowing->due_date->addDays(3);
        $borrowing->is_extended = true;
        $borrowing->save();

        return back()->with('success', 'Pinjaman berhasil diperpanjang 3 hari! Jatuh tempo baru: ' . $borrowing->due_date->format('d M Y'));
    }
}
