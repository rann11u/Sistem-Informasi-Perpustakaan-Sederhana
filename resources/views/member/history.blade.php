@extends('layouts.member')

@section('title', 'Riwayat Peminjaman — Portal Anggota')
@section('main_class', 'member-main--narrow')

@section('content')
    <div class="page-head">
        <div>
            <h1 class="page-title">Riwayat Peminjaman Saya</h1>
            <p class="page-subtitle">Daftar seluruh transaksi peminjaman dan booking buku Anda.</p>
        </div>
    </div>

    {{-- TAB FILTER --}}
    <div class="tabs" role="tablist" aria-label="Filter status peminjaman">
        <a href="{{ route('member.history') }}"
           class="tab {{ !request('status') ? 'tab--active' : '' }}">
            Semua ({{ $countBooked + $countBorrowed + $countReturned }})
        </a>
        <a href="{{ route('member.history', ['status' => 'booked']) }}"
           class="tab {{ request('status') === 'booked' ? 'tab--active-apricot' : '' }}">
            Menunggu / Booking ({{ $countBooked }})
        </a>
        <a href="{{ route('member.history', ['status' => 'borrowed']) }}"
           class="tab {{ request('status') === 'borrowed' ? 'tab--active' : '' }}">
            Sedang Dipinjam ({{ $countBorrowed }})
        </a>
        <a href="{{ route('member.history', ['status' => 'returned']) }}"
           class="tab {{ request('status') === 'returned' ? 'tab--active-success' : '' }}">
            Selesai ({{ $countReturned }})
        </a>
    </div>

    {{-- DAFTAR TRANSAKSI --}}
    @if($borrowings->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
            <h3>Belum ada transaksi</h3>
            <p>Anda belum pernah melakukan peminjaman atau booking buku.</p>
        </div>
    @else
        <div class="card-list">
            @foreach($borrowings as $borrowing)
                @php
                    $isOverdue = $borrowing->status === 'borrowed'
                        && $borrowing->due_date
                        && $borrowing->due_date->isPast();
                    $isDeadlineNear = $borrowing->status === 'booked'
                        && $borrowing->pickup_deadline
                        && $borrowing->pickup_deadline->diffInHours(now()) < 3
                        && $borrowing->pickup_deadline->isFuture();
                @endphp

                <article class="txn-card">
                    {{-- Header kartu --}}
                    <div class="txn-header">
                        <div>
                            <span class="txn-id">#TRX-{{ str_pad($borrowing->id, 5, '0', STR_PAD_LEFT) }}</span>
                            @if($isOverdue)
                                <span class="badge badge-overdue">Terlambat</span>
                            @endif
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <span class="txn-date">
                                {{ $borrowing->created_at ? $borrowing->created_at->format('d M Y, H:i') : '-' }}
                            </span>
                            @if($borrowing->status === 'booked')
                                <span class="badge badge-booked">Booking Online</span>
                            @elseif($borrowing->status === 'borrowed')
                                <span class="badge badge-borrowed">Sedang Dipinjam</span>
                            @elseif($borrowing->status === 'returned')
                                <span class="badge badge-returned">Selesai</span>
                            @elseif($borrowing->status === 'cancelled')
                                <span class="badge badge-cancelled">Dibatalkan</span>
                            @endif
                        </div>
                    </div>

                    {{-- Daftar Buku --}}
                    <div class="txn-body">
                        <div class="books-list">
                            @foreach($borrowing->details as $detail)
                                <div class="book-row">
                                    {{-- Cover buku --}}
                                    @if($detail->book && $detail->book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($detail->book->cover_image))
                                        <img class="book-cover" src="{{ asset('storage/' . $detail->book->cover_image) }}" alt="{{ $detail->book->title }}">
                                    @else
                                        <div class="book-cover-placeholder" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                                        </div>
                                    @endif
                                    <div class="book-info">
                                        <div class="book-title">{{ $detail->book->title ?? 'Judul tidak tersedia' }}</div>
                                        <div class="book-meta--muted">
                                            {{ $detail->book->author ?? '-' }}
                                            @if($detail->book?->category)
                                                — {{ $detail->book->category->name }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="book-qty">{{ $detail->quantity }} buku</div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Info tanggal/deadline --}}
                        <div class="txn-dates">
                            @if($borrowing->status === 'booked')
                                <div class="txn-date-item">
                                    <label>Waktu Booking</label>
                                    <span class="value">{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d M Y') : '-' }}</span>
                                </div>
                                <div class="txn-date-item">
                                    <label>Batas Ambil</label>
                                    <span class="value {{ $isDeadlineNear ? 'value-warning' : 'value-warning' }}">
                                        {{ $borrowing->pickup_deadline ? $borrowing->pickup_deadline->format('d M Y, H:i') . ' WIB' : '-' }}
                                    </span>
                                </div>
                            @else
                                <div class="txn-date-item">
                                    <label>Tanggal Pinjam</label>
                                    <span class="value">{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d M Y') : '-' }}</span>
                                </div>
                                @if($borrowing->status !== 'cancelled')
                                    <div class="txn-date-item">
                                        <label>Jatuh Tempo</label>
                                        <span class="value {{ $isOverdue ? 'value-danger' : '' }}">
                                            {{ $borrowing->due_date ? $borrowing->due_date->format('d M Y') : '-' }}
                                        </span>
                                    </div>
                                @endif
                                @if($borrowing->status === 'returned')
                                    <div class="txn-date-item">
                                        <label>Tanggal Kembali</label>
                                        <span class="value value-ok">{{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : '-' }}</span>
                                    </div>
                                @endif
                                @if($borrowing->user)
                                    <div class="txn-date-item">
                                        <label>Petugas</label>
                                        <span class="value">{{ $borrowing->user->name }}</span>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Footer: Tombol Download Bukti --}}
                    @if(in_array($borrowing->status, ['booked', 'borrowed', 'returned']))
                        <div class="txn-footer">
                            <a href="{{ route('member.history.invoice', $borrowing->id) }}"
                               target="_blank" class="btn-primary btn-invoice">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 2l5 5h-5V4zm-5 14v-2h8v2H8zm0-4v-2h8v2H8zm0-4V8h3v2H8z"/></svg>
                                Download Bukti Pinjam (PDF)
                            </a>
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
@endsection
