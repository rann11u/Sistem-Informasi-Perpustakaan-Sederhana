@extends('layouts.app')

@section('title', 'Daftar Anggota')

@section('content')
    <div class="admin-card">
        <div class="page-header-action">
            <div>
                <h2>Daftar Anggota</h2>
                <small style="color:var(--color-muted);">Data seluruh member perpustakaan terdaftar</small>
            </div>
            <a href="{{ route('members.create') }}" class="btn-ui btn-ui-primary">+ Tambah Anggota</a>
        </div>

        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th style="width:55px;">Foto</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $member)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($member->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($member->photo))
                                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" 
                                         style="width:38px; height:38px; border-radius:50%; object-fit:cover; border:1px solid var(--card-border); display:block;">
                                @else
                                    <div style="width:38px; height:38px; border-radius:50%; background:var(--table-head-bg); color:var(--color-ink); display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:13px;">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td style="color:var(--color-muted); font-size:13px;">{{ $member->member_code }}</td>
                            <td><strong>{{ $member->name }}</strong></td>
                            <td style="color:var(--color-muted);">{{ $member->email ?? '-' }}</td>
                            <td style="color:var(--color-muted);">{{ $member->joined_at ? $member->joined_at->format('d/m/Y') : '-' }}</td>
                            <td>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <a href="{{ route('members.show', $member->id) }}" class="btn-ui btn-ui-secondary btn-ui-sm">Detail</a>
                                    <a href="{{ route('members.edit', $member->id) }}" class="btn-ui btn-ui-secondary btn-ui-sm">Edit</a>
                                    <form action="{{ route('members.destroy', $member->id) }}" method="POST" style="display:inline;"
                                          onsubmit="openDeleteModal(event, 'Anggota {{ addslashes($member->name) }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-ui btn-ui-danger btn-ui-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding:28px; text-align:center; color:var(--color-muted);">Belum ada anggota.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
