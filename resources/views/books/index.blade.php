@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2 style="margin:0;">Daftar Buku <span style="font-size:14px; color:#888;">(Total stok: {{ $totalBooks }})</span></h2>
        <a href="{{ route('books.create') }}" style="background:#174a38; color:white; padding:8px 16px; border-radius:6px; text-decoration:none;">+ Tambah Buku</a>
    </div>

    <table style="width:100%; border-collapse:collapse; font-size:14px;">
        <thead>
            <tr style="background:#f0f0f0;">
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">#</th>
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
                    <td style="padding:10px;">{{ $loop->iteration }}</td>
                    <td style="padding:10px;">{{ $book->title }}</td>
                    <td style="padding:10px;">{{ $book->author }}</td>
                    <td style="padding:10px;">{{ $book->category->name ?? '-' }}</td>
                    <td style="padding:10px;">{{ $book->publication_year ?? '-' }}</td>
                    <td style="padding:10px;">{{ $book->stock }}</td>
                    <td style="padding:10px;">
                        <a href="{{ route('books.show', $book->id) }}" style="color:#1565c0; margin-right:8px;">Detail</a>
                        <a href="{{ route('books.edit', $book->id) }}" style="color:#f9a825; margin-right:8px;">Edit</a>
                        <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;"
                              onsubmit="openDeleteModal(event, 'Buku {{ addslashes($book->title) }}')">
                            @csrf @method('DELETE')
                            <button type="submit" style="color:#c62828; background:none; border:none; cursor:pointer; padding:0;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="padding:20px; text-align:center; color:#888;">Belum ada data buku.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
