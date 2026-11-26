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

    {{-- Banner Peringatan Booking Kedaluwarsa --}}
    @if(isset($countExpired) && $countExpired > 0)
        <div style="background:#fef2f2; border:1px solid #fecaca; border-left:5px solid #dc2626; padding:12px 18px; border-radius:8px; margin-bottom:18px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div>
                <strong style="color:#991b1b; font-size:14px; display:flex; align-items:center; gap:6px;">
                    ⚠️ Terdapat {{ $countExpired }} booking online yang telah kedaluwarsa (lewat batas 1x24 jam)!
                </strong>
                <div style="color:#7f1d1d; font-size:13px; margin-top:3px;">
                    Member tidak datang mengambil buku tepat waktu. Anda dapat membatalkan booking agar stok buku kembali tersedia untuk dipinjam.
                </div>
            </div>
            <form action="{{ route('borrowings.cancel-expired') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan SEMUA ({{ $countExpired }}) booking yang kedaluwarsa?\n\nStok seluruh buku akan otomatis dikembalikan ke katalog perpustakaan.')">
                @csrf
                <button type="submit" style="background:#dc2626; color:white; border:none; padding:8px 16px; border-radius:6px; font-weight:bold; font-size:13px; cursor:pointer; box-shadow:0 1px 2px rgba(220,38,38,0.2);">
                    ⚡ Batalkan Semua Booking Kedaluwarsa
                </button>
            </form>
        </div>
    @endif

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
                @php
                    $isBooked  = $borrowing->status === 'booked';
                    $isExpired = $isBooked && $borrowing->isExpired();
                @endphp
                <tr style="border-bottom:1px solid #eee; {{ $isExpired ? 'background:#fff8f8;' : '' }}">
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
                        @if($isBooked)
                            <div style="font-weight:bold; font-size:13px; {{ $isExpired ? 'color:#dc2626;' : 'color:#b45309;' }}">
                                Batas: {{ $borrowing->pickup_deadline ? $borrowing->pickup_deadline->format('d/m/Y H:i') : '-' }} WIB
                            </div>
                            @if($isExpired)
                                <span style="background:#fee2e2; color:#dc2626; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:bold; display:inline-block; margin-top:3px;">
                                    ⚠️ Lewat 1 Hari (Kedaluwarsa)
                                </span>
                            @else
                                <span style="background:#ecfdf5; color:#047857; padding:2px 8px; border-radius:10px; font-size:11px; display:inline-block; margin-top:3px;">
                                    Sisa: {{ now()->diffForHumans($borrowing->pickup_deadline, ['parts' => 2, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}
                                </span>
                            @endif
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
                            <span style="background:#fef3c7; color:#92400e; padding:4px 10px; border-radius:12px; font-size:12px; font-weight:bold; display:inline-block;">
                                Booking Online
                            </span>
                            @if($isExpired)
                                <span style="background:#fee2e2; color:#b91c1c; padding:2px 8px; border-radius:10px; font-size:10px; font-weight:bold; display:block; margin-top:4px; text-align:center;">
                                    Kedaluwarsa
                                </span>
                            @endif
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

                        {{-- Tombol Aksi untuk Status BOOKED --}}
                        @if ($borrowing->status === 'booked')
                            @if ($isExpired)
                                {{-- Jika Sudah Kedaluwarsa (> 1 Hari): Tombol Batalkan / Kedaluwarsa Lebih Menonjol --}}
                                <form action="{{ route('borrowings.cancel-booking', $borrowing->id) }}" method="POST" style="display:inline;"
                                      onsubmit="return confirm('Booking ini telah KEDALUWARSA (melewati 1x24 jam).\n\nBatalkan booking sekarang dan kembalikan stok buku ke katalog?')">
                                    @csrf
                                    <button type="submit" style="background:#dc2626; color:white; border:none; padding:5px 11px; border-radius:4px; font-size:12px; font-weight:bold; cursor:pointer; margin-right:6px; box-shadow:0 1px 2px rgba(220,38,38,0.2);">
                                        ⚠️ Batalkan / Kedaluwarsa
                                    </button>
                                </form>
                                <form action="{{ route('borrowings.confirm-pickup', $borrowing->id) }}" method="POST" style="display:inline;"
                                      onsubmit="return confirm('Peringatan: Booking ini sebenarnya sudah lewat batas 1x24 jam.\n\nApakah Anda tetap ingin menyerahkan buku kepada member?')">
                                    @csrf
                                    <button type="submit" style="background:#f3f4f6; color:#374151; border:1px solid #d1d5db; padding:5px 9px; border-radius:4px; font-size:11px; cursor:pointer; margin-right:6px;">
                                        Tetap Serahkan
                                    </button>
                                </form>
                            @else
                                {{-- Masih Berlaku (Dalam Batas 1x24 Jam) --}}
                                <form action="{{ route('borrowings.confirm-pickup', $borrowing->id) }}" method="POST" style="display:inline;"
                                      onsubmit="return confirm('Konfirmasi bahwa member telah datang dan buku telah diserahkan?')">
                                    @csrf
                                    <button type="submit" style="background:#16a34a; color:white; border:none; padding:5px 10px; border-radius:4px; font-size:12px; font-weight:bold; cursor:pointer; margin-right:6px;">
                                        Konfirmasi Pengambilan / Serahkan Buku
                                    </button>
                                </form>
                                <form action="{{ route('borrowings.cancel-booking', $borrowing->id) }}" method="POST" style="display:inline;"
                                      onsubmit="return confirm('Batalkan booking ini dan kembalikan stok buku?')">
                                    @csrf
                                    <button type="submit" style="color:#dc2626; background:none; border:none; cursor:pointer; font-size:12px; padding:0; text-decoration:underline;">
                                        Batalkan
                                    </button>
                                </form>
                            @endif
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
