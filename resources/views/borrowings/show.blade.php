@extends('layouts.app')

@section('title', 'Detail Peminjaman & Booking')

@section('content')
    <div class="admin-card" style="max-width: 680px; margin: 0 auto;">
        <div class="page-header-action">
            <h2>Detail Transaksi Peminjaman</h2>
            <a href="{{ route('borrowings.index') }}" class="btn-ui btn-ui-secondary">← Kembali ke Daftar</a>
        </div>

        <table class="admin-table">
            <tr>
                <th style="width:180px;">Anggota</th>
                <td>
                    <strong>{{ $borrowing->member->name ?? '-' }}</strong> ({{ $borrowing->member->member_code ?? '' }})
                </td>
            </tr>
            <tr>
                <th>Petugas Pelayan</th>
                <td>
                    @if($borrowing->user)
                        {{ $borrowing->user->name }}
                    @else
                        <span style="color:var(--badge-booked-color); font-style:italic;">Menunggu Penyerahan Buku (Booking Online)</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if ($borrowing->status === 'booked')
                        <span class="status-pill pill-booked">Booking Online</span>
                    @elseif ($borrowing->status === 'borrowed')
                        <span class="status-pill pill-borrowed">Sedang Dipinjam</span>
                    @elseif ($borrowing->status === 'returned')
                        <span class="status-pill pill-returned">Sudah Dikembalikan</span>
                    @elseif ($borrowing->status === 'cancelled')
                        <span class="status-pill pill-cancelled">Dibatalkan</span>
                    @endif
                </td>
            </tr>

            @if($borrowing->status === 'booked')
                <tr>
                    <th>Batas Pengambilan</th>
                    <td>
                        <strong style="color:var(--badge-booked-color);">{{ $borrowing->pickup_deadline ? $borrowing->pickup_deadline->format('d/m/Y H:i') : '-' }} WIB</strong>
                        <div style="font-size:12px; color:var(--color-muted); margin-top:2px;">(Maksimal 1 hari dari waktu booking)</div>
                    </td>
                </tr>
            @else
                <tr>
                    <th>Tanggal Pinjam</th>
                    <td>{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y') : '-' }}</td>
                </tr>
                <tr>
                    <th>Jatuh Tempo</th>
                    <td>
                        {{ $borrowing->due_date ? $borrowing->due_date->format('d/m/Y') : '-' }}
                        @if ($borrowing->status === 'borrowed' && $borrowing->due_date && $borrowing->due_date->isPast())
                            <span style="color:var(--color-error); font-weight:bold; margin-left:8px;">(Terlambat)</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Tanggal Kembali</th>
                    <td>
                        @if ($borrowing->return_date)
                            {{ $borrowing->return_date->format('d/m/Y') }}
                        @else
                            <span style="color:var(--color-muted);">-</span>
                        @endif
                    </td>
                </tr>
            @endif

            <tr>
                <th>Buku yang Dipinjam</th>
                <td>
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        @foreach ($borrowing->details as $detail)
                            <div>
                                <strong>{{ $detail->book->title ?? 'Buku dihapus' }}</strong> <span style="color:var(--color-muted);">({{ $detail->quantity }} buku)</span>
                            </div>
                        @endforeach
                    </div>
                </td>
            </tr>
        </table>

        {{-- Aksi Cepat Petugas --}}
        <div style="margin-top:24px; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            @if ($borrowing->status === 'booked')
                <form action="{{ route('borrowings.confirm-pickup', $borrowing->id) }}" method="POST" style="display:inline;"
                      onsubmit="openConfirmModal(event, { title: 'Konfirmasi Pengambilan Buku', message: 'Konfirmasi bahwa member telah datang dan buku telah diserahkan?', note: 'Status transaksi akan berubah menjadi Dipinjam.', confirmText: 'Ya, Serahkan', loadingText: 'Memproses...', variant: 'success' })">
                    @csrf
                    <button type="submit" class="btn-ui btn-ui-success">Serahkan Buku ke Member</button>
                </form>
                <form action="{{ route('borrowings.cancel-booking', $borrowing->id) }}" method="POST" style="display:inline;"
                      onsubmit="openConfirmModal(event, { title: 'Batalkan Booking Online', message: 'Apakah Anda yakin ingin membatalkan booking online ini?', note: 'Stok buku yang dibooking akan dikembalikan ke katalog.', confirmText: 'Ya, Batalkan', loadingText: 'Membatalkan...', variant: 'danger' })">
                    @csrf
                    <button type="submit" class="btn-ui btn-ui-danger">Batalkan Booking</button>
                </form>
            @endif

            @if ($borrowing->status === 'borrowed')
                <a href="{{ route('borrowings.edit', $borrowing->id) }}" class="btn-ui btn-ui-primary">Proses Pengembalian</a>
            @endif

            <a href="{{ route('borrowings.invoice', $borrowing->id) }}" target="_blank" class="btn-ui btn-ui-secondary">
                Cetak / Download Bukti Pinjam (PDF)
            </a>

            <a href="{{ route('borrowings.index') }}" class="btn-ui btn-ui-secondary">Kembali</a>
        </div>
    </div>
@endsection
