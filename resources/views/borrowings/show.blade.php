@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
    <h2 style="margin-top:0;">Detail Peminjaman</h2>

    @php $tdStyle = "padding:10px 14px; border-bottom:1px solid #eee;"; $thStyle = "padding:10px 14px; border-bottom:1px solid #eee; background:#f9f9f9; font-weight:bold; width:180px; text-align:left;"; @endphp

    <table style="font-size:14px; border-collapse:collapse; width:100%; max-width:560px;">
        <tr><th style="{{ $thStyle }}">Anggota</th><td style="{{ $tdStyle }}">{{ $borrowing->member->name ?? '-' }}</td></tr>
        <tr><th style="{{ $thStyle }}">Dicatat oleh</th><td style="{{ $tdStyle }}">{{ $borrowing->user->name ?? '-' }}</td></tr>
        <tr><th style="{{ $thStyle }}">Tanggal Pinjam</th><td style="{{ $tdStyle }}">{{ $borrowing->borrow_date->format('d/m/Y') }}</td></tr>
        <tr><th style="{{ $thStyle }}">Jatuh Tempo</th><td style="{{ $tdStyle }}">{{ $borrowing->due_date->format('d/m/Y') }}</td></tr>
        <tr><th style="{{ $thStyle }}">Tanggal Kembali</th><td style="{{ $tdStyle }}">{{ $borrowing->return_date ? $borrowing->return_date->format('d/m/Y') : '-' }}</td></tr>
        <tr>
            <th style="{{ $thStyle }}">Status</th>
            <td style="{{ $tdStyle }}">
                @if ($borrowing->status === 'borrowed')
                    <span style="background:#fff8e1; color:#f9a825; padding:3px 10px; border-radius:12px;">Dipinjam</span>
                @else
                    <span style="background:#e8f5e9; color:#174a38; padding:3px 10px; border-radius:12px;">Dikembalikan</span>
                @endif
            </td>
        </tr>
    </table>

    <h3 style="margin-top:24px;">Buku yang Dipinjam</h3>
    <table style="width:100%; max-width:560px; border-collapse:collapse; font-size:14px;">
        <thead>
            <tr style="background:#f0f0f0;">
                <th style="padding:8px; text-align:left; border-bottom:2px solid #ddd;">Judul Buku</th>
                <th style="padding:8px; text-align:left; border-bottom:2px solid #ddd;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowing->details as $detail)
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:8px;">{{ $detail->book->title ?? '-' }}</td>
                    <td style="padding:8px;">{{ $detail->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:20px;">
        @if ($borrowing->status === 'borrowed')
            <a href="{{ route('borrowings.edit', $borrowing->id) }}" style="background:#174a38; color:white; padding:8px 16px; border-radius:6px; text-decoration:none; margin-right:8px;">Proses Pengembalian</a>
        @endif
        <a href="{{ route('borrowings.index') }}" style="color:#555;">← Kembali</a>
    </div>
@endsection

