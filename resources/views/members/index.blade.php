@extends('layouts.app')

@section('title', 'Daftar Anggota')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2 style="margin:0;">Daftar Anggota</h2>
        <a href="{{ route('members.create') }}" style="background:#174a38; color:white; padding:8px 16px; border-radius:6px; text-decoration:none;">+ Tambah Anggota</a>
    </div>

    <table style="width:100%; border-collapse:collapse; font-size:14px;">
        <thead>
            <tr style="background:#f0f0f0;">
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">#</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Kode</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Nama</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Email</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Telepon</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Bergabung</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($members as $member)
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:10px;">{{ $loop->iteration }}</td>
                    <td style="padding:10px;">{{ $member->member_code }}</td>
                    <td style="padding:10px;">{{ $member->name }}</td>
                    <td style="padding:10px;">{{ $member->email ?? '-' }}</td>
                    <td style="padding:10px;">{{ $member->phone ?? '-' }}</td>
                    <td style="padding:10px;">{{ $member->joined_at ? $member->joined_at->format('d/m/Y') : '-' }}</td>
                    <td style="padding:10px;">
                        <a href="{{ route('members.edit', $member->id) }}" style="color:#f9a825; margin-right:8px;">Edit</a>
                        <form action="{{ route('members.destroy', $member->id) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Hapus anggota ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="color:#c62828; background:none; border:none; cursor:pointer; padding:0;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="padding:20px; text-align:center; color:#888;">Belum ada anggota.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection

