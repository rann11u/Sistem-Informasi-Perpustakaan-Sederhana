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
                Booking Online ({{ $countBooked }})
            </a>
            <a href="{{ route('borrowings.index', ['status' => 'borrowed']) }}" class="tab-filter-item {{ request('status') === 'borrowed' ? 'active' : '' }}">
                Sedang Dipinjam ({{ $countBorrowed }})
            </a>
            <a href="{{ route('borrowings.index', ['status' => 'returned']) }}" class="tab-filter-item {{ request('status') === 'returned' ? 'active' : '' }}">
                Sudah Selesai ({{ $countReturned }})
            </a>
        </div>

        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Anggota</th>
                        <th>Buku yang Dipinjam</th>
                        <th>Tgl Pinjam / Batas</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($borrowings as $borrowing)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $borrowing->member->name ?? '-' }}</strong><br>
                                <small style="color:var(--color-muted);">{{ $borrowing->member->member_code ?? '' }}</small>
                            </td>
                            <td>
                                @foreach($borrowing->details as $detail)
                                    <div style="font-size:13px; margin-bottom:2px;">{{ $detail->book->title ?? '-' }} <span style="color:var(--color-muted);">({{ $detail->quantity }} buku)</span></div>
                                @endforeach
                            </td>
                            <td>
                                @if($borrowing->status === 'booked')
                                    <span style="color:var(--badge-booked-color); font-weight:600; font-size:13px;">
                                        Batas: {{ $borrowing->pickup_deadline ? $borrowing->pickup_deadline->format('d/m/Y H:i') : '-' }}
                                    </span>
                                @else
                                    <span style="color:var(--color-muted);">{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y') : '-' }}</span>
                                @endif
                            </td>
                            <td>
                                @if($borrowing->status === 'booked')
                                    <span style="color:var(--color-muted); font-style:italic; font-size:12px;">Dihitung saat diambil</span>
                                @else
                                    <span style="color:var(--color-muted);">{{ $borrowing->due_date ? $borrowing->due_date->format('d/m/Y') : '-' }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($borrowing->status === 'booked')
                                    <span class="status-pill pill-booked">Booking Online</span>
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

                                    @if ($borrowing->status === 'booked')
                                        <form action="{{ route('borrowings.confirm-pickup', $borrowing->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Konfirmasi bahwa member telah datang dan buku telah diserahkan?')">
                                            @csrf
                                            <button type="submit" class="btn-ui btn-ui-success btn-ui-sm">Serahkan</button>
                                        </form>
                                        <form action="{{ route('borrowings.cancel-booking', $borrowing->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Batalkan booking ini dan kembalikan stok buku?')">
                                            @csrf
                                            <button type="submit" class="btn-ui btn-ui-danger btn-ui-sm">Batal</button>
                                        </form>
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
