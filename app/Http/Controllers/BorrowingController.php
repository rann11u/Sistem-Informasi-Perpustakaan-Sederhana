<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Member;
use App\Models\Book;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    // Read - Tampilkan semua peminjaman dengan filter tab
    public function index(Request $request) {
        $query = Borrowing::with(['member', 'user', 'details.book']);

        // Filter status jika dipilih
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->latest()->get();

        // Hitung ringkasan status untuk tab
        $countBooked   = Borrowing::where('status', 'booked')->count();
        $countBorrowed = Borrowing::where('status', 'borrowed')->count();
        $countReturned = Borrowing::where('status', 'returned')->count();

        return view('borrowings.index', compact('borrowings', 'countBooked', 'countBorrowed', 'countReturned'));
    }

    // Tampilkan form tambah peminjaman langsung (offline)
    public function create() {
        $members = Member::all();
        $books   = Book::where('stock', '>', 0)->get();

        return view('borrowings.create', compact('members', 'books'));
    }

    // Store - Simpan peminjaman langsung
    public function store(Request $request) {
        $request->validate([
            'member_id'   => 'required|exists:members,id',
            'borrow_date' => 'required|date',
            'book_id'     => 'required|exists:books,id',
            'quantity'    => 'required|integer|min:1',
        ]);

        // Validasi kuota maksimal 5 buku per member
        $activeCount = Borrowing::where('member_id', $request->member_id)
            ->whereIn('status', ['borrowed', 'booked'])
            ->with('details')
            ->get()
            ->sum(function ($b) {
                return $b->details->sum('quantity');
            });

        if (($activeCount + $request->quantity) > 5) {
            return back()->with('error', "Gagal! Member ini sudah memiliki {$activeCount} buku aktif (dipinjam/booking). Batas maksimal adalah 5 buku.")->withInput();
        }

        // Cek stok buku
        $book = Book::findOrFail($request->book_id);
        if ($book->stock < $request->quantity) {
            return back()->with('error', 'Stok buku tidak mencukupi.')->withInput();
        }

        // Otomatis: Jatuh tempo 1 minggu (7 hari) dari tanggal peminjaman
        $dueDate = Carbon::parse($request->borrow_date)->addDays(7)->toDateString();

        // Buat data peminjaman
        $borrowing = Borrowing::create([
            'user_id'     => auth()->id(),
            'member_id'   => $request->member_id,
            'borrow_date' => $request->borrow_date,
            'due_date'    => $dueDate,
            'status'      => 'borrowed',
        ]);

        // Simpan detail buku yang dipinjam
        BorrowingDetail::create([
            'borrowing_id' => $borrowing->id,
            'book_id'      => $request->book_id,
            'quantity'     => $request->quantity,
        ]);

        // Kurangi stok buku
        $book->stock = $book->stock - $request->quantity;
        $book->save();

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    // Show - Detail peminjaman
    public function show($id) {
        $borrowing = Borrowing::with(['member', 'user', 'details.book'])->findOrFail($id);

        return view('borrowings.show', compact('borrowing'));
    }

    // Konfirmasi Pengambilan Buku Booking oleh Petugas
    public function confirmPickup($id) {
        $borrowing = Borrowing::findOrFail($id);

        if ($borrowing->status !== 'booked') {
            return back()->with('error', 'Status transaksi ini bukan booking.');
        }

        // Ubah status menjadi borrowed, catat petugas penyerah, perbarui tanggal pinjam & due date
        $borrowing->status      = 'borrowed';
        $borrowing->user_id     = auth()->id();
        $borrowing->borrow_date = now()->toDateString();
        $borrowing->due_date    = now()->addDays(7)->toDateString();
        $borrowing->save();

        return back()->with('success', 'Buku berhasil diserahkan kepada member. Status berubah menjadi Dipinjam.');
    }

    // Batalkan Booking (misal lewat 1 hari atau member membatalkan)
    public function cancelBooking($id) {
        $borrowing = Borrowing::with('details.book')->findOrFail($id);

        if ($borrowing->status !== 'booked') {
            return back()->with('error', 'Hanya transaksi berstatus booking yang dapat dibatalkan.');
        }

        // Kembalikan stok setiap buku yang dibooking
        foreach ($borrowing->details as $detail) {
            $detail->book->increment('stock', $detail->quantity);
        }

        $borrowing->status = 'cancelled';
        $borrowing->save();

        return back()->with('success', 'Booking berhasil dibatalkan dan stok buku telah dikembalikan.');
    }

    // Tampilkan form pengembalian buku
    public function edit($id) {
        $borrowing = Borrowing::findOrFail($id);

        return view('borrowings.edit', compact('borrowing'));
    }

    // Update - Proses pengembalian buku
    public function update(Request $request, $id) {
        $borrowing = Borrowing::findOrFail($id);

        $request->validate([
            'return_date' => 'required|date',
        ]);

        // Kembalikan stok setiap buku yang dipinjam
        foreach ($borrowing->details as $detail) {
            $detail->book->stock = $detail->book->stock + $detail->quantity;
            $detail->book->save();
        }

        // Update status peminjaman
        $borrowing->return_date = $request->return_date;
        $borrowing->status      = 'returned';
        $borrowing->save();

        return redirect()->route('borrowings.index')->with('success', 'Buku berhasil dikembalikan.');
    }

    // Delete - Hapus peminjaman
    public function destroy($id) {
        $borrowing = Borrowing::findOrFail($id);
        $borrowing->delete();

        return redirect()->route('borrowings.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }

    // Cetak / Download Bukti Pinjam (Invoice PDF) untuk Petugas & Admin
    public function invoice($id) {
        $borrowing = Borrowing::with(['details.book.category', 'member', 'user'])->findOrFail($id);
        $member    = $borrowing->member;
        $user      = $borrowing->user ?? auth()->user();

        return view('member.invoice', compact('borrowing', 'member', 'user'));
    }
}
