@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <h2 style="margin-top:0;">Edit Kategori</h2>

    <form action="{{ route('categories.update', $category->id) }}" method="POST" style="max-width:480px;">
        @csrf @method('PUT')

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Nama <span style="color:red">*</span></label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Deskripsi</label>
            <textarea name="description" rows="4" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">{{ old('description', $category->description) }}</textarea>
        </div>

        <button type="submit" style="background:#174a38; color:white; padding:10px 20px; border:none; border-radius:6px; cursor:pointer;">Perbarui</button>
        <a href="{{ route('categories.index') }}" style="margin-left:12px; color:#555;">Batal</a>
    </form>
@endsection

