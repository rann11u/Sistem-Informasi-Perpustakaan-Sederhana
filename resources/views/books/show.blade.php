@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="margin:0;">Detail Buku</h2>
        <a href="{{ route('books.index') }}" style="color:#555; text-decoration:none; font-size:14px;">← Kembali ke Daftar</a>
    </div>

    <div style="display:flex; flex-wrap:wrap; gap:28px; align-items:flex-start; margin-bottom:28px;">
        <!-- Sampul Buku -->
        <div style="text-align:center; min-width:170px;">
            @if ($book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" 
                     style="width:160px; height:225px; object-fit:cover; border-radius:6px; border:1px solid #d1d5db; box-shadow:0 6px 14px rgba(0,0,0,0.15); display:block; margin:0 auto;">
            @else
                <div style="width:160px; height:225px; background:#f1f5f9; border-radius:6px; border:2px dashed #cbd5e1; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#94a3b8; margin:0 auto;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    <span style="font-size:12px; margin-top:8px;">Belum ada sampul</span>
                </div>
            @endif
        </div>

        <!-- Tabel Spesifikasi Buku -->
        <div style="flex:1; min-width:280px;">
            <table style="font-size:14px; border-collapse:collapse; width:100%;">
                @php 
                    $tdStyle = "padding:10px 14px; border-bottom:1px solid #eee; color:#1f2937;"; 
                    $thStyle = "padding:10px 14px; border-bottom:1px solid #eee; background:#f9fafb; font-weight:bold; width:140px; text-align:left; color:#4b5563;"; 
                @endphp
                <tr><th style="{{ $thStyle }}">Judul Buku</th><td style="{{ $tdStyle }}"><strong>{{ $book->title }}</strong></td></tr>
                <tr><th style="{{ $thStyle }}">Penulis</th><td style="{{ $tdStyle }}">{{ $book->author }}</td></tr>
                <tr><th style="{{ $thStyle }}">Penerbit</th><td style="{{ $tdStyle }}">{{ $book->publisher ?? '-' }}</td></tr>
                <tr><th style="{{ $thStyle }}">Kategori</th><td style="{{ $tdStyle }}">
                    <span style="background:#f1f5f9; color:#174a38; padding:3px 8px; border-radius:4px; font-weight:600; font-size:13px;">
                        {{ $book->category->name ?? '-' }}
                    </span>
                </td></tr>
                <tr><th style="{{ $thStyle }}">Tahun Terbit</th><td style="{{ $tdStyle }}">{{ $book->publication_year ?? '-' }}</td></tr>
                <tr><th style="{{ $thStyle }}">ISBN</th><td style="{{ $tdStyle }}">{{ $book->isbn ?? '-' }}</td></tr>
                <tr><th style="{{ $thStyle }}">Stok Tersedia</th><td style="{{ $tdStyle }}">
                    <strong style="{{ $book->stock == 0 ? 'color:#dc2626;' : 'color:#16a34a;' }}">{{ $book->stock }} eksemplar</strong>
                </td></tr>
            </table>

            <div style="margin-top:20px; display:flex; gap:10px;">
                <a href="{{ route('books.edit', $book->id) }}" style="background:#f9a825; color:white; padding:8px 18px; border-radius:6px; text-decoration:none; font-weight:600;">Edit Buku</a>
                <a href="{{ route('books.index') }}" style="background:#f3f4f6; color:#374151; padding:8px 18px; border-radius:6px; text-decoration:none; border:1px solid #d1d5db;">Kembali</a>
            </div>
        </div>
    </div>

    <!-- Bagian Sinopsis Buku -->
    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:20px; margin-top:10px;">
        <h3 style="margin:0 0 10px; font-size:16px; color:#174a38; display:flex; align-items:center; gap:8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="8" y1="6" x2="21" y2="6"></line>
                <line x1="8" y1="12" x2="21" y2="12"></line>
                <line x1="8" y1="18" x2="21" y2="18"></line>
                <line x1="3" y1="6" x2="3.01" y2="6"></line>
                <line x1="3" y1="12" x2="3.01" y2="12"></line>
                <line x1="3" y1="18" x2="3.01" y2="18"></line>
            </svg>
            Sinopsis Buku
        </h3>
        @if ($book->synopsis)
            <div style="font-size:14px; color:#334155; line-height:1.7; white-space:pre-line;">
                {{ $book->synopsis }}
            </div>
        @else
            <p style="margin:0; font-size:14px; color:#94a3b8; font-style:italic;">
                Belum ada sinopsis untuk buku ini.
            </p>
        @endif
    </div>
@endsection
