<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;

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

        return view('member.books', compact('books', 'categories'));
    }
}
