@extends('layouts.app')

@section('title', 'Detail Peminjaman & Booking')

@section('content')
    <h2 style="margin-top:0;">Detail Transaksi Peminjaman</h2>

    @php $tdStyle = "padding:10px 14px; border-bottom:1px solid #eee;"; $thStyle = "padding:10px 14px; border-bottom:1px solid #eee; background:#f9f9f9; font-weight:bold; width:180px; text-align:left;"; @endphp

    <table style="font-size:14px; border-collapse:collapse; width:100%; max-width:600px;">
        <tr>
            <th style="{{ $thStyle }}">Anggota</th>
            <td style="{{ $tdStyle }}">
                <strong>{{ $borrowing->member->name ?? '-' }}</strong> ({{ $borrowing->member->member_code ?? '' }})
            </td>
        </tr>
        <tr>
            <th style="{{ $thStyle }}">Petugas Pelayan</th>
            <td style="{{ $tdStyle }}">
                @if($borrowing->user)
                    {{ $borrowing->user->name }}
                @else
                    <span style="color:#d97706; font-style:italic;">Menunggu Penyerahan Buku (Booking Online)</span>
                @endif
            </td>
        </tr>
        <tr>
            <th style="{{ $thStyle }}">Status</th>
            <td style="{{ $tdStyle }}">
                @if ($borrowing->status === 'booked')
                    <span style="background:#fef3c7; color:#92400e; padding:3px 10px; border-radius:12px; font-weight:bold;">Booking Online</span>
                @elseif ($borrowing->status === 'borrowed')
                    <span style="background:#e0f2fe; color:#0369a1; padding:3px 10px; border-radius:12px; font-weight:bold;">Sedang Dipinjam</span>
                @elseif ($borrowing->status === 'returned')
                    <span style="background:#dcfce7; color:#15803d; padding:3px 10px; border-radius:12px; font-weight:bold;">Sudah Dikembalikan</span>
                @elseif ($borrowing->status === 'cancelled')
                    <span style="background:#f3f4f6; color:#6b7280; padding:3px 10px; border-radius:12px;">Dibatalkan</span>
                @endif
            </td>
        </tr>

        @if($borrowing->status === 'booked')
            <tr>
                <th style="{{ $thStyle }}">Batas Pengambilan</th>
                <td style="{{ $tdStyle }}">
                    <strong style="color:#b45309;">{{ $borrowing->pickup_deadline ? $borrowing->pickup_deadline->format('d/m/Y H:i') : '-' }} WIB</strong>
                    <div style="font-size:12px; color:#6b7280; margin-top:2px;">(Maksimal 1 hari dari waktu booking)</div>
                </td>
            </tr>
        @else
            <tr>
                <th style="{{ $thStyle }}">Tanggal Pinjam</th>
                <td style="{{ $tdStyle }}">{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y') : '-' }}</td>
            </tr>
            <tr>
                <th style="{{ $thStyle }}">Jatuh Tempo</th>
                <td style="{{ $tdStyle }}">{{ $borrowing->due_date ? $borrowing->due_date->format('d/m/Y') : '-' }}</td>
            </tr>
            <tr>
                <th style="{{ $thStyle }}">Tanggal Kembali</th>
                <td style="{{ $tdStyle }}">{{ $borrowing->return_date ? $borrowing->return_date->format('d/m/Y') : '-' }}</td>
            </tr>
        @endif
    </table>

    <h3 style="margin-top:24px;">Buku yang Dipesan / Dipinjam</h3>
    <table style="width:100%; max-width:600px; border-collapse:collapse; font-size:14px; margin-bottom:20px;">
        <thead>
            <tr style="background:#f0f0f0;">
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Judul Buku</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Kategori</th>
                <th style="padding:10px; text-align:center; border-bottom:2px solid #ddd;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowing->details as $detail)
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:10px;"><strong>{{ $detail->book->title ?? '-' }}</strong></td>
                    <td style="padding:10px;">{{ $detail->book->category->name ?? '-' }}</td>
                    <td style="padding:10px; text-align:center;">{{ $detail->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        @if ($borrowing->status === 'booked')
            <form action="{{ route('borrowings.confirm-pickup', $borrowing->id) }}" method="POST" onsubmit="return confirm('Konfirmasi penyerahan buku kepada member?')">
                @csrf
                <button type="submit" style="background:#16a34a; color:white; border:none; padding:9px 16px; border-radius:6px; font-weight:bold; cursor:pointer;">
                    Serahkan Buku (Konfirmasi Pengambilan)
                </button>
            </form>
            <form action="{{ route('borrowings.cancel-booking', $borrowing->id) }}" method="POST" onsubmit="return confirm('Batalkan booking ini?')">
                @csrf
                <button type="submit" style="background:#dc2626; color:white; border:none; padding:9px 16px; border-radius:6px; font-weight:bold; cursor:pointer;">
                    Batalkan Booking
                </button>
            </form>
        @elseif ($borrowing->status === 'borrowed')
            <a href="{{ route('borrowings.edit', $borrowing->id) }}" style="background:#174a38; color:white; padding:9px 16px; border-radius:6px; text-decoration:none; font-weight:bold;">
                Proses Pengembalian
            </a>
        @endif
        <a href="{{ route('borrowings.invoice', $borrowing->id) }}" target="_blank" style="background:#6d28d9; color:white; padding:9px 16px; border-radius:6px; text-decoration:none; font-weight:bold;">
            Cetak / Download Bukti Pinjam (PDF)
        </a>
        <a href="{{ route('borrowings.index') }}" style="color:#4b5563; text-decoration:none; padding:9px 14px; background:#e5e7eb; border-radius:6px; font-weight:bold;">
            ← Kembali ke Daftar
        </a>
    </div>
@endsection
