@extends('layouts.app')

@section('title', 'Daftar Peminjaman & Booking')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:10px;">
        <h2 style="margin:0;">Daftar Peminjaman & Booking Buku</h2>
        <a href="{{ route('borrowings.create') }}" style="background:#174a38; color:white; padding:8px 16px; border-radius:6px; text-decoration:none; font-weight:bold;">+ Catat Peminjaman Langsung</a>
    </div>

    {{-- Tab Filter Status Peminjaman --}}
    <div style="display:flex; gap:8px; margin-bottom:18px; flex-wrap:wrap; border-bottom:1px solid #e5e7eb; padding-bottom:12px;">
        <a href="{{ route('borrowings.index') }}" style="padding:6px 14px; border-radius:20px; font-size:13px; text-decoration:none; {{ !request('status') ? 'background:#174a38; color:white; font-weight:bold;' : 'background:#f3f4f6; color:#4b5563;' }}">
            Semua Data
        </a>
        <a href="{{ route('borrowings.index', ['status' => 'booked']) }}" style="padding:6px 14px; border-radius:20px; font-size:13px; text-decoration:none; {{ request('status') === 'booked' ? 'background:#d97706; color:white; font-weight:bold;' : 'background:#fef3c7; color:#92400e;' }}">
            Daftar Booking Masuk ({{ $countBooked }})
        </a>
        <a href="{{ route('borrowings.index', ['status' => 'borrowed']) }}" style="padding:6px 14px; border-radius:20px; font-size:13px; text-decoration:none; {{ request('status') === 'borrowed' ? 'background:#1565c0; color:white; font-weight:bold;' : 'background:#e0f2fe; color:#0369a1;' }}">
            Sedang Dipinjam ({{ $countBorrowed }})
        </a>
        <a href="{{ route('borrowings.index', ['status' => 'returned']) }}" style="padding:6px 14px; border-radius:20px; font-size:13px; text-decoration:none; {{ request('status') === 'returned' ? 'background:#16a34a; color:white; font-weight:bold;' : 'background:#dcfce7; color:#15803d;' }}">
            Sudah Selesai ({{ $countReturned }})
        </a>
    </div>

    <table style="width:100%; border-collapse:collapse; font-size:14px;">
        <thead>
            <tr style="background:#f0f0f0;">
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">#</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Anggota</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Buku yang Dipinjam</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Tgl Pinjam / Batas Ambil</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Jatuh Tempo</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Status</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #ddd;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($borrowings as $borrowing)
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:10px;">{{ $loop->iteration }}</td>
                    
                    {{-- Nama & Kode Anggota --}}
                    <td style="padding:10px;">
                        <strong>{{ $borrowing->member->name ?? '-' }}</strong><br>
                        <small style="color:#6b7280;">{{ $borrowing->member->member_code ?? '' }}</small>
                    </td>

                    {{-- Judul Buku --}}
                    <td style="padding:10px;">
                        @foreach($borrowing->details as $detail)
                            <div>• {{ $detail->book->title ?? '-' }} ({{ $detail->quantity }} eks)</div>
                        @endforeach
                    </td>

                    {{-- Tanggal / Batas Ambil --}}
                    <td style="padding:10px;">
                        @if($borrowing->status === 'booked')
                            <span style="color:#b45309; font-weight:bold; font-size:13px;">
                                Batas: {{ $borrowing->pickup_deadline ? $borrowing->pickup_deadline->format('d/m/Y H:i') : '-' }}
                            </span>
                        @else
                            {{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y') : '-' }}
                        @endif
                    </td>

                    {{-- Jatuh Tempo --}}
                    <td style="padding:10px;">
                        @if($borrowing->status === 'booked')
                            <span style="color:#6b7280; font-style:italic;">Dihitung saat diambil</span>
                        @else
                            {{ $borrowing->due_date ? $borrowing->due_date->format('d/m/Y') : '-' }}
                        @endif
                    </td>

                    {{-- Status --}}
                    <td style="padding:10px;">
                        @if ($borrowing->status === 'booked')
                            <span style="background:#fef3c7; color:#92400e; padding:4px 10px; border-radius:12px; font-size:12px; font-weight:bold;">
                                Booking Online
                            </span>
                        @elseif ($borrowing->status === 'borrowed')
                            <span style="background:#e0f2fe; color:#0369a1; padding:4px 10px; border-radius:12px; font-size:12px; font-weight:bold;">
                                Dipinjam
                            </span>
                        @elseif ($borrowing->status === 'returned')
                            <span style="background:#dcfce7; color:#15803d; padding:4px 10px; border-radius:12px; font-size:12px; font-weight:bold;">
                                Dikembalikan
                            </span>
                        @elseif ($borrowing->status === 'cancelled')
                            <span style="background:#f3f4f6; color:#6b7280; padding:4px 10px; border-radius:12px; font-size:12px;">
                                Dibatalkan
                            </span>
                        @endif
                    </td>

                    {{-- Aksi Petugas --}}
                    <td style="padding:10px; white-space:nowrap;">
                        <a href="{{ route('borrowings.show', $borrowing->id) }}" style="color:#1565c0; margin-right:8px; text-decoration:none; font-weight:bold;">Detail</a>

                        {{-- Tombol Konfirmasi Penyerahan Buku Jika Status BOOKED --}}
                        @if ($borrowing->status === 'booked')
                            <form action="{{ route('borrowings.confirm-pickup', $borrowing->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Konfirmasi bahwa member telah datang dan buku telah diserahkan?')">
                                @csrf
                                <button type="submit" style="background:#16a34a; color:white; border:none; padding:5px 10px; border-radius:4px; font-size:12px; font-weight:bold; cursor:pointer; margin-right:6px;">
                                    Konfirmasi Pengambilan / Serahkan Buku
                                </button>
                            </form>
                            <form action="{{ route('borrowings.cancel-booking', $borrowing->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Batalkan booking ini dan kembalikan stok buku?')">
                                @csrf
                                <button type="submit" style="color:#dc2626; background:none; border:none; cursor:pointer; font-size:12px; padding:0; text-decoration:underline;">
                                    Batal
                                </button>
                            </form>
                        @endif

                        {{-- Tombol Proses Pengembalian Jika Status BORROWED --}}
                        @if ($borrowing->status === 'borrowed')
                            <a href="{{ route('borrowings.edit', $borrowing->id) }}" style="color:#174a38; margin-right:8px; text-decoration:none; font-weight:bold;">Kembalikan</a>
                        @endif

                        {{-- Tombol Cetak / Download Invoice PDF --}}
                        <a href="{{ route('borrowings.invoice', $borrowing->id) }}" target="_blank" style="color:#6d28d9; margin-right:8px; text-decoration:none; font-weight:bold;">Invoice PDF</a>

                        {{-- Tombol Hapus Jika Status Selesai / Dibatalkan --}}
                        @if (in_array($borrowing->status, ['returned', 'cancelled']))
                            <form action="{{ route('borrowings.destroy', $borrowing->id) }}" method="POST" style="display:inline;"
                                  onsubmit="openDeleteModal(event, 'data transaksi ini')">
                                @csrf @method('DELETE')
                                <button type="submit" style="color:#c62828; background:none; border:none; cursor:pointer; padding:0;">Hapus</button>
                            </form>
                        @endif

                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="padding:24px; text-align:center; color:#888;">Belum ada data transaksi peminjaman atau booking.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
