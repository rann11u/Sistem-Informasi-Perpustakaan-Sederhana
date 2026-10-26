@extends('layouts.app')

@section('title', 'Catat Peminjaman')

@section('content')
    <h2 style="margin-top:0;">Catat Peminjaman Baru</h2>

    <form action="{{ route('borrowings.store') }}" method="POST" style="max-width:520px;">
        @csrf

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Anggota <span style="color:red">*</span></label>
            <select name="member_id" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
                <option value="">-- Pilih Anggota --</option>
                @foreach ($members as $member)
                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                        {{ $member->member_code }} — {{ $member->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Buku <span style="color:red">*</span></label>
            <select name="book_id" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
                <option value="">-- Pilih Buku --</option>
                @foreach ($books as $book)
                    <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                        {{ $book->title }} (Stok: {{ $book->stock }})
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Jumlah <span style="color:red">*</span></label>
            <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Tanggal Pinjam <span style="color:red">*</span></label>
            <input type="date" name="borrow_date" value="{{ old('borrow_date', date('Y-m-d')) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Jatuh Tempo <span style="color:red">*</span></label>
            <input type="date" name="due_date" value="{{ old('due_date') }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
        </div>

        <button type="submit" style="background:#174a38; color:white; padding:10px 20px; border:none; border-radius:6px; cursor:pointer;">Simpan</button>
        <a href="{{ route('borrowings.index') }}" style="margin-left:12px; color:#555;">Batal</a>
    </form>
@endsection

