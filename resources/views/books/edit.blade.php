@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
    <div class="admin-card" style="max-width: 680px; margin: 0 auto;">
        <div class="page-header-action">
            <h2>Edit Data Buku</h2>
            <a href="{{ route('books.index') }}" class="btn-ui btn-ui-secondary">← Kembali</a>
        </div>

        <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="form-field">
                <label>Kategori <span style="color:var(--color-error)">*</span></label>
                <select name="category_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-field">
                <label>Judul Buku <span style="color:var(--color-error)">*</span></label>
                <input type="text" name="title" value="{{ old('title', $book->title) }}" required>
            </div>

            {{-- UI Modern Upload Sampul Buku --}}
            <div class="form-field">
                <label>Sampul / Foto Buku</label>
                @if ($book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
                    <div style="margin-bottom:12px; display:flex; align-items:center; gap:14px; background:var(--table-head-bg); padding:10px 14px; border-radius:var(--radius-inner); border:1px solid var(--card-border);">
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" 
                             style="width:58px; height:82px; object-fit:cover; border-radius:4px; border:1px solid var(--card-border);">
                        <div>
                            <span style="font-size:13px; font-weight:600; display:block;">Sampul buku saat ini</span>
                            <span style="font-size:12px; color:var(--color-muted);">Pilih file baru di bawah jika ingin mengganti</span>
                        </div>
                    </div>
                @endif

                <div class="file-upload-dropzone" onclick="document.getElementById('coverInput').click()">
                    <input type="file" id="coverInput" name="cover_image" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewBookCover(event)">
                    <div class="dropzone-content">
                        <div class="dropzone-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                        </div>
                        <span class="dropzone-title" id="dropzoneLabel">Klik untuk memilih file gambar baru</span>
                        <span class="dropzone-sub">Format: JPG, PNG, WEBP (Maksimal 2MB)</span>
                    </div>
                </div>

                {{-- Pratinjau Sampul Baru --}}
                <div id="coverPreviewContainer" style="display:none; margin-top:14px; align-items:center; gap:14px; background:var(--table-head-bg); padding:10px 14px; border-radius:var(--radius-inner); border:1px solid var(--card-border);">
                    <img id="coverPreview" src="" alt="Pratinjau Sampul Baru" style="width:58px; height:82px; object-fit:cover; border-radius:4px; border:2px solid var(--color-success);">
                    <div style="flex:1;">
                        <span style="font-size:13px; color:var(--color-success); font-weight:600; display:block;">Sampul baru siap di-upload</span>
                        <span id="coverFileName" style="font-size:12px; color:var(--color-muted);"></span>
                    </div>
                </div>
            </div>

            <div class="form-field">
                <label>Penulis <span style="color:var(--color-error)">*</span></label>
                <input type="text" name="author" value="{{ old('author', $book->author) }}" required>
            </div>

            <div class="form-field">
                <label>Penerbit</label>
                <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-field">
                    <label>Tahun Terbit</label>
                    <input type="number" name="publication_year" value="{{ old('publication_year', $book->publication_year) }}" min="1000" max="{{ date('Y') }}">
                </div>
                <div class="form-field">
                    <label>ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}">
                </div>
            </div>

            <div class="form-field">
                <label>Stok Buku <span style="color:var(--color-error)">*</span></label>
                <input type="number" name="stock" value="{{ old('stock', $book->stock) }}" min="0" required>
            </div>

            <div class="form-field">
                <label>Sinopsis Buku</label>
                <textarea name="synopsis" rows="4">{{ old('synopsis', $book->synopsis) }}</textarea>
            </div>

            <div style="display:flex; align-items:center; gap:12px; margin-top:24px;">
                <button type="submit" class="btn-ui btn-ui-primary">Perbarui Buku</button>
                <a href="{{ route('books.index') }}" class="btn-ui btn-ui-secondary">Batal</a>
            </div>
        </form>
    </div>

    <script>
        function previewBookCover(event) {
            const input = event.target;
            const container = document.getElementById('coverPreviewContainer');
            const preview = document.getElementById('coverPreview');
            const fileName = document.getElementById('coverFileName');
            const label = document.getElementById('dropzoneLabel');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'flex';
                    if (fileName) fileName.textContent = file.name;
                    if (label) label.textContent = 'Ganti file: ' + file.name;
                };
                reader.readAsDataURL(file);
            } else {
                container.style.display = 'none';
            }
        }
    </script>
@endsection
