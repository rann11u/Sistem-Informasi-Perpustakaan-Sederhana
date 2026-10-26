@extends('layouts.app')

@section('title', 'Daftar Pengguna')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2 style="margin:0;">Daftar Pengguna</h2>
        <a href="{{ route('users.create') }}" style="background:#174a38; color:white; padding:8px 16px; border-radius:6px; text-decoration:none;">+ Tambah Pengguna</a>
    </div>

    <table style="width:100%; border-collapse:collapse; font-size:14px;">
        <thead>
            <tr style="background:#f0f0f0;">
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">#</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Nama</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Email</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Role</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:10px;">{{ $loop->iteration }}</td>
                    <td style="padding:10px;">{{ $user->name }}</td>
                    <td style="padding:10px;">{{ $user->email }}</td>
                    <td style="padding:10px;">
                        <span style="background:{{ $user->role === 'admin' ? '#e8f5e9' : '#e3f2fd' }}; color:{{ $user->role === 'admin' ? '#174a38' : '#1565c0' }}; padding:3px 10px; border-radius:12px; font-size:12px;">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td style="padding:10px;">
                        <a href="{{ route('users.edit', $user->id) }}" style="color:#f9a825; margin-right:8px;">Edit</a>
                        @if (auth()->id() !== $user->id)
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;"
                                  onsubmit="return confirm('Hapus pengguna ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="color:#c62828; background:none; border:none; cursor:pointer; padding:0;">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="padding:20px; text-align:center; color:#888;">Belum ada pengguna.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection

