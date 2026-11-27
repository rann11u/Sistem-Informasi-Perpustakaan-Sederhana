@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
    <div class="admin-card">
        <div class="page-header-action">
            <div>
                <h2>Kelola Pengguna</h2>
                <small style="color:var(--color-muted);">Daftar akun admin dan petugas sistem perpustakaan</small>
            </div>
            <a href="{{ route('users.create') }}" class="btn-ui btn-ui-primary">+ Tambah Pengguna</a>
        </div>

        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td style="color:var(--color-muted);">{{ $user->email }}</td>
                            <td>
                                <span class="status-pill {{ $user->role === 'admin' ? 'pill-booked' : 'pill-borrowed' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn-ui btn-ui-secondary btn-ui-sm">Edit</a>
                                    @if (auth()->id() !== $user->id)
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;"
                                              onsubmit="openDeleteModal(event, 'Pengguna {{ addslashes($user->name) }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-ui btn-ui-danger btn-ui-sm">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding:28px; text-align:center; color:var(--color-muted);">Belum ada pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
