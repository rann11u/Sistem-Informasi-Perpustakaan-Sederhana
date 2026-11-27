@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <div class="admin-card">
        <div class="page-header-action">
            <div>
                <h2>Daftar Buku</h2>
                <small style="color:var(--color-muted);">Total stok keseluruhan: {{ $totalBooks }} buku</small>
            </div>
            <a href="{{ route('books.create') }}" class="btn-ui btn-ui-primary">+ Tambah Buku</a>
        </div>

        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th style="width:65px;">Sampul</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Kategori</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $book)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" 
                                         style="width:42px; height:58px; object-fit:cover; border-radius:4px; border:1px solid var(--card-border); display:block;">
                                @else
                                    <div style="width:42px; height:58px; background:var(--table-head-bg); border-radius:4px; border:1px dashed var(--card-border); display:flex; flex-direction:column; align-items:center; justify-content:center; color:var(--color-muted); font-size:10px;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td><strong>{{ $book->title }}</strong></td>
                            <td style="color:var(--color-muted);">{{ $book->author }}</td>
                            <td>
                                <span style="background:var(--table-head-bg); color:var(--color-ink); padding:3px 8px; border-radius:4px; font-size:12px; font-weight:500;">
                                    {{ $book->category->name ?? '-' }}
                                </span>
                            </td>
                            <td style="color:var(--color-muted);">{{ $book->publication_year ?? '-' }}</td>
                            <td>
                                <span style="font-weight:700; {{ $book->stock == 0 ? 'color:var(--color-error);' : '' }}">{{ $book->stock }}</span>
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <a href="{{ route('books.show', $book->id) }}" class="btn-ui btn-ui-secondary btn-ui-sm">Detail</a>
                                    <a href="{{ route('books.edit', $book->id) }}" class="btn-ui btn-ui-secondary btn-ui-sm">Edit</a>
                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;"
                                          onsubmit="openDeleteModal(event, 'Buku {{ addslashes($book->title) }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-ui btn-ui-danger btn-ui-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding:28px; text-align:center; color:var(--color-muted);">Belum ada data buku.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
