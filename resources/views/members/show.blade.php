@extends('layouts.app')

@section('title', 'Detail Anggota')

@section('content')
    <h2 style="margin-top:0;">Detail Anggota</h2>

    <table style="font-size:14px; border-collapse:collapse; width:100%; max-width:520px;">
        @php $tdStyle = "padding:10px 14px; border-bottom:1px solid #eee;"; $thStyle = "padding:10px 14px; border-bottom:1px solid #eee; background:#f9f9f9; font-weight:bold; width:160px; text-align:left;"; @endphp
        <tr><th style="{{ $thStyle }}">Kode Anggota</th><td style="{{ $tdStyle }}">{{ $member->member_code }}</td></tr>
        <tr><th style="{{ $thStyle }}">Nama Lengkap</th><td style="{{ $tdStyle }}">{{ $member->name }}</td></tr>
        <tr><th style="{{ $thStyle }}">Email</th><td style="{{ $tdStyle }}">{{ $member->email ?? '-' }}</td></tr>
        <tr><th style="{{ $thStyle }}">No. Telepon</th><td style="{{ $tdStyle }}">{{ $member->phone ?? '-' }}</td></tr>
        <tr><th style="{{ $thStyle }}">Alamat</th><td style="{{ $tdStyle }}">{{ $member->address ?? '-' }}</td></tr>
        <tr><th style="{{ $thStyle }}">Tanggal Bergabung</th><td style="{{ $tdStyle }}">{{ $member->joined_at ? $member->joined_at->format('d/m/Y') : '-' }}</td></tr>
    </table>

    <div style="margin-top:20px;">
        <a href="{{ route('members.edit', $member->id) }}" style="background:#f9a825; color:white; padding:8px 16px; border-radius:6px; text-decoration:none; margin-right:8px;">Edit</a>
        <a href="{{ route('members.index') }}" style="color:#555;">← Kembali</a>
    </div>
@endsection

