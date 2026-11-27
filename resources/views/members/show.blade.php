@extends('layouts.app')

@section('title', 'Detail Anggota')

@section('content')
    <div class="admin-card">
        <div class="page-header-action">
            <h2>Detail Profil Anggota</h2>
            <a href="{{ route('members.index') }}" class="btn-ui btn-ui-secondary">← Kembali ke Daftar</a>
        </div>

        <div style="display:flex; flex-wrap:wrap; gap:28px; align-items:flex-start; margin-top:10px;">
            <!-- Foto Profil Anggota -->
            <div style="text-align:center; min-width:180px; padding:20px; background:var(--table-head-bg); border-radius:12px; border:1px solid var(--card-border);">
                @if ($member->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($member->photo))
                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" style="width:130px; height:130px; border-radius:50%; object-fit:cover; border:3px solid var(--color-accent); box-shadow:0 4px 10px rgba(0,0,0,0.1); margin:0 auto 12px; display:block;">
                @else
                    <div style="width:130px; height:130px; border-radius:50%; background:color-mix(in oklab, var(--color-accent) 20%, transparent); color:var(--color-accent); display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:42px; border:3px solid var(--card-border); margin:0 auto 12px;">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                @endif
                <div style="font-weight:700; font-size:16px;">{{ $member->name }}</div>
                <div style="font-size:13px; color:var(--color-muted); margin-top:4px;">ID: {{ $member->member_code }}</div>
            </div>

            <!-- Tabel Informasi Lengkap -->
            <div style="flex:1; min-width:280px;">
                <table class="admin-table">
                    <tr><th style="width:160px;">Kode Anggota</th><td><strong>{{ $member->member_code }}</strong></td></tr>
                    <tr><th>Nama Lengkap</th><td>{{ $member->name }}</td></tr>
                    <tr><th>Email</th><td>{{ $member->email ?? '-' }}</td></tr>
                    <tr><th>Alamat Domisili</th><td>{{ $member->address ?? '-' }}</td></tr>
                    <tr><th>Tanggal Bergabung</th><td>{{ $member->joined_at ? $member->joined_at->format('d F Y') : '-' }}</td></tr>
                </table>

                <div style="margin-top:24px; display:flex; gap:10px;">
                    <a href="{{ route('members.edit', $member->id) }}" class="btn-ui btn-ui-primary">Edit Profil</a>
                    <a href="{{ route('members.index') }}" class="btn-ui btn-ui-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
