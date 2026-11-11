<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // Read - Tampilkan semua buku
    public function index() {
        $totalBooks = Book::sum('stock');
        $books = Book::with('category')->get();

        return view('books.index', compact('books', 'totalBooks'));
    }

    // Tampilkan detail satu buku
    public function show($id) {
        $book = Book::with('category')->findOrFail($id);

        return view('books.show', compact('book'));
    }

    // Tampilkan form tambah buku
    public function create() {
        $categories = Category::all();

        return view('books.create', compact('categories'));
    }

    // Store - Simpan buku baru
    public function store(Request $request) {
        $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'title'            => 'required|string|max:255',
            'author'           => 'required|string|max:255',
            'publisher'        => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'year'             => 'nullable|integer|min:1000|max:' . date('Y'),
            'isbn'             => 'nullable|string|max:20|unique:books,isbn',
            'stock'            => 'required|integer|min:0',
            'cover_image'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'synopsis'         => 'nullable|string',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('books', 'public');
        }

        Book::create([
            'category_id'      => $request->category_id,
            'title'            => $request->title,
            'author'           => $request->author,
            'publisher'        => $request->publisher,
            'publication_year' => $request->publication_year ?? $request->year,
            'isbn'             => $request->isbn,
            'stock'            => $request->stock,
            'cover_image'      => $coverPath,
            'synopsis'         => $request->synopsis,
        ]);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    // Tampilkan form edit buku
    public function edit($id) {
        $book = Book::findOrFail($id);
        $categories = Category::all();

        return view('books.edit', compact('book', 'categories'));
    }

    // Update - Perbarui data buku
    public function update(Request $request, $id) {
        $book = Book::findOrFail($id);

        $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'title'            => 'required|string|max:255',
            'author'           => 'required|string|max:255',
            'publisher'        => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'year'             => 'nullable|integer|min:1000|max:' . date('Y'),
            'isbn'             => 'nullable|string|max:20|unique:books,isbn,' . $id,
            'stock'            => 'required|integer|min:0',
            'cover_image'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'synopsis'         => 'nullable|string',
        ]);

        $coverPath = $book->cover_image;
        if ($request->hasFile('cover_image')) {
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $coverPath = $request->file('cover_image')->store('books', 'public');
        }

        $book->update([
            'category_id'      => $request->category_id,
            'title'            => $request->title,
            'author'           => $request->author,
            'publisher'        => $request->publisher,
            'publication_year' => $request->publication_year ?? $request->year,
            'isbn'             => $request->isbn,
            'stock'            => $request->stock,
            'cover_image'      => $coverPath,
            'synopsis'         => $request->synopsis,
        ]);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    // Delete - Hapus buku
    public function destroy($id) {
        $book = Book::findOrFail($id);

        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}
