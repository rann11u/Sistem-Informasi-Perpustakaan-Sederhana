@extends('layouts.app')

@section('title', 'Proses Pengembalian')

@section('content')
    <h2 style="margin-top:0;">Proses Pengembalian Buku</h2>

    <div style="background:#f9f9f9; border:1px solid #eee; border-radius:8px; padding:16px; margin-bottom:20px; max-width:520px; font-size:14px;">
        <p style="margin:4px 0;"><strong>Anggota:</strong> {{ $borrowing->member->name ?? '-' }}</p>
        <p style="margin:4px 0;"><strong>Tanggal Pinjam:</strong> {{ $borrowing->borrow_date->format('d/m/Y') }}</p>
        <p style="margin:4px 0;"><strong>Jatuh Tempo:</strong> {{ $borrowing->due_date->format('d/m/Y') }}</p>
        <p style="margin:4px 0;"><strong>Buku:</strong>
            @foreach ($borrowing->details as $detail)
                {{ $detail->book->title ?? '-' }} ({{ $detail->quantity }} eks.)
            @endforeach
        </p>
    </div>

    <form action="{{ route('borrowings.update', $borrowing->id) }}" method="POST" style="max-width:480px;">
        @csrf @method('PUT')

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Tanggal Pengembalian <span style="color:red">*</span></label>
            <input type="date" name="return_date" value="{{ old('return_date', date('Y-m-d')) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
        </div>

        <button type="submit" style="background:#174a38; color:white; padding:10px 20px; border:none; border-radius:6px; cursor:pointer;">Konfirmasi Pengembalian</button>
        <a href="{{ route('borrowings.index') }}" style="margin-left:12px; color:#555;">Batal</a>
    </form>
@endsection

