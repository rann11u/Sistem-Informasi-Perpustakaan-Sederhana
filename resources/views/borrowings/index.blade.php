@extends('layouts.app')

@section('title', 'Daftar Peminjaman')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2 style="margin:0;">Daftar Peminjaman</h2>
        <a href="{{ route('borrowings.create') }}" style="background:#174a38; color:white; padding:8px 16px; border-radius:6px; text-decoration:none;">+ Catat Peminjaman</a>
    </div>

    <table style="width:100%; border-collapse:collapse; font-size:14px;">
        <thead>
            <tr style="background:#f0f0f0;">
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">#</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Anggota</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Tgl Pinjam</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Jatuh Tempo</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Tgl Kembali</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Status</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($borrowings as $borrowing)
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:10px;">{{ $loop->iteration }}</td>
                    <td style="padding:10px;">{{ $borrowing->member->name ?? '-' }}</td>
                    <td style="padding:10px;">{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                    <td style="padding:10px;">{{ $borrowing->due_date->format('d/m/Y') }}</td>
                    <td style="padding:10px;">{{ $borrowing->return_date ? $borrowing->return_date->format('d/m/Y') : '-' }}</td>
                    <td style="padding:10px;">
                        @if ($borrowing->status === 'borrowed')
                            <span style="background:#fff8e1; color:#f9a825; padding:3px 10px; border-radius:12px; font-size:12px;">Dipinjam</span>
                        @else
                            <span style="background:#e8f5e9; color:#174a38; padding:3px 10px; border-radius:12px; font-size:12px;">Dikembalikan</span>
                        @endif
                    </td>
                    <td style="padding:10px;">
                        <a href="{{ route('borrowings.show', $borrowing->id) }}" style="color:#1565c0; margin-right:8px;">Detail</a>
                        @if ($borrowing->status === 'borrowed')
                            <a href="{{ route('borrowings.edit', $borrowing->id) }}" style="color:#174a38; margin-right:8px;">Kembalikan</a>
                        @endif
                        <form action="{{ route('borrowings.destroy', $borrowing->id) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Hapus data peminjaman ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="color:#c62828; background:none; border:none; cursor:pointer; padding:0;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="padding:20px; text-align:center; color:#888;">Belum ada data peminjaman.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection

