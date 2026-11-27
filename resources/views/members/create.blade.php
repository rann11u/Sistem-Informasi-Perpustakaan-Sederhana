@extends('layouts.app')

@section('title', 'Tambah Anggota')

@section('content')
    <div class="admin-card" style="max-width: 620px; margin: 0 auto;">
        <div class="page-header-action">
            <h2>Tambah Anggota Baru</h2>
            <a href="{{ route('members.index') }}" class="btn-ui btn-ui-secondary">← Kembali</a>
        </div>

        <form id="memberForm" action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-field">
                <label>Kode Anggota <span style="color:var(--color-error)">*</span></label>
                <input type="text" id="member_code" name="member_code" value="{{ old('member_code') }}" placeholder="Contoh: MBR-001" required>
                <div id="warning-member_code" style="color:var(--color-error); font-size:12px; font-weight:600; margin-top:5px; display:none;"></div>
            </div>

            <div class="form-field">
                <label>Nama Lengkap <span style="color:var(--color-error)">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap anggota" required>
            </div>

            <div class="form-field">
                <label>Foto Profil</label>
                <input type="file" id="photoInput" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewProfilePhoto(event)">
                <small>Format: JPG, JPEG, PNG, WEBP (Maks. 2MB)</small>

                <div id="photoPreviewContainer" style="display:none; margin-top:12px; align-items:center; gap:12px;">
                    <img id="photoPreview" src="" alt="Pratinjau Foto" style="width:64px; height:64px; border-radius:50%; object-fit:cover; border:2px solid var(--color-accent);">
                    <span style="font-size:13px; color:var(--color-success); font-weight:600;">✓ Foto siap di-upload</span>
                </div>
            </div>

            <div class="form-field">
                <label>Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Contoh: anggota@email.com">
                <div id="warning-email" style="color:var(--color-error); font-size:12px; font-weight:600; margin-top:5px; display:none;"></div>
            </div>

            <div class="form-field">
                <label>Alamat Domisili</label>
                <textarea id="address" name="address" rows="3" placeholder="Masukkan alamat domisili saat ini">{{ old('address') }}</textarea>
                <div id="warning-address" style="color:var(--badge-booked-color); font-size:12px; font-weight:600; margin-top:5px; display:none;"></div>
            </div>

            <div class="form-field">
                <label>Tanggal Bergabung</label>
                <input type="date" name="joined_at" value="{{ old('joined_at', date('Y-m-d')) }}">
            </div>

            <div style="display:flex; align-items:center; gap:12px; margin-top:24px;">
                <button type="submit" id="submitBtn" class="btn-ui btn-ui-primary">Simpan Anggota</button>
                <a href="{{ route('members.index') }}" class="btn-ui btn-ui-secondary">Batal</a>
            </div>
        </form>
    </div>

    <script>
        function previewProfilePhoto(event) {
            const input = event.target;
            const container = document.getElementById('photoPreviewContainer');
            const preview = document.getElementById('photoPreview');

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
