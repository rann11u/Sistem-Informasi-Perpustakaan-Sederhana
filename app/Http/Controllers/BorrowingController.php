<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Member;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        // Hitung berapa booking yang telah kedaluwarsa (lewat batas 1x24 jam)
        $countExpired  = Borrowing::where('status', 'booked')
            ->whereNotNull('pickup_deadline')
            ->where('pickup_deadline', '<', now())
            ->count();

        return view('borrowings.index', compact('borrowings', 'countBooked', 'countBorrowed', 'countReturned', 'countExpired'));
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

        $errorMessage = DB::transaction(function () use ($request) {
            // Validasi kuota maksimal 5 buku per member (Online + Offline)
            $borrowedCount = Borrowing::where('member_id', $request->member_id)
                ->where('status', 'borrowed')
                ->with('details')
                ->get()
                ->sum(fn ($b) => $b->details->sum('quantity'));

            $bookedCount = Borrowing::where('member_id', $request->member_id)
                ->where('status', 'booked')
                ->with('details')
                ->get()
                ->sum(fn ($b) => $b->details->sum('quantity'));

            $activeCount    = $borrowedCount + $bookedCount;
            $remainingQuota = max(0, 5 - $activeCount);

            if (($activeCount + $request->quantity) > 5) {
                $details = [];
                if ($borrowedCount > 0) {
                    $details[] = "{$borrowedCount} buku sedang dipinjam";
                }
                if ($bookedCount > 0) {
                    $details[] = "{$bookedCount} buku sudah di-booking online";
                }
                $detailText = count($details) > 0 ? ' (' . implode(', ', $details) . ')' : '';

                return "Gagal! Kuota tersisa hanya {$remainingQuota} buku{$detailText}.";
            }

            // Kunci baris buku agar stok tidak balapan (race condition)
            $book = Book::where('id', $request->book_id)->lockForUpdate()->firstOrFail();
            if ($book->stock < $request->quantity) {
                return 'Stok buku tidak mencukupi.';
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
            $book->decrement('stock', $request->quantity);

            return null;
        });

        if ($errorMessage) {
            return back()->with('error', $errorMessage)->withInput();
        }

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    // Show - Detail peminjaman
    public function show($id) {
        $borrowing = Borrowing::with(['member', 'user', 'details.book'])->findOrFail($id);

        return view('borrowings.show', compact('borrowing'));
    }

    // Konfirmasi Pengambilan Buku Booking oleh Petugas
    public function confirmPickup($id) {
        $errorMessage = DB::transaction(function () use ($id) {
            $borrowing = Borrowing::where('id', $id)->lockForUpdate()->firstOrFail();

            if ($borrowing->status !== 'booked') {
                return 'Status transaksi ini bukan booking.';
            }

            // Ubah status menjadi borrowed, catat petugas penyerah, perbarui tanggal pinjam & due date
            $borrowing->status      = 'borrowed';
            $borrowing->user_id     = auth()->id();
            $borrowing->borrow_date = now()->toDateString();
            $borrowing->due_date    = now()->addDays(7)->toDateString();
            $borrowing->save();

            return null;
        });

        if ($errorMessage) {
            return back()->with('error', $errorMessage);
        }

        return back()->with('success', 'Buku berhasil diserahkan kepada member. Status berubah menjadi Dipinjam.');
    }

    // Batalkan Booking (misal lewat 1 hari atau member membatalkan)
    public function cancelBooking($id) {
        $result = DB::transaction(function () use ($id) {
            $borrowing = Borrowing::with('details')->where('id', $id)->lockForUpdate()->firstOrFail();

            if ($borrowing->status !== 'booked') {
                return ['error' => 'Hanya transaksi berstatus booking yang dapat dibatalkan.'];
            }

            $isExpired = $borrowing->isExpired();

            // Kembalikan stok setiap buku yang dibooking
            foreach ($borrowing->details as $detail) {
                if ($detail->book_id) {
                    Book::where('id', $detail->book_id)
                        ->lockForUpdate()
                        ->increment('stock', $detail->quantity);
                }
            }

            $borrowing->status = 'cancelled';
            $borrowing->save();

            return ['expired' => $isExpired];
        });

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        if ($result['expired']) {
            return back()->with('success', 'Booking berhasil dibatalkan karena telah melewati batas waktu 1x24 jam (kedaluwarsa). Stok buku telah otomatis dikembalikan ke katalog.');
        }

        return back()->with('success', 'Booking berhasil dibatalkan dan stok buku telah dikembalikan.');
    }

    // Batalkan Seluruh Booking yang Kedaluwarsa (> 1 Hari) Sekaligus
    public function cancelAllExpired() {
        $count = Borrowing::cancelExpiredBookings();

        if ($count > 0) {
            return back()->with('success', "Berhasil membatalkan {$count} booking yang kedaluwarsa (> 1 hari). Seluruh stok buku telah dikembalikan ke katalog.");
        }

        return back()->with('info', 'Tidak ada booking yang kedaluwarsa saat ini.');
    }

    // Tampilkan form pengembalian buku
    public function edit($id) {
        $borrowing = Borrowing::with(['member', 'details.book'])->findOrFail($id);

        return view('borrowings.edit', compact('borrowing'));
    }

    // Update - Proses pengembalian buku
    public function update(Request $request, $id) {
        $request->validate([
            'return_date' => 'required|date',
        ]);

        $errorMessage = DB::transaction(function () use ($request, $id) {
            $borrowing = Borrowing::with('details')->where('id', $id)->lockForUpdate()->firstOrFail();

            if ($borrowing->status !== 'borrowed') {
                return 'Transaksi ini tidak dalam status dipinjam sehingga tidak dapat dikembalikan ulang.';
            }

            // Kembalikan stok setiap buku yang dipinjam
            foreach ($borrowing->details as $detail) {
                if ($detail->book_id) {
                    Book::where('id', $detail->book_id)
                        ->lockForUpdate()
                        ->increment('stock', $detail->quantity);
                }
            }

            // Update status peminjaman
            $borrowing->return_date = $request->return_date;
            $borrowing->status      = 'returned';
            $borrowing->save();

            return null;
        });

        if ($errorMessage) {
            return redirect()->route('borrowings.index')->with('error', $errorMessage);
        }

        return redirect()->route('borrowings.index')->with('success', 'Buku berhasil dikembalikan dan stok katalog telah diperbarui.');
    }

    // Delete - Hapus peminjaman
    public function destroy($id) {
        DB::transaction(function () use ($id) {
            $borrowing = Borrowing::with('details')->where('id', $id)->lockForUpdate()->firstOrFail();

            if (in_array($borrowing->status, ['booked', 'borrowed'])) {
                foreach ($borrowing->details as $detail) {
                    if ($detail->book_id) {
                        Book::where('id', $detail->book_id)
                            ->lockForUpdate()
                            ->increment('stock', $detail->quantity);
                    }
                }
            }

            $borrowing->delete();
        });

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
