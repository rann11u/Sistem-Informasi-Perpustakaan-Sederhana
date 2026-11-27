@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
    <div class="admin-card">
        <div class="page-header-action">
            <h2>Detail Buku</h2>
            <a href="{{ route('books.index') }}" class="btn-ui btn-ui-secondary">← Kembali ke Daftar</a>
        </div>

        <div style="display:flex; flex-wrap:wrap; gap:28px; align-items:flex-start; margin-bottom:28px;">
            <!-- Sampul Buku -->
            <div style="text-align:center; min-width:170px;">
                @if ($book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" 
                         style="width:160px; height:225px; object-fit:cover; border-radius:8px; border:1px solid var(--card-border); box-shadow:0 6px 16px rgba(0,0,0,0.15); display:block; margin:0 auto;">
                @else
                    <div style="width:160px; height:225px; background:var(--table-head-bg); border-radius:8px; border:2px dashed var(--card-border); display:flex; flex-direction:column; align-items:center; justify-content:center; color:var(--color-muted); margin:0 auto;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <span style="font-size:12px; margin-top:8px;">Belum ada sampul</span>
                    </div>
                @endif
            </div>

            <!-- Tabel Spesifikasi Buku -->
            <div style="flex:1; min-width:280px;">
                <table class="admin-table">
                    <tr><th style="width:140px;">Judul Buku</th><td><strong>{{ $book->title }}</strong></td></tr>
                    <tr><th>Penulis</th><td>{{ $book->author }}</td></tr>
                    <tr><th>Penerbit</th><td>{{ $book->publisher ?? '-' }}</td></tr>
                    <tr><th>Kategori</th><td>
                        <span style="background:var(--table-head-bg); color:var(--color-accent); padding:4px 10px; border-radius:4px; font-weight:600; font-size:12px;">
                            {{ $book->category->name ?? '-' }}
                        </span>
                    </td></tr>
                    <tr><th>Tahun Terbit</th><td>{{ $book->publication_year ?? '-' }}</td></tr>
                    <tr><th>ISBN</th><td>{{ $book->isbn ?? '-' }}</td></tr>
                    <tr><th>Stok Tersedia</th><td>
                        <strong style="{{ $book->stock == 0 ? 'color:var(--color-error);' : 'color:var(--color-success);' }}">{{ $book->stock }} buku</strong>
                    </td></tr>
                </table>

                <div style="margin-top:20px; display:flex; gap:10px;">
                    <a href="{{ route('books.edit', $book->id) }}" class="btn-ui btn-ui-primary">Edit Buku</a>
                    <a href="{{ route('books.index') }}" class="btn-ui btn-ui-secondary">Kembali</a>
                </div>
            </div>
        </div>

        <!-- Sinopsis Buku -->
        @if ($book->synopsis)
            <div style="border-top:1px solid var(--table-border); padding-top:20px; margin-top:10px;">
                <h3 style="margin:0 0 8px; font-size:15px; font-weight:600;">Sinopsis Buku</h3>
                <p style="color:var(--color-muted); line-height:1.7; margin:0; font-size:14px;">
                    {{ $book->synopsis }}
                </p>
            </div>
        @endif
    </div>
@endsection
