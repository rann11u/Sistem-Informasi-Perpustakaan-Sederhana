<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Member;
use App\Models\Book;

class BorrowingController extends Controller
{
    // Read - Tampilkan semua peminjaman
    public function index() {
        $borrowings = Borrowing::all();

        return view('borrowings.index', compact('borrowings'));
    }

    // Tampilkan form tambah peminjaman
    public function create() {
        $members = Member::all();
        $books   = Book::where('stock', '>', 0)->get();

        return view('borrowings.create', compact('members', 'books'));
    }

    // Store - Simpan peminjaman baru
    public function store(Request $request) {
        $request->validate([
            'member_id'   => 'required|exists:members,id',
            'borrow_date' => 'required|date',
            'due_date'    => 'required|date|after_or_equal:borrow_date',
            'book_id'     => 'required|exists:books,id',
            'quantity'    => 'required|integer|min:1',
        ]);

        // Cek stok buku
        $book = Book::findOrFail($request->book_id);
        if ($book->stock < $request->quantity) {
            return back()->with('error', 'Stok buku tidak mencukupi.')->withInput();
        }

        // Buat data peminjaman
        $borrowing = Borrowing::create([
            'user_id'     => auth()->id(),
            'member_id'   => $request->member_id,
            'borrow_date' => $request->borrow_date,
            'due_date'    => $request->due_date,
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
        $borrowing = Borrowing::findOrFail($id);

        return view('borrowings.show', compact('borrowing'));
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
}
