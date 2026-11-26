<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman - Portal Anggota</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f6f9;
            color: #263238;
            min-height: 100vh;
        }

        /* ======= HEADER ======= */
        .header {
            background-color: #174a38;
            color: white;
            padding: 14px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        .header h1 { font-size: 19px; }
        .user-nav { display: flex; align-items: center; gap: 14px; }
        .nav-link {
            color: #dcebe3;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s;
        }
        .nav-link:hover, .nav-link.active { color: white; font-weight: bold; }

        .btn-profile-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            border: 2px solid #e5f1eb;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            background-color: #265a48;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s;
        }
        .btn-profile-avatar:hover { transform: scale(1.05); }
        .btn-profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .btn-profile-avatar svg { width: 18px; height: 18px; fill: #dcebe3; }

        .btn-logout {
            background-color: #dc2626;
            color: white; border: none;
            padding: 7px 14px; border-radius: 6px;
            cursor: pointer; font-size: 13px; font-weight: bold;
        }

        /* ======= CONTAINER ======= */
        .container {
            max-width: 960px;
            margin: 32px auto;
            padding: 0 20px;
        }

        .page-title {
            font-size: 22px;
            font-weight: bold;
            color: #174a38;
            margin-bottom: 6px;
        }
        .page-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 24px;
        }

        /* ======= TAB FILTER ======= */
        .tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 12px;
        }
        .tab {
            padding: 7px 16px;
            border-radius: 20px;
            font-size: 13px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.15s;
        }

        /* ======= ALERT ======= */
        .alert-success {
            background-color: #dcfce7;
            border: 1px solid #86efac;
            color: #15803d;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }
        .alert-error {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        /* ======= KARTU TRANSAKSI ======= */
        .card-list { display: flex; flex-direction: column; gap: 14px; }

        .txn-card {
            background: white;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .txn-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            border-bottom: 1px solid #f3f4f6;
            flex-wrap: wrap;
            gap: 8px;
        }
        .txn-id {
            font-size: 12px;
            color: #6b7280;
            font-family: monospace;
        }
        .txn-date { font-size: 12px; color: #9ca3af; }

        /* Badge Status */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-booked    { background:#fef3c7; color:#92400e; }
        .badge-borrowed  { background:#e0f2fe; color:#0369a1; }
        .badge-returned  { background:#dcfce7; color:#15803d; }
        .badge-cancelled { background:#f3f4f6; color:#6b7280; }

        .txn-body { padding: 14px 16px; }

        .books-list { margin-bottom: 12px; }
        .book-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            border-bottom: 1px dashed #f3f4f6;
        }
        .book-row:last-child { border-bottom: none; }
        .book-cover {
            width: 36px; height: 50px;
            object-fit: cover;
            border-radius: 3px;
            border: 1px solid #e5e7eb;
            flex-shrink: 0;
        }
        .book-cover-placeholder {
            width: 36px; height: 50px;
            background: #e2e8f0;
            border-radius: 3px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .book-cover-placeholder svg { width: 16px; height: 16px; color: #94a3b8; }
        .book-info { flex: 1; min-width: 0; }
        .book-title {
            font-weight: bold;
            font-size: 14px;
            color: #1f2937;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .book-meta { font-size: 12px; color: #6b7280; margin-top: 2px; }
        .book-qty {
            font-size: 13px;
            color: #374151;
            font-weight: 600;
            flex-shrink: 0;
        }

        /* Info batas/tanggal */
        .txn-dates {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 13px;
            color: #374151;
            padding-top: 10px;
            border-top: 1px solid #f3f4f6;
        }
        .txn-date-item label {
            display: block;
            font-size: 11px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .txn-date-item .value { font-weight: 600; }
        .value-warning { color: #d97706 !important; }
        .value-danger  { color: #dc2626 !important; }
        .value-ok      { color: #16a34a !important; }

        /* Tombol aksi bawah kartu */
        .txn-footer {
            padding: 10px 16px;
            background: #fafafa;
            border-top: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn-invoice {
            background: #174a38;
            color: white;
            padding: 7px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }
        .btn-invoice:hover { background: #103629; }
        .btn-invoice svg { width: 15px; height: 15px; fill: white; }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }
        .empty-state svg { width: 56px; height: 56px; fill: #d1d5db; margin-bottom: 14px; }
        .empty-state h3 { font-size: 16px; color: #6b7280; margin-bottom: 6px; }
        .empty-state p  { font-size: 14px; }

        .footer-credit {
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            margin: 36px 0 20px;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Sistem Informasi Perpustakaan</h1>
        <div class="user-nav">
            <a href="{{ route('member.books') }}" class="nav-link">Katalog Buku</a>
            <a href="{{ route('member.history') }}" class="nav-link active">Riwayat Pinjam</a>

            <a href="{{ route('member.profile') }}" class="btn-profile-avatar" title="Profil Saya">
                @if($member && $member->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($member->photo))
                    <img src="{{ asset('storage/' . $member->photo) }}" alt="Foto">
                @else
                    <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                @endif
            </a>

            <span style="font-size:14px; color:white;">Halo, <strong>{{ $user->name }}</strong></span>

            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-logout">Keluar</button>
            </form>
        </div>
    </header>

    <main class="container">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <h2 class="page-title">Riwayat Peminjaman Saya</h2>
        <p class="page-subtitle">Daftar seluruh transaksi peminjaman dan booking buku Anda.</p>

        {{-- TAB FILTER --}}
        <div class="tabs">
            <a href="{{ route('member.history') }}"
               class="tab {{ !request('status') ? 'badge-borrowed' : '' }}"
               style="{{ !request('status') ? '' : 'background:#f3f4f6; color:#4b5563;' }}">
                Semua ({{ $countBooked + $countBorrowed + $countReturned }})
            </a>
            <a href="{{ route('member.history', ['status' => 'booked']) }}"
               class="tab badge-booked {{ request('status') === 'booked' ? '' : '' }}"
               style="{{ request('status') === 'booked' ? 'outline:2px solid #d97706;' : 'opacity:0.7;' }}">
                ⏳ Menunggu / Booking ({{ $countBooked }})
            </a>
            <a href="{{ route('member.history', ['status' => 'borrowed']) }}"
               class="tab badge-borrowed"
               style="{{ request('status') === 'borrowed' ? 'outline:2px solid #0369a1;' : 'opacity:0.7;' }}">
                📖 Sedang Dipinjam ({{ $countBorrowed }})
            </a>
            <a href="{{ route('member.history', ['status' => 'returned']) }}"
               class="tab badge-returned"
               style="{{ request('status') === 'returned' ? 'outline:2px solid #15803d;' : 'opacity:0.7;' }}">
                ✅ Selesai ({{ $countReturned }})
            </a>
        </div>

        {{-- DAFTAR TRANSAKSI --}}
        @if($borrowings->isEmpty())
            <div class="empty-state">
                <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
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

                    <div class="txn-card">
                        {{-- Header kartu --}}
                        <div class="txn-header">
                            <div>
                                <span class="txn-id">#TRX-{{ str_pad($borrowing->id, 5, '0', STR_PAD_LEFT) }}</span>
                                @if($isOverdue)
                                    <span class="badge" style="background:#fee2e2;color:#dc2626;margin-left:8px;">⚠ Terlambat!</span>
                                @endif
                            </div>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span class="txn-date">
                                    {{ $borrowing->created_at ? $borrowing->created_at->format('d M Y, H:i') : '-' }}
                                </span>
                                @if($borrowing->status === 'booked')
                                    <span class="badge badge-booked">⏳ Booking Online</span>
                                @elseif($borrowing->status === 'borrowed')
                                    <span class="badge badge-borrowed">📖 Sedang Dipinjam</span>
                                @elseif($borrowing->status === 'returned')
                                    <span class="badge badge-returned">✅ Selesai</span>
                                @elseif($borrowing->status === 'cancelled')
                                    <span class="badge badge-cancelled">❌ Dibatalkan</span>
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
                                            <div class="book-cover-placeholder">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                                            </div>
                                        @endif
                                        <div class="book-info">
                                            <div class="book-title">{{ $detail->book->title ?? 'Judul tidak tersedia' }}</div>
                                            <div class="book-meta">
                                                {{ $detail->book->author ?? '-' }}
                                                @if($detail->book?->category)
                                                    · {{ $detail->book->category->name }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="book-qty">{{ $detail->quantity }} eks</div>
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

                        {{-- Footer: Tombol Download Bukti & Perpanjangan --}}
                        @if(in_array($borrowing->status, ['booked', 'borrowed', 'returned']))
                            <div class="txn-footer" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                                @if($borrowing->status === 'borrowed')
                                    @if(!$borrowing->is_extended && !$isOverdue)
                                        <form action="{{ route('member.history.extend', $borrowing->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Perpanjang masa pinjam selama 3 hari? (Hanya dapat dilakukan 1 kali)')">
                                            @csrf
                                            <button type="submit" style="background:#0284c7; color:white; border:none; padding:8px 14px; border-radius:6px; font-size:13px; font-weight:bold; cursor:pointer;">
                                                Perpanjang Pinjaman (+3 Hari)
                                            </button>
                                        </form>
                                    @elseif($borrowing->is_extended)
                                        <span style="background:#e0f2fe; color:#0369a1; padding:6px 12px; border-radius:6px; font-size:12px; font-weight:bold;">
                                            Sudah Diperpanjang (+3 Hari)
                                        </span>
                                    @elseif($isOverdue)
                                        <span style="background:#fde8e7; color:#a12222; padding:6px 12px; border-radius:6px; font-size:12px; font-weight:bold;">
                                            Terlambat (Tidak Bisa Perpanjang)
                                        </span>
                                    @endif
                                @endif

                                <a href="{{ route('member.history.invoice', $borrowing->id) }}"
                                   target="_blank" class="btn-invoice">
                                    <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 2l5 5h-5V4zm-5 14v-2h8v2H8zm0-4v-2h8v2H8zm0-4V8h3v2H8z"/></svg>
                                    Download Bukti Pinjam (PDF)
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <p class="footer-credit">© {{ date('Y') }} Sistem Informasi Perpustakaan Sederhana</p>
    </main>
</body>
</html>
