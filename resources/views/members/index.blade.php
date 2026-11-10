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
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Foto</th>
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
                    <td style="padding:10px; vertical-align:middle;">{{ $loop->iteration }}</td>
                    <td style="padding:10px; vertical-align:middle;">
                        @if ($member->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($member->photo))
                            <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" style="width:40px; height:40px; border-radius:50%; object-fit:cover; border:1px solid #dce3df; display:block;">
                        @else
                            <div style="width:40px; height:40px; border-radius:50%; background:#e2e8f0; color:#475569; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:14px;">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                        @endif
                    </td>
                    <td style="padding:10px; vertical-align:middle;">{{ $member->member_code }}</td>
                    <td style="padding:10px; vertical-align:middle;"><strong>{{ $member->name }}</strong></td>
                    <td style="padding:10px; vertical-align:middle;">{{ $member->email ?? '-' }}</td>
                    <td style="padding:10px; vertical-align:middle;">{{ $member->phone ?? '-' }}</td>
                    <td style="padding:10px; vertical-align:middle;">{{ $member->joined_at ? $member->joined_at->format('d/m/Y') : '-' }}</td>
                    <td style="padding:10px; vertical-align:middle;">
                        <a href="{{ route('members.show', $member->id) }}" style="color:#1565c0; margin-right:8px; text-decoration:none; font-weight:600;">Detail</a>
                        <a href="{{ route('members.edit', $member->id) }}" style="color:#f9a825; margin-right:8px; text-decoration:none;">Edit</a>
                        <form action="{{ route('members.destroy', $member->id) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Hapus anggota ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="color:#c62828; background:none; border:none; cursor:pointer; padding:0;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="padding:20px; text-align:center; color:#888;">Belum ada anggota.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
