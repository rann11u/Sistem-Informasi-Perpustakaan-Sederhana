@extends('layouts.app')

@section('title', 'Detail Anggota')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="margin:0;">Detail Profil Anggota</h2>
        <a href="{{ route('members.index') }}" style="color:#555; text-decoration:none;">← Kembali ke Daftar</a>
    </div>

    <div style="display:flex; flex-wrap:wrap; gap:28px; align-items:flex-start; margin-top:10px;">
        <!-- Foto Profil Anggota -->
        <div style="text-align:center; min-width:180px; padding:20px; background:#f9fafb; border-radius:12px; border:1px solid #e5e7eb;">
            @if ($member->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($member->photo))
                <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" style="width:130px; height:130px; border-radius:50%; object-fit:cover; border:3px solid #174a38; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); margin:0 auto 12px; display:block;">
            @else
                <div style="width:130px; height:130px; border-radius:50%; background:#e2e8f0; color:#475569; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:42px; border:3px solid #cbd5e1; margin:0 auto 12px;">
                    {{ strtoupper(substr($member->name, 0, 1)) }}
                </div>
            @endif
            <div style="font-weight:bold; font-size:16px; color:#1f2937;">{{ $member->name }}</div>
            <div style="font-size:13px; color:#6b7280; margin-top:4px;">ID: {{ $member->member_code }}</div>
        </div>

        <!-- Tabel Informasi Lengkap -->
        <div style="flex:1; min-width:280px;">
            <table style="font-size:14px; border-collapse:collapse; width:100%;">
                @php 
                    $tdStyle = "padding:12px 14px; border-bottom:1px solid #eee; color:#1f2937;"; 
                    $thStyle = "padding:12px 14px; border-bottom:1px solid #eee; background:#f9f9f9; font-weight:bold; width:160px; text-align:left; color:#4b5563;"; 
                @endphp
                <tr><th style="{{ $thStyle }}">Kode Anggota</th><td style="{{ $tdStyle }}"><strong>{{ $member->member_code }}</strong></td></tr>
                <tr><th style="{{ $thStyle }}">Nama Lengkap</th><td style="{{ $tdStyle }}">{{ $member->name }}</td></tr>
                <tr><th style="{{ $thStyle }}">Email</th><td style="{{ $tdStyle }}">{{ $member->email ?? '-' }}</td></tr>
                <tr><th style="{{ $thStyle }}">No. Telepon</th><td style="{{ $tdStyle }}">{{ $member->phone ?? '-' }}</td></tr>
                <tr><th style="{{ $thStyle }}">Alamat</th><td style="{{ $tdStyle }}">{{ $member->address ?? '-' }}</td></tr>
                <tr><th style="{{ $thStyle }}">Tanggal Bergabung</th><td style="{{ $tdStyle }}">{{ $member->joined_at ? $member->joined_at->format('d F Y') : '-' }}</td></tr>
            </table>

            <div style="margin-top:24px; display:flex; gap:10px;">
                <a href="{{ route('members.edit', $member->id) }}" style="background:#f9a825; color:white; padding:9px 18px; border-radius:6px; text-decoration:none; font-weight:600;">Edit Profil</a>
                <a href="{{ route('members.index') }}" style="background:#f3f4f6; color:#374151; padding:9px 18px; border-radius:6px; text-decoration:none; border:1px solid #d1d5db;">Kembali</a>
            </div>
        </div>
    </div>
@endsection
