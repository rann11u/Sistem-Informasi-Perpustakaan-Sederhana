@extends('layouts.app')

@section('title', 'Catat Peminjaman')

@section('content')
    <div class="admin-card" style="max-width: 600px; margin: 0 auto;">
        <div class="page-header-action">
            <h2>Catat Peminjaman Baru</h2>
            <a href="{{ route('borrowings.index') }}" class="btn-ui btn-ui-secondary">← Kembali</a>
        </div>

        <form action="{{ route('borrowings.store') }}" method="POST">
            @csrf

            <div class="form-field">
                <label>Pilih Anggota <span style="color:var(--color-error)">*</span></label>
                <select name="member_id" required>
                    <option value="">-- Pilih Anggota --</option>
                    @foreach ($members as $member)
                        <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                            {{ $member->member_code }} — {{ $member->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-field">
                <label>Pilih Buku <span style="color:var(--color-error)">*</span></label>
                <select name="book_id" required>
                    <option value="">-- Pilih Buku --</option>
                    @foreach ($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->title }} (Stok Tersedia: {{ $book->stock }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-field">
                <label>Jumlah Eksemplar <span style="color:var(--color-error)">*</span></label>
                <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
            </div>

            <div class="form-field">
                <label>Tanggal Pinjam <span style="color:var(--color-error)">*</span></label>
                <input type="date" id="borrow_date" name="borrow_date" value="{{ old('borrow_date', date('Y-m-d')) }}" required>
            </div>

            <div class="form-field">
                <label>Jatuh Tempo (Otomatis 1 Minggu)</label>
                <input type="date" id="due_date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}" style="opacity:0.8; cursor:not-allowed;" readonly>
                <small>*Jatuh tempo otomatis dihitung 7 hari ke depan dari tanggal pinjam.</small>
            </div>

            <div style="display:flex; align-items:center; gap:12px; margin-top:24px;">
                <button type="submit" class="btn-ui btn-ui-primary">Simpan Transaksi</button>
                <a href="{{ route('borrowings.index') }}" class="btn-ui btn-ui-secondary">Batal</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('borrow_date').addEventListener('change', function() {
            if (this.value) {
                const borrowDate = new Date(this.value);
                borrowDate.setDate(borrowDate.getDate() + 7);
                const yyyy = borrowDate.getFullYear();
                const mm = String(borrowDate.getMonth() + 1).padStart(2, '0');
                const dd = String(borrowDate.getDate()).padStart(2, '0');
                document.getElementById('due_date').value = `${yyyy}-${mm}-${dd}`;
            }
        });
    </script>
@endsection
