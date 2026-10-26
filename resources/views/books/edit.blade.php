@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
    <h2 style="margin-top:0;">Edit Buku</h2>

    <form action="{{ route('books.update', $book->id) }}" method="POST" style="max-width:520px;">
        @csrf @method('PUT')

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Kategori <span style="color:red">*</span></label>
            <select name="category_id" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Judul <span style="color:red">*</span></label>
            <input type="text" name="title" value="{{ old('title', $book->title) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Penulis <span style="color:red">*</span></label>
            <input type="text" name="author" value="{{ old('author', $book->author) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Penerbit</label>
            <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Tahun Terbit</label>
            <input type="number" name="publication_year" value="{{ old('publication_year', $book->publication_year) }}" min="1000" max="{{ date('Y') }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">ISBN</label>
            <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Stok <span style="color:red">*</span></label>
            <input type="number" name="stock" value="{{ old('stock', $book->stock) }}" min="0" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
        </div>

        <button type="submit" style="background:#174a38; color:white; padding:10px 20px; border:none; border-radius:6px; cursor:pointer;">Perbarui</button>
        <a href="{{ route('books.index') }}" style="margin-left:12px; color:#555;">Batal</a>
    </form>
@endsection

