<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;

class MemberBookController extends Controller
{
    /**
     * Menampilkan katalog buku dengan fitur pencarian dan filter kategori
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $query = Book::with('category');

        // Pencarian berdasarkan Judul, Pengarang, atau ISBN
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan Kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Tampilkan dengan pagination 12 buku per halaman
        $books = $query->latest()->paginate(12)->withQueryString();

        // Hitung kuota buku aktif member (maksimal 5 buku)
        $member = auth()->user()->member;
        $activeBookingsCount = 0;
        if ($member) {
            $activeBookingsCount = Borrowing::where('member_id', $member->id)
                ->whereIn('status', ['borrowed', 'booked'])
                ->with('details')
                ->get()
                ->sum(function ($b) {
                    return $b->details->sum('quantity');
                });
        }

        return view('member.books', compact('books', 'categories', 'activeBookingsCount'));
    }

    /**
     * Memproses booking buku oleh member
     */
    public function book(Request $request, $bookId)
    {
        $user = auth()->user();
        $member = $user->member;

        if (! $member) {
            return back()->with('error', 'Profil anggota Anda belum lengkap.');
        }

        // 1. Validasi Maksimal 5 Buku (Buku yang sedang dipinjam + sedang dibooking)
        $activeCount = Borrowing::where('member_id', $member->id)
            ->whereIn('status', ['borrowed', 'booked'])
            ->with('details')
            ->get()
            ->sum(function ($b) {
                return $b->details->sum('quantity');
            });

        if ($activeCount >= 5) {
            return back()->with('error', 'Gagal booking! Anda sudah mencapai batas maksimal 5 buku (sedang dipinjam / dibooking). Silakan kembalikan buku terlebih dahulu.');
        }

        // 2. Cek apakah member sudah membooking buku yang sama sebelumnya
        $alreadyBooked = Borrowing::where('member_id', $member->id)
            ->where('status', 'booked')
            ->whereHas('details', function ($q) use ($bookId) {
                $q->where('book_id', $bookId);
            })->exists();

        if ($alreadyBooked) {
            return back()->with('error', 'Anda sudah membooking buku ini sebelumnya dan masih menunggu pengambilan.');
        }

        // 3. Cek ketersediaan stok buku
        $book = Book::findOrFail($bookId);
        if ($book->stock < 1) {
            return back()->with('error', 'Maaf, stok buku ini sedang habis.');
        }

        // 4. Hitung batas waktu pengambilan 1 hari (24 jam)
        $pickupDeadline = now()->addHours(24);
        $borrowDate     = now()->toDateString();
        $dueDate        = now()->addDays(7)->toDateString();

        $borrowing = Borrowing::create([
            'user_id'         => null, // Diisi nanti oleh petugas saat buku diambil
            'member_id'       => $member->id,
            'borrow_date'     => $borrowDate,
            'due_date'        => $dueDate,
            'pickup_deadline' => $pickupDeadline,
            'status'          => 'booked',
        ]);

        BorrowingDetail::create([
            'borrowing_id' => $borrowing->id,
            'book_id'      => $book->id,
            'quantity'     => 1,
        ]);

        // Kurangi stok buku
        $book->decrement('stock');

        return back()->with('success', "Buku '{$book->title}' berhasil dibooking! Silakan ambil di perpustakaan sebelum " . $pickupDeadline->format('d M Y, H:i') . ' WIB.');
    }
}
