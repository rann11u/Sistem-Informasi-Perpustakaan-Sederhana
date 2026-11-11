@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
    <h2 style="margin-top:0;">Edit Data Buku</h2>

    <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data" style="max-width:560px;">
        @csrf @method('PUT')

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Kategori <span style="color:red">*</span></label>
            <select name="category_id" style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px;" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Judul Buku <span style="color:red">*</span></label>
            <input type="text" name="title" value="{{ old('title', $book->title) }}" 
                   style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px;" required>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Sampul / Foto Buku</label>
            @if ($book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
                <div style="margin-bottom:10px; display:flex; align-items:center; gap:14px;">
                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" 
                         style="width:65px; height:90px; object-fit:cover; border-radius:4px; border:1px solid #d1d5db; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                    <span style="font-size:13px; color:#6b7280;">Sampul buku saat ini</span>
                </div>
            @endif
            <input type="file" id="coverInput" name="cover_image" accept="image/jpeg,image/png,image/jpg,image/webp" 
                   style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px; background:#fafafa; font-size:13px;"
                   onchange="previewBookCover(event)">
            <small style="color:#6b7280; display:block; margin-top:4px;">Kosongkan jika tidak ingin mengubah sampul. Format: JPG, PNG, WEBP (Maks. 2MB)</small>

            <!-- Pratinjau Sampul Baru -->
            <div id="coverPreviewContainer" style="display:none; margin-top:10px; align-items:center; gap:14px;">
                <img id="coverPreview" src="" alt="Pratinjau Sampul Baru" 
                     style="width:65px; height:90px; object-fit:cover; border-radius:4px; border:2px solid #10b981; box-shadow:0 2px 4px rgba(0,0,0,0.15);">
                <span style="font-size:13px; color:#10b981; font-weight:600;">✓ Sampul baru dipilih</span>
            </div>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Penulis <span style="color:red">*</span></label>
            <input type="text" name="author" value="{{ old('author', $book->author) }}" 
                   style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px;" required>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Penerbit</label>
            <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}" 
                   style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px;">
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:14px;">
            <div>
                <label style="display:block; margin-bottom:4px; font-weight:bold;">Tahun Terbit</label>
                <input type="number" name="publication_year" value="{{ old('publication_year', $book->publication_year) }}" min="1000" max="{{ date('Y') }}" 
                       style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px;">
            </div>
            <div>
                <label style="display:block; margin-bottom:4px; font-weight:bold;">ISBN</label>
                <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" 
                       style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px;">
            </div>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Stok Buku <span style="color:red">*</span></label>
            <input type="number" name="stock" value="{{ old('stock', $book->stock) }}" min="0" 
                   style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px;" required>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Sinopsis Buku</label>
            <textarea name="synopsis" rows="4" 
                      style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px; line-height:1.5;">{{ old('synopsis', $book->synopsis) }}</textarea>
        </div>

        <div style="display:flex; align-items:center; gap:12px;">
            <button type="submit" style="background:#174a38; color:white; padding:10px 24px; border:none; border-radius:6px; font-weight:600; cursor:pointer;">Perbarui Buku</button>
            <a href="{{ route('books.index') }}" style="color:#555; text-decoration:none;">Batal</a>
        </div>
    </form>

    <script>
        function previewBookCover(event) {
            const input = event.target;
            const container = document.getElementById('coverPreviewContainer');
            const preview = document.getElementById('coverPreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'flex';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                container.style.display = 'none';
            }
        }
    </script>
@endsection
