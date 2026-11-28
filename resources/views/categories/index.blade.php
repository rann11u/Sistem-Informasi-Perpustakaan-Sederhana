@extends('layouts.app')

@section('title', 'Kategori Buku')

@section('content')
    <div class="admin-card">
        <div class="page-header-action">
            <div>
                <h2>Kategori Buku</h2>
                <small style="color:var(--color-muted);">Kelola kategori untuk pengelompokan buku</small>
            </div>
            <a href="{{ route('categories.create') }}" class="btn-ui btn-ui-primary">+ Tambah Kategori</a>
        </div>

        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn-ui btn-ui-secondary btn-ui-sm">Edit</a>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;"
                                          onsubmit="openDeleteModal(event, 'Kategori {{ addslashes($category->name) }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-ui btn-ui-danger btn-ui-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="padding:28px; text-align:center; color:var(--color-muted);">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
