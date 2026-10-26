@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2 style="margin-top:0">Dashboard</h2>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:16px; margin-bottom:24px;">
        <div style="background:#e8f5e9; border-left:4px solid #174a38; padding:20px; border-radius:8px;">
            <div style="font-size:28px; font-weight:bold; color:#174a38;">{{ $totalBooks }}</div>
            <div style="color:#555; margin-top:6px;">Total Stok Buku</div>
        </div>
        <div style="background:#e3f2fd; border-left:4px solid #1565c0; padding:20px; border-radius:8px;">
            <div style="font-size:28px; font-weight:bold; color:#1565c0;">{{ $totalMembers }}</div>
            <div style="color:#555; margin-top:6px;">Total Anggota</div>
        </div>
        <div style="background:#fff8e1; border-left:4px solid #f9a825; padding:20px; border-radius:8px;">
            <div style="font-size:28px; font-weight:bold; color:#f9a825;">{{ $totalBorrowed }}</div>
            <div style="color:#555; margin-top:6px;">Sedang Dipinjam</div>
        </div>
        <div style="background:#f3e5f5; border-left:4px solid #6a1b9a; padding:20px; border-radius:8px;">
            <div style="font-size:28px; font-weight:bold; color:#6a1b9a;">{{ $totalReturned }}</div>
            <div style="color:#555; margin-top:6px;">Sudah Dikembalikan</div>
        </div>
    </div>

    <p style="color:#666;">Selamat datang di Sistem Informasi Perpustakaan. Gunakan menu navigasi di atas untuk mengelola data.</p>
@endsection
