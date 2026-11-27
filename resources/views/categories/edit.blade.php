@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <div class="admin-card" style="max-width: 580px; margin: 0 auto;">
        <div class="page-header-action">
            <h2>Edit Kategori</h2>
            <a href="{{ route('categories.index') }}" class="btn-ui btn-ui-secondary">← Kembali</a>
        </div>

        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="form-field">
                <label>Nama Kategori <span style="color:var(--color-error)">*</span></label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required>
            </div>

            <div class="form-field">
                <label>Deskripsi Kategori</label>
                <textarea name="description" rows="4">{{ old('description', $category->description) }}</textarea>
            </div>

            <div style="display:flex; align-items:center; gap:12px; margin-top:24px;">
                <button type="submit" class="btn-ui btn-ui-primary">Perbarui Kategori</button>
                <a href="{{ route('categories.index') }}" class="btn-ui btn-ui-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
