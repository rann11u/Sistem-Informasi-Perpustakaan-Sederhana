@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
    <div class="admin-card" style="max-width: 580px; margin: 0 auto;">
        <div class="page-header-action">
            <h2>Tambah Kategori Baru</h2>
            <a href="{{ route('categories.index') }}" class="btn-ui btn-ui-secondary">← Kembali</a>
        </div>

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="form-field">
                <label>Nama Kategori <span style="color:var(--color-error)">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Pemrograman, Fiksi, Sains" required>
            </div>

            <div class="form-field">
                <label>Deskripsi Kategori</label>
                <textarea name="description" rows="4" placeholder="Deskripsi atau cakupan kategori buku...">{{ old('description') }}</textarea>
            </div>

            <div style="display:flex; align-items:center; gap:12px; margin-top:24px;">
                <button type="submit" class="btn-ui btn-ui-primary">Simpan Kategori</button>
                <a href="{{ route('categories.index') }}" class="btn-ui btn-ui-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
