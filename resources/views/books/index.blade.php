@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2 style="margin:0;">Daftar Buku <span style="font-size:14px; color:#888;">(Total stok: {{ $totalBooks }})</span></h2>
        <a href="{{ route('books.create') }}" style="background:#174a38; color:white; padding:8px 16px; border-radius:6px; text-decoration:none; font-weight:600;">+ Tambah Buku</a>
    </div>

    <table style="width:100%; border-collapse:collapse; font-size:14px;">
        <thead>
            <tr style="background:#f0f0f0;">
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd; width:40px;">#</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd; width:65px;">Sampul</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Judul</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Penulis</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Kategori</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Tahun</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Stok</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:10px; vertical-align:middle;">{{ $loop->iteration }}</td>
                    <td style="padding:10px; vertical-align:middle;">
                        @if ($book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" 
                                 style="width:45px; height:62px; object-fit:cover; border-radius:4px; border:1px solid #d1d5db; box-shadow:0 1px 3px rgba(0,0,0,0.1); display:block;">
                        @else
                            <div style="width:45px; height:62px; background:#e2e8f0; border-radius:4px; border:1px dashed #cbd5e1; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#94a3b8; font-size:10px;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                                <span>Buku</span>
                            </div>
                        @endif
                    </td>
                    <td style="padding:10px; vertical-align:middle;"><strong>{{ $book->title }}</strong></td>
                    <td style="padding:10px; vertical-align:middle;">{{ $book->author }}</td>
                    <td style="padding:10px; vertical-align:middle;">
                        <span style="background:#f1f5f9; color:#334155; padding:3px 8px; border-radius:4px; font-size:12px; font-weight:500;">
                            {{ $book->category->name ?? '-' }}
                        </span>
                    </td>
                    <td style="padding:10px; vertical-align:middle;">{{ $book->publication_year ?? '-' }}</td>
                    <td style="padding:10px; vertical-align:middle;">
                        <span style="font-weight:600; {{ $book->stock == 0 ? 'color:#dc2626;' : '' }}">{{ $book->stock }}</span>
                    </td>
                    <td style="padding:10px; vertical-align:middle;">
                        <a href="{{ route('books.show', $book->id) }}" style="color:#1565c0; margin-right:8px; text-decoration:none; font-weight:600;">Detail</a>
                        <a href="{{ route('books.edit', $book->id) }}" style="color:#f9a825; margin-right:8px; text-decoration:none;">Edit</a>
                        <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;"
                              onsubmit="openDeleteModal(event, 'Buku {{ addslashes($book->title) }}')">
                            @csrf @method('DELETE')
                            <button type="submit" style="color:#c62828; background:none; border:none; cursor:pointer; padding:0;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="padding:20px; text-align:center; color:#888;">Belum ada data buku.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
