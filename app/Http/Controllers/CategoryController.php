<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Read - Tampilkan semua kategori
    public function index() {
        $categories = Category::all();

        return view('categories.index', compact('categories'));
    }

    // Tampilkan form tambah
    public function create() {
        return view('categories.create');
    }

    // Store - Simpan kategori baru
    public function store(Request $request) {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
        ]);

        Category::create([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    // Tampilkan form edit
    public function edit($id) {
        $category = Category::findOrFail($id);

        return view('categories.edit', compact('category'));
    }

    // Update - Perbarui kategori
    public function update(Request $request, $id) {
        $category = Category::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string|max:1000',
        ]);

        $category->update([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    // Delete - Hapus kategori
    public function destroy($id) {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
