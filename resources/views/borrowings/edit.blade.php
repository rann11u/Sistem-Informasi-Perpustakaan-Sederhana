@extends('layouts.app')

@section('title', 'Proses Pengembalian')

@section('content')
    <div class="admin-card" style="max-width: 580px; margin: 0 auto;">
        <div class="page-header-action">
            <h2>Proses Pengembalian Buku</h2>
            <a href="{{ route('borrowings.index') }}" class="btn-ui btn-ui-secondary">← Kembali</a>
        </div>

        <div style="background:var(--table-head-bg); border:1px solid var(--card-border); border-radius:var(--radius-inner); padding:18px; margin-bottom:20px; font-size:14px;">
            <p style="margin:4px 0;"><strong>Peminjam:</strong> {{ $borrowing->member->name ?? '-' }} ({{ $borrowing->member->member_code ?? '-' }})</p>
            <p style="margin:4px 0; color:var(--color-muted);"><strong>Tanggal Pinjam:</strong> {{ $borrowing->borrow_date->format('d/m/Y') }}</p>
            <p style="margin:4px 0; color:var(--color-muted);"><strong>Jatuh Tempo:</strong> {{ $borrowing->due_date->format('d/m/Y') }}</p>
            <p style="margin:6px 0 0;"><strong>Buku Dipinjam:</strong>
                @foreach ($borrowing->details as $detail)
                    <span style="display:inline-block; margin-right:6px;">{{ $detail->book->title ?? '-' }} ({{ $detail->quantity }} buku)</span>
                @endforeach
            </p>
        </div>

        <form action="{{ route('borrowings.update', $borrowing->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="form-field">
                <label>Tanggal Pengembalian Riil <span style="color:var(--color-error)">*</span></label>
                <input type="date" name="return_date" value="{{ old('return_date', date('Y-m-d')) }}" required>
            </div>

            <div style="display:flex; align-items:center; gap:12px; margin-top:24px;">
                <button type="submit" class="btn-ui btn-ui-primary">Konfirmasi Pengembalian</button>
                <a href="{{ route('borrowings.index') }}" class="btn-ui btn-ui-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
