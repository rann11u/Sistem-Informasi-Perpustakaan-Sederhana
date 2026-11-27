@extends('layouts.app')

@section('title', 'Edit Anggota')

@section('content')
    <div class="admin-card" style="max-width: 620px; margin: 0 auto;">
        <div class="page-header-action">
            <h2>Edit Data Anggota</h2>
            <a href="{{ route('members.index') }}" class="btn-ui btn-ui-secondary">← Kembali</a>
        </div>

        <form id="memberEditForm" action="{{ route('members.update', $member->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="form-field">
                <label>Kode Anggota <span style="color:var(--color-error)">*</span></label>
                <input type="text" id="member_code" name="member_code" value="{{ old('member_code', $member->member_code) }}" required>
                <div id="warning-member_code" style="color:var(--color-error); font-size:12px; font-weight:600; margin-top:5px; display:none;"></div>
            </div>

            <div class="form-field">
                <label>Nama Lengkap <span style="color:var(--color-error)">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $member->name) }}" required>
            </div>

            <div class="form-field">
                <label>Foto Profil</label>
                @if ($member->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($member->photo))
                    <div id="currentPhotoWrapper" style="margin-bottom:12px; display:flex; align-items:center; gap:12px;">
                        <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" 
                             style="width:56px; height:56px; border-radius:50%; object-fit:cover; border:2px solid var(--color-accent);">
                        <span style="font-size:13px; color:var(--color-muted);">Foto profil saat ini</span>
                    </div>
                @endif
                <input type="file" id="photoInput" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewProfilePhoto(event)">
                <small>Kosongkan jika tidak ingin mengubah foto. Format: JPG, PNG, WEBP (Maks. 2MB)</small>

                <div id="photoPreviewContainer" style="display:none; margin-top:12px; align-items:center; gap:12px;">
                    <img id="photoPreview" src="" alt="Pratinjau Foto Baru" style="width:56px; height:56px; border-radius:50%; object-fit:cover; border:2px solid var(--color-success);">
                    <span style="font-size:13px; color:var(--color-success); font-weight:600;">✓ Foto baru dipilih</span>
                </div>
            </div>

            <div class="form-field">
                <label>Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $member->email) }}">
                <div id="warning-email" style="color:var(--color-error); font-size:12px; font-weight:600; margin-top:5px; display:none;"></div>
            </div>

            <div class="form-field">
                <label>Alamat Domisili</label>
                <textarea id="address" name="address" rows="3">{{ old('address', $member->address) }}</textarea>
                <div id="warning-address" style="color:var(--badge-booked-color); font-size:12px; font-weight:600; margin-top:5px; display:none;"></div>
            </div>

            <div class="form-field">
                <label>Tanggal Bergabung</label>
                <input type="date" name="joined_at" value="{{ old('joined_at', $member->joined_at?->format('Y-m-d')) }}">
            </div>

            <div style="display:flex; align-items:center; gap:12px; margin-top:24px;">
                <button type="submit" id="submitBtn" class="btn-ui btn-ui-primary">Perbarui Data</button>
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
