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

    {{-- Buku yang Sedang Dipinjam / Dibooking Saat Ini --}}
    <div class="admin-card">
        <div class="page-header-action">
            <div>
                <h2>Buku yang Sedang Dipinjam Saat Ini</h2>
                <small style="color:var(--color-muted);">Daftar peminjaman aktif dan booking online yang belum selesai</small>
            </div>
            <span class="status-pill {{ $totalActiveBooks >= 5 ? 'pill-booked' : 'pill-borrowed' }}">
                Kuota Aktif: {{ $totalActiveBooks }} / 5 buku
            </span>
        </div>

        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Buku</th>
                        <th>Status</th>
                        <th>Tgl Pinjam / Batas Ambil</th>
                        <th>Jatuh Tempo</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activeBorrowings as $borrowing)
                        @php
                            $isBooked  = $borrowing->status === 'booked';
                            $isExpired = $isBooked && $borrowing->isExpired();
                            $isOverdue = $borrowing->status === 'borrowed' && $borrowing->due_date && $borrowing->due_date->isPast();
                        @endphp
                        <tr class="{{ $isExpired ? 'row-expired' : '' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @foreach ($borrowing->details as $detail)
                                    <div style="margin-bottom:4px;">
                                        <strong>{{ $detail->book->title ?? 'Buku tidak tersedia' }}</strong>
                                        <span style="color:var(--color-muted); font-size:12px;">
                                            ({{ $detail->quantity }} eks{{ $detail->book?->category ? ' • ' . $detail->book->category->name : '' }})
                                        </span>
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                @if ($isBooked)
                                    <span class="status-pill pill-booked">Booking Online</span>
                                    @if ($isExpired)
                                        <div><span class="mini-pill mini-pill-danger">Kedaluwarsa</span></div>
                                    @endif
                                @else
                                    <span class="status-pill pill-borrowed">Sedang Dipinjam</span>
                                @endif
                            </td>
                            <td>
                                @if ($isBooked)
                                    <span style="color:{{ $isExpired ? 'var(--color-error)' : 'var(--badge-booked-color)' }}; font-weight:600; font-size:13px;">
                                        Batas: {{ $borrowing->pickup_deadline ? $borrowing->pickup_deadline->format('d/m/Y H:i') : '-' }} WIB
                                    </span>
                                @else
                                    <span style="color:var(--color-muted);">{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y') : '-' }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($isBooked)
                                    <span style="color:var(--color-muted); font-style:italic; font-size:12px;">Dihitung saat diambil</span>
                                @else
                                    <span style="color:{{ $isOverdue ? 'var(--color-error)' : 'var(--color-muted)' }}; font-weight:{{ $isOverdue ? '600' : '400' }};">
                                        {{ $borrowing->due_date ? $borrowing->due_date->format('d/m/Y') : '-' }}
                                    </span>
                                    @if ($isOverdue)
                                        <div><span class="mini-pill mini-pill-danger">Terlambat</span></div>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                                    <a href="{{ route('borrowings.show', $borrowing->id) }}" class="btn-ui btn-ui-secondary btn-ui-sm">Detail</a>
                                    @if ($borrowing->status === 'borrowed')
                                        <a href="{{ route('borrowings.edit', $borrowing->id) }}" class="btn-ui btn-ui-primary btn-ui-sm">Kembalikan</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:24px; text-align:center; color:var(--color-muted);">
                                Anggota ini tidak memiliki buku yang sedang dipinjam atau dibooking saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
