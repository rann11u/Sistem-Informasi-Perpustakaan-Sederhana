@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
    <h2 style="margin-top:0;">Detail Buku</h2>

    <table style="font-size:14px; border-collapse:collapse; width:100%; max-width:520px;">
        @php $tdStyle = "padding:10px 14px; border-bottom:1px solid #eee;"; $thStyle = "padding:10px 14px; border-bottom:1px solid #eee; background:#f9f9f9; font-weight:bold; width:160px; text-align:left;"; @endphp
        <tr><th style="{{ $thStyle }}">Judul</th><td style="{{ $tdStyle }}">{{ $book->title }}</td></tr>
        <tr><th style="{{ $thStyle }}">Penulis</th><td style="{{ $tdStyle }}">{{ $book->author }}</td></tr>
        <tr><th style="{{ $thStyle }}">Penerbit</th><td style="{{ $tdStyle }}">{{ $book->publisher ?? '-' }}</td></tr>
        <tr><th style="{{ $thStyle }}">Kategori</th><td style="{{ $tdStyle }}">{{ $book->category->name ?? '-' }}</td></tr>
        <tr><th style="{{ $thStyle }}">Tahun Terbit</th><td style="{{ $tdStyle }}">{{ $book->publication_year ?? '-' }}</td></tr>
        <tr><th style="{{ $thStyle }}">ISBN</th><td style="{{ $tdStyle }}">{{ $book->isbn ?? '-' }}</td></tr>
        <tr><th style="{{ $thStyle }}">Stok</th><td style="{{ $tdStyle }}">{{ $book->stock }}</td></tr>
    </table>

    <div style="margin-top:20px;">
        <a href="{{ route('books.edit', $book->id) }}" style="background:#f9a825; color:white; padding:8px 16px; border-radius:6px; text-decoration:none; margin-right:8px;">Edit</a>
        <a href="{{ route('books.index') }}" style="color:#555;">← Kembali</a>
    </div>
@endsection

