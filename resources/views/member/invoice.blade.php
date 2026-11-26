<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pinjam #TRX-{{ str_pad($borrowing->id, 5, '0', STR_PAD_LEFT) }} - Perpustakaan</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            color: #1f2937;
        }

        /* ======= TOOLBAR (hanya tampil di layar, hilang saat print) ======= */
        .toolbar {
            background: #174a38;
            color: white;
            padding: 12px 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }
        .toolbar span { font-size: 14px; }
        .toolbar-actions { display: flex; gap: 10px; }
        .btn-print {
            background: white;
            color: #174a38;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }
        .btn-print:hover { background: #dcfce7; }
        .btn-back {
            background: rgba(255,255,255,0.15);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-back:hover { background: rgba(255,255,255,0.25); }

        /* ======= HALAMAN INVOICE ======= */
        .invoice-wrapper {
            max-width: 760px;
            margin: 32px auto 48px;
            padding: 0 16px;
        }

        .invoice-paper {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            padding: 48px 48px;
        }

        /* ======= KOP SURAT ======= */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 2px solid #174a38;
        }
        .logo-area h2 {
            color: #174a38;
            font-size: 20px;
            margin-bottom: 4px;
        }
        .logo-area p {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.6;
        }

        .invoice-meta { text-align: right; }
        .invoice-meta .invoice-label {
            font-size: 11px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .invoice-meta .invoice-number {
            font-size: 22px;
            font-weight: bold;
            color: #174a38;
            font-family: monospace;
            margin: 4px 0;
        }
        .invoice-meta .invoice-date { font-size: 13px; color: #4b5563; }

        /* ======= BADGE STATUS BESAR ======= */
        .status-banner {
            text-align: center;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 28px;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .status-booked    { background:#fef3c7; color:#92400e; border:1px solid #fcd34d; }
        .status-borrowed  { background:#e0f2fe; color:#0369a1; border:1px solid #93c5fd; }
        .status-returned  { background:#dcfce7; color:#15803d; border:1px solid #86efac; }
        .status-cancelled { background:#f3f4f6; color:#6b7280; border:1px solid #e5e7eb; }

        /* ======= SECTION ======= */
        .section { margin-bottom: 24px; }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #9ca3af;
            margin-bottom: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 24px;
        }
        .info-item {}
        .info-item .label {
            font-size: 11px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 2px;
        }
        .info-item .value {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }
        .value-warning { color: #d97706 !important; }
        .value-danger  { color: #dc2626 !important; }
        .value-ok      { color: #16a34a !important; }

        /* ======= TABEL BUKU ======= */
        .books-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-top: 10px;
        }
        .books-table thead tr {
            background: #174a38;
            color: white;
        }
        .books-table thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .books-table tbody tr { border-bottom: 1px solid #f3f4f6; }
        .books-table tbody tr:last-child { border-bottom: none; }
        .books-table tbody td { padding: 12px; vertical-align: middle; }
        .books-table tfoot tr { border-top: 2px solid #e5e7eb; background: #f9fafb; }
        .books-table tfoot td {
            padding: 10px 12px;
            font-size: 13px;
            font-weight: bold;
            color: #374151;
        }

        .book-cover-sm {
            width: 32px; height: 44px;
            object-fit: cover;
            border-radius: 3px;
            border: 1px solid #e5e7eb;
        }
        .book-cover-placeholder-sm {
            width: 32px; height: 44px;
            background: #e2e8f0;
            border-radius: 3px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* ======= CATATAN / FOOTER INVOICE ======= */
        .invoice-footer {
            margin-top: 32px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 20px;
        }
        .invoice-footer .notes {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.8;
            max-width: 320px;
        }
        .invoice-footer .notes strong { color: #374151; }
        .signature-area { text-align: center; }
        .signature-area .sig-line {
            border-top: 1px solid #374151;
            width: 160px;
            margin: 48px auto 6px;
        }
        .signature-area .sig-label { font-size: 12px; color: #374151; }

        /* ======= PRINT RULES ======= */
        @media print {
            body { background: white; }
            .toolbar { display: none !important; }
            .invoice-wrapper { margin: 0; padding: 0; max-width: 100%; }
            .invoice-paper {
                box-shadow: none;
                border: none;
                border-radius: 0;
                padding: 24px 32px;
            }
        }
    </style>
</head>
<body>

    {{-- TOOLBAR LAYAR --}}
    <div class="toolbar no-print">
        <span>📄 Bukti Pinjam Buku — Siap dicetak atau disimpan sebagai PDF</span>
        <div class="toolbar-actions">
            <a href="{{ route('member.history') }}" class="btn-back">← Kembali</a>
            <button onclick="window.print()" class="btn-print">
                🖨️ &nbsp;Print / Simpan PDF
            </button>
        </div>
    </div>

    <div class="invoice-wrapper">
        <div class="invoice-paper">

            {{-- KOP SURAT --}}
            <div class="invoice-header">
                <div class="logo-area">
                    <h2>📚 Perpustakaan</h2>
                    <p>
                        Sistem Informasi Perpustakaan Sederhana<br>
                        Universitas Singaperbangsa Karawang<br>
                        Pemrograman Web Framework – SYS61645
                    </p>
                </div>
                <div class="invoice-meta">
                    <div class="invoice-label">Nomor Bukti</div>
                    <div class="invoice-number">#TRX-{{ str_pad($borrowing->id, 5, '0', STR_PAD_LEFT) }}</div>
                    <div class="invoice-date">Dicetak: {{ now()->format('d M Y, H:i') }} WIB</div>
                </div>
            </div>

            {{-- STATUS BANNER --}}
            @php
                $isOverdue = $borrowing->status === 'borrowed'
                    && $borrowing->due_date
                    && $borrowing->due_date->isPast();
            @endphp
            @if($borrowing->status === 'booked')
                <div class="status-banner status-booked">⏳ STATUS: BOOKING ONLINE — Menunggu Pengambilan di Perpustakaan</div>
            @elseif($borrowing->status === 'borrowed')
                <div class="status-banner status-borrowed {{ $isOverdue ? 'status-cancelled' : '' }}">
                    {{ $isOverdue ? '⚠️ STATUS: TERLAMBAT DIKEMBALIKAN!' : '📖 STATUS: SEDANG DIPINJAM' }}
                </div>
            @elseif($borrowing->status === 'returned')
                <div class="status-banner status-returned">✅ STATUS: SELESAI — Buku Telah Dikembalikan</div>
            @else
                <div class="status-banner status-cancelled">❌ STATUS: DIBATALKAN</div>
            @endif

            {{-- DATA PEMINJAM --}}
            <div class="section">
                <div class="section-title">Informasi Peminjam</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="label">Nama Lengkap</div>
                        <div class="value">{{ $member->name ?? $user->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Kode Anggota</div>
                        <div class="value" style="font-family:monospace;">{{ $member->member_code ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Email</div>
                        <div class="value">{{ $user->email }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Alamat</div>
                        <div class="value">{{ $member->address ?? '-' }}</div>
                    </div>
                </div>
            </div>

            {{-- DATA TRANSAKSI --}}
            <div class="section">
                <div class="section-title">Detail Transaksi</div>
                <div class="info-grid">
                    @if($borrowing->status === 'booked')
                        <div class="info-item">
                            <div class="label">Tanggal Booking</div>
                            <div class="value">{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d M Y') : '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">Batas Waktu Pengambilan</div>
                            <div class="value value-warning">
                                {{ $borrowing->pickup_deadline ? $borrowing->pickup_deadline->format('d M Y, H:i') . ' WIB' : '-' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="label">Petugas</div>
                            <div class="value">Belum ada (menunggu pengambilan)</div>
                        </div>
                    @else
                        <div class="info-item">
                            <div class="label">Tanggal Pinjam</div>
                            <div class="value">{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d M Y') : '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">Jatuh Tempo Pengembalian</div>
                            <div class="value {{ $isOverdue ? 'value-danger' : '' }}">
                                {{ $borrowing->due_date ? $borrowing->due_date->format('d M Y') : '-' }}
                                @if($isOverdue) (Terlambat!) @endif
                            </div>
                        </div>
                        @if($borrowing->status === 'returned')
                            <div class="info-item">
                                <div class="label">Tanggal Kembali</div>
                                <div class="value value-ok">{{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : '-' }}</div>
                            </div>
                        @endif
                        <div class="info-item">
                            <div class="label">Petugas Pelayan</div>
                            <div class="value">{{ $borrowing->user?->name ?? '-' }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- TABEL BUKU --}}
            <div class="section">
                <div class="section-title">Buku yang Dipinjam / Dibooking</div>
                <table class="books-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sampul</th>
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th>Kategori</th>
                            <th>ISBN</th>
                            <th style="text-align:center;">Jml</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrowing->details as $i => $detail)
                            <tr>
                                <td style="color:#9ca3af;">{{ $i + 1 }}</td>
                                <td>
                                    @if($detail->book && $detail->book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($detail->book->cover_image))
                                        <img class="book-cover-sm" src="{{ asset('storage/' . $detail->book->cover_image) }}" alt="{{ $detail->book->title }}">
                                    @else
                                        <div class="book-cover-placeholder-sm">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td><strong>{{ $detail->book->title ?? '-' }}</strong></td>
                                <td>{{ $detail->book->author ?? '-' }}</td>
                                <td>{{ $detail->book?->category?->name ?? '-' }}</td>
                                <td style="font-family:monospace; font-size:12px; color:#6b7280;">{{ $detail->book->isbn ?? '-' }}</td>
                                <td style="text-align:center; font-weight:bold;">{{ $detail->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" style="text-align:right;">Total Buku:</td>
                            <td style="text-align:center; color:#174a38;">
                                {{ $borrowing->details->sum('quantity') }} eks
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- FOOTER INVOICE --}}
            <div class="invoice-footer">
                <div class="notes">
                    <strong>Catatan Penting:</strong><br>
                    • Buku wajib dikembalikan tepat waktu sesuai jatuh tempo.<br>
                    • Keterlambatan akan dikenakan sanksi sesuai peraturan perpustakaan.<br>
                    • Simpan bukti ini sebagai tanda terima resmi peminjaman buku.<br>
                    • Untuk informasi lebih lanjut, hubungi petugas perpustakaan.
                </div>
                <div class="signature-area">
                    <div class="sig-line"></div>
                    <div class="sig-label">Tanda Tangan Petugas</div>
                    <div style="font-size:11px; color:#9ca3af; margin-top:4px;">
                        {{ $borrowing->user?->name ?? '( ..................... )' }}
                    </div>
                </div>
            </div>

        </div>{{-- /invoice-paper --}}
    </div>{{-- /invoice-wrapper --}}

    <script>
        // Auto-trigger print saat halaman sudah selesai dimuat
        // Dinonaktifkan agar user bisa melihat preview dulu.
        // Aktifkan baris berikut jika ingin langsung muncul dialog print:
        // window.addEventListener('load', () => window.print());
    </script>
</body>
</html>
