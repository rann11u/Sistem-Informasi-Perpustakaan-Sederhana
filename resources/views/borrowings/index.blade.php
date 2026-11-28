@extends('layouts.app')

@section('title', 'Peminjaman & Booking')

@section('content')
    <div class="admin-card">
        <div class="page-header-action">
            <div>
                <h2>Daftar Peminjaman & Booking</h2>
                <small style="color:var(--color-muted);">Kelola peminjaman langsung dan booking online anggota</small>
            </div>
            <a href="{{ route('borrowings.create') }}" class="btn-ui btn-ui-primary">+ Catat Peminjaman</a>
        </div>

        {{-- Tab Filter Status --}}
        <div class="tab-filter-container">
            <a href="{{ route('borrowings.index') }}" class="tab-filter-item {{ !request('status') ? 'active' : '' }}">
                Semua Data
            </a>
            <a href="{{ route('borrowings.index', ['status' => 'booked']) }}" class="tab-filter-item {{ request('status') === 'booked' ? 'active' : '' }}">
                Daftar Booking Masuk ({{ $countBooked }})
            </a>
            <a href="{{ route('borrowings.index', ['status' => 'borrowed']) }}" class="tab-filter-item {{ request('status') === 'borrowed' ? 'active' : '' }}">
                Sedang Dipinjam ({{ $countBorrowed }})
            </a>
            <a href="{{ route('borrowings.index', ['status' => 'returned']) }}" class="tab-filter-item {{ request('status') === 'returned' ? 'active' : '' }}">
                Sudah Selesai ({{ $countReturned }})
            </a>
        </div>

        {{-- Banner Peringatan Booking Kedaluwarsa (> 1 Hari) --}}
        @if(isset($countExpired) && $countExpired > 0)
            <div class="expired-alert-banner">
                <div>
                    <div class="expired-alert-title">
                        <span>⚠️ Terdapat {{ $countExpired }} booking online yang telah kedaluwarsa (lewat batas 1x24 jam)!</span>
                    </div>
                    <div class="expired-alert-sub">
                        Member tidak datang mengambil buku tepat waktu. Anda dapat membatalkan booking agar stok buku kembali tersedia untuk dipinjam.
                    </div>
                </div>
                <form action="{{ route('borrowings.cancel-expired') }}" method="POST"
                      onsubmit="openConfirmModal(event, { title: 'Batalkan Semua Booking Kedaluwarsa', message: 'Apakah Anda yakin ingin membatalkan semua ({{ $countExpired }}) booking online yang telah kedaluwarsa?', note: 'Stok seluruh buku akan otomatis dikembalikan ke katalog perpustakaan.', confirmText: 'Ya, Batalkan Semua', loadingText: 'Membatalkan...', variant: 'danger' })">
                    @csrf
                    <button type="submit" class="btn-ui btn-ui-danger btn-ui-sm">
                        Batalkan Semua Booking Kedaluwarsa
                    </button>
                </form>
            </div>
        @endif

        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Anggota</th>
                        <th>Buku yang Dipinjam</th>
                        <th>Tgl Pinjam / Batas Ambil</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($borrowings as $borrowing)
                        @php
                            $isBooked  = $borrowing->status === 'booked';
                            $isExpired = $isBooked && $borrowing->isExpired();
                            $isOverdue = $borrowing->status === 'borrowed' && $borrowing->due_date && $borrowing->due_date->isPast();
                        @endphp
                        <tr class="{{ $isExpired ? 'row-expired' : '' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $borrowing->member->name ?? '-' }}</strong><br>
                                <small style="color:var(--color-muted);">{{ $borrowing->member->member_code ?? '' }}</small>
                            </td>
                            <td>
                                @foreach($borrowing->details as $detail)
                                    <div style="font-size:13px; margin-bottom:2px;">
                                        {{ $detail->book->title ?? '-' }}
                                        <span style="color:var(--color-muted);">({{ $detail->quantity }} eks)</span>
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                @if($isBooked)
                                    <div style="font-weight:600; font-size:13px; color:{{ $isExpired ? 'var(--color-error)' : 'var(--badge-booked-color)' }};">
                                        Batas: {{ $borrowing->pickup_deadline ? $borrowing->pickup_deadline->format('d/m/Y H:i') : '-' }} WIB
                                    </div>
                                    @if($isExpired)
                                        <span class="mini-pill mini-pill-danger">⚠️ Lewat 1 Hari (Kedaluwarsa)</span>
                                    @elseif($borrowing->pickup_deadline)
                                        <span class="mini-pill mini-pill-success">
                                            Sisa: {{ now()->diffForHumans($borrowing->pickup_deadline, ['parts' => 2, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}
                                        </span>
                                    @endif
                                @else
                                    <span style="color:var(--color-muted);">{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y') : '-' }}</span>
                                @endif
                            </td>
                            <td>
                                @if($isBooked)
                                    <span style="color:var(--color-muted); font-style:italic; font-size:12px;">Dihitung saat diambil</span>
                                @else
                                    <span style="color:{{ $isOverdue ? 'var(--color-error)' : 'var(--color-muted)' }}; font-weight:{{ $isOverdue ? '600' : '400' }};">
                                        {{ $borrowing->due_date ? $borrowing->due_date->format('d/m/Y') : '-' }}
                                    </span>
                                    @if($isOverdue)
                                        <div><span class="mini-pill mini-pill-danger">Terlambat</span></div>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if ($borrowing->status === 'booked')
                                    <span class="status-pill pill-booked">Booking Online</span>
                                    @if($isExpired)
                                        <div><span class="mini-pill mini-pill-danger">Kedaluwarsa</span></div>
                                    @endif
                                @elseif ($borrowing->status === 'borrowed')
                                    <span class="status-pill pill-borrowed">Dipinjam</span>
                                @elseif ($borrowing->status === 'returned')
                                    <span class="status-pill pill-returned">Dikembalikan</span>
                                @elseif ($borrowing->status === 'cancelled')
                                    <span class="status-pill pill-cancelled">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                                    <a href="{{ route('borrowings.show', $borrowing->id) }}" class="btn-ui btn-ui-secondary btn-ui-sm">Detail</a>
                                    <a href="{{ route('borrowings.invoice', $borrowing->id) }}" target="_blank" class="btn-ui btn-ui-secondary btn-ui-sm">Invoice PDF</a>

                                    @if ($borrowing->status === 'booked')
                                        @if ($isExpired)
                                            <form action="{{ route('borrowings.cancel-booking', $borrowing->id) }}" method="POST" style="display:inline;"
                                                  onsubmit="openConfirmModal(event, { title: 'Batalkan Booking Kedaluwarsa', message: 'Booking ini telah melewati batas waktu pengambilan 1x24 jam.', note: 'Batalkan booking sekarang dan kembalikan stok buku ke katalog?', confirmText: 'Ya, Batalkan', loadingText: 'Membatalkan...', variant: 'danger' })">
                                                @csrf
                                                <button type="submit" class="btn-ui btn-ui-danger btn-ui-sm">Batalkan / Kedaluwarsa</button>
                                            </form>
                                            <form action="{{ route('borrowings.confirm-pickup', $borrowing->id) }}" method="POST" style="display:inline;"
                                                  onsubmit="openConfirmModal(event, { title: 'Konfirmasi Penyerahan Buku', message: 'Booking ini sudah melewati batas waktu 1x24 jam.', note: 'Apakah Anda tetap ingin menyerahkan buku kepada member?', confirmText: 'Ya, Tetap Serahkan', loadingText: 'Memproses...', variant: 'warning' })">
                                                @csrf
                                                <button type="submit" class="btn-ui btn-ui-secondary btn-ui-sm">Tetap Serahkan</button>
                                            </form>
                                        @else
                                            <form action="{{ route('borrowings.confirm-pickup', $borrowing->id) }}" method="POST" style="display:inline;"
                                                  onsubmit="openConfirmModal(event, { title: 'Konfirmasi Pengambilan Buku', message: 'Konfirmasi bahwa member telah datang dan buku telah diserahkan?', note: 'Status transaksi akan berubah menjadi Dipinjam.', confirmText: 'Ya, Serahkan', loadingText: 'Memproses...', variant: 'success' })">
                                                @csrf
                                                <button type="submit" class="btn-ui btn-ui-success btn-ui-sm">Serahkan</button>
                                            </form>
                                            <form action="{{ route('borrowings.cancel-booking', $borrowing->id) }}" method="POST" style="display:inline;"
                                                  onsubmit="openConfirmModal(event, { title: 'Batalkan Booking Online', message: 'Apakah Anda yakin ingin membatalkan booking online ini?', note: 'Stok buku yang dibooking akan dikembalikan ke katalog.', confirmText: 'Ya, Batalkan', loadingText: 'Membatalkan...', variant: 'danger' })">
                                                @csrf
                                                <button type="submit" class="btn-ui btn-ui-danger btn-ui-sm">Batal</button>
                                            </form>
                                        @endif
                                    @endif

                                    @if ($borrowing->status === 'borrowed')
                                        <a href="{{ route('borrowings.edit', $borrowing->id) }}" class="btn-ui btn-ui-primary btn-ui-sm">Kembalikan</a>
                                    @endif

                                    @if (in_array($borrowing->status, ['returned', 'cancelled']))
                                        <form action="{{ route('borrowings.destroy', $borrowing->id) }}" method="POST" style="display:inline;"
                                              onsubmit="openDeleteModal(event, 'data transaksi ini')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-ui btn-ui-danger btn-ui-sm">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding:28px; text-align:center; color:var(--color-muted);">Belum ada data transaksi peminjaman atau booking.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
