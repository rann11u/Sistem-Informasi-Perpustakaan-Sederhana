@extends('layouts.app')

@section('title', 'Daftar Kategori')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2 style="margin:0;">Daftar Kategori</h2>
        <a href="{{ route('categories.create') }}" style="background:#174a38; color:white; padding:8px 16px; border-radius:6px; text-decoration:none;">+ Tambah Kategori</a>
    </div>

    <table style="width:100%; border-collapse:collapse; font-size:14px;">
        <thead>
            <tr style="background:#f0f0f0;">
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">#</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Nama</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Deskripsi</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:10px;">{{ $loop->iteration }}</td>
                    <td style="padding:10px;">{{ $category->name }}</td>
                    <td style="padding:10px;">{{ $category->description ?? '-' }}</td>
                    <td style="padding:10px;">
                        <a href="{{ route('categories.edit', $category->id) }}" style="color:#f9a825; margin-right:8px;">Edit</a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;"
                              onsubmit="openDeleteModal(event, 'Kategori {{ addslashes($category->name) }}')">
                            @csrf @method('DELETE')
                            <button type="submit" style="color:#c62828; background:none; border:none; cursor:pointer; padding:0;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" style="padding:20px; text-align:center; color:#888;">Belum ada kategori.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
