@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- ========================================================
         1. RINGKASAN STATISTIK (5 KARTU: KARTU DENDA DIHAPUS)
         ======================================================== --}}
    <div class="stat-grid-5">
        {{-- Total Koleksi Buku --}}
        <div class="stat-box stat-books">
            <div class="stat-box-top">
                <span class="stat-desc">Total Koleksi Buku</span>
                <span class="stat-badge">Katalog</span>
            </div>
            <div class="stat-number">{{ $totalBookTitles }} <small class="stat-unit">Judul</small></div>
            <div class="stat-sub">{{ $totalBookStock }} total buku fisik</div>
        </div>

        {{-- Total Anggota Aktif --}}
        <div class="stat-box stat-members">
            <div class="stat-box-top">
                <span class="stat-desc">Anggota Aktif</span>
                <span class="stat-badge stat-badge-blue">Member</span>
            </div>
            <div class="stat-number">{{ $totalMembers }}</div>
            <div class="stat-sub">Anggota terdaftar</div>
        </div>

        {{-- Buku Sedang Dipinjam --}}
        <div class="stat-box stat-borrowed">
            <div class="stat-box-top">
                <span class="stat-desc">Sedang Dipinjam</span>
                <span class="stat-badge stat-badge-amber">Sirkulasi</span>
            </div>
            <div class="stat-number">{{ $totalBorrowed }} <small class="stat-unit">Buku</small></div>
            <div class="stat-sub">Buku sedang dipinjam</div>
        </div>

        {{-- Buku Terlambat --}}
        <div class="stat-box stat-overdue">
            <div class="stat-box-top">
                <span class="stat-desc">Buku Terlambat</span>
                <span class="stat-badge stat-badge-red">Perhatian</span>
            </div>
            <div class="stat-number text-danger">{{ $totalOverdue }} <small class="stat-unit">Peminjaman</small></div>
            <div class="stat-sub">Melewati jatuh tempo</div>
        </div>

        {{-- Kunjungan Hari Ini --}}
        <div class="stat-box stat-visits">
            <div class="stat-box-top">
                <span class="stat-desc">Kunjungan Hari Ini</span>
                <span class="stat-badge stat-badge-green">Aktivitas</span>
            </div>
            <div class="stat-number text-success">{{ $todayVisits }} <small class="stat-unit">Orang</small></div>
            <div class="stat-sub">Aktivitas perpustakaan</div>
        </div>
    </div>

    {{-- ========================================================
         3. RESERVASI & 4. DAFTAR PEMINJAMAN
         ======================================================== --}}
    <div class="dashboard-row-2col">
        {{-- 3. Reservasi / Antrean Booking Online Menunggu --}}
        <div class="admin-card">
            <div class="page-header-action">
                <div>
                    <h2>Reservasi Buku Menunggu</h2>
                    <small style="color:var(--color-muted);">Booking online yang belum diambil anggota</small>
                </div>
                <a href="{{ route('borrowings.index', ['status' => 'booked']) }}" class="btn-ui btn-ui-secondary btn-ui-sm">Lihat Semua</a>
            </div>

            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Batas Ambil</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($waitingBookings as $wb)
                            <tr>
                                <td>
                                    <strong>{{ $wb->member->name ?? '-' }}</strong><br>
                                    <small style="color:var(--color-muted);">{{ $wb->member->member_code ?? '' }}</small>
                                </td>
                                <td>{{ $wb->details->first()?->book?->title ?? '-' }}</td>
                                <td>
                                    <span style="color:var(--badge-booked-color); font-weight:600; font-size:12px;">
                                        {{ $wb->pickup_deadline ? $wb->pickup_deadline->format('d/m H:i') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('borrowings.confirm-pickup', $wb->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-ui btn-ui-success btn-ui-sm">Serahkan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align:center; padding:18px; color:var(--color-muted);">Tidak ada reservasi menunggu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 4. Daftar Peminjaman Terbaru --}}
        <div class="admin-card">
            <div class="page-header-action">
                <div>
                    <h2>Daftar Peminjaman Terbaru</h2>
                    <small style="color:var(--color-muted);">Transaksi sirkulasi terkini</small>
                </div>
                <a href="{{ route('borrowings.index') }}" class="btn-ui btn-ui-secondary btn-ui-sm">Lihat Semua</a>
            </div>

            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentBorrowings as $rb)
                            <tr>
                                <td><strong>{{ $rb->member->name ?? '-' }}</strong></td>
                                <td>{{ $rb->details->first()?->book?->title ?? '-' }}</td>
                                <td>{{ $rb->borrow_date ? $rb->borrow_date->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if ($rb->status === 'booked')
                                        <span class="status-pill pill-booked">Booking</span>
                                    @elseif ($rb->status === 'borrowed')
                                        <span class="status-pill pill-borrowed">Dipinjam</span>
                                    @elseif ($rb->status === 'returned')
                                        <span class="status-pill pill-returned">Kembali</span>
                                    @else
                                        <span class="status-pill pill-cancelled">Batal</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align:center; padding:18px; color:var(--color-muted);">Belum ada data peminjaman.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ========================================================
         5. KOLEKSI BUKU (BUKU POPULER, BUKU TERBARU, STOK KRITIS)
         ======================================================== --}}
    <div class="dashboard-row-3col">
        {{-- Buku Populer --}}
        <div class="admin-card">
            <div class="page-header-action">
                <div>
                    <h2>Buku Populer</h2>
                    <small style="color:var(--color-muted);">Paling sering dipinjam</small>
                </div>
            </div>
            <ul class="dashboard-list">
                @forelse ($popularBooks as $pb)
                    <li class="dashboard-list-item">
                        <div class="list-item-main">
                            <span class="list-title">{{ $pb->title }}</span>
                            <span class="list-meta">{{ $pb->author }} • {{ $pb->category->name ?? '-' }}</span>
                        </div>
                        <span class="list-badge-count">{{ $pb->borrowing_details_count }}x dipinjam</span>
                    </li>
                @empty
                    <li style="color:var(--color-muted); padding:10px 0;">Belum ada data buku populer.</li>
                @endforelse
            </ul>
        </div>

        {{-- Buku Terbaru --}}
        <div class="admin-card">
            <div class="page-header-action">
                <div>
                    <h2>Buku Terbaru</h2>
                    <small style="color:var(--color-muted);">Koleksi buku teranyar</small>
                </div>
                <a href="{{ route('books.create') }}" class="btn-ui btn-ui-secondary btn-ui-sm">+ Baru</a>
            </div>
            <ul class="dashboard-list">
                @forelse ($newBooks as $nb)
                    <li class="dashboard-list-item">
                        <div class="list-item-main">
                            <span class="list-title">{{ $nb->title }}</span>
                            <span class="list-meta">Stok: {{ $nb->stock }} buku • Tahun: {{ $nb->publication_year ?? '-' }}</span>
                        </div>
                        <span class="status-pill pill-borrowed" style="font-size:11px;">Baru</span>
                    </li>
                @empty
                    <li style="color:var(--color-muted); padding:10px 0;">Belum ada koleksi buku baru.</li>
                @endforelse
            </ul>
        </div>

        {{-- Stok Kritis & Perlu Restock --}}
        <div class="admin-card">
            <div class="page-header-action">
                <div>
                    <h2>Stok Kritis & Perlu Restock</h2>
                    <small style="color:var(--color-muted);">Stok 0 atau sisa sedikit</small>
                </div>
            </div>
            <ul class="dashboard-list">
                @forelse ($lowStockBooks as $ls)
                    <li class="dashboard-list-item">
                        <div class="list-item-main">
                            <span class="list-title">{{ $ls->title }}</span>
                            <span class="list-meta">{{ $ls->author }}</span>
                        </div>
                        <span class="status-pill {{ $ls->stock == 0 ? 'pill-cancelled' : 'pill-booked' }}" style="font-size:11px;">
                            {{ $ls->stock == 0 ? 'Stok Habis' : 'Sisa ' . $ls->stock . ' buku' }}
                        </span>
                    </li>
                @empty
                    <li style="color:var(--color-muted); padding:10px 0;">Semua stok buku aman.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
