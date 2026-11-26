@extends('layouts.member')

@section('title', 'Profil Saya — Portal Anggota')
@section('main_class', 'member-main--narrow')

@section('content')
    <div class="page-head">
        <div>
            <h1 class="page-title">Profil Anggota</h1>
            <p class="page-subtitle">Kelola foto profil, nomor telepon, dan alamat domisili Anda.</p>
        </div>
    </div>

    <div class="profile-grid">
        {{-- Kartu Info Foto & Identitas Anggota --}}
        <aside class="card photo-card">
            <div class="avatar-wrapper">
                @if($member && $member->photo)
                    <img id="avatarPreview" src="{{ asset('storage/' . $member->photo) }}" alt="Foto Profil" class="avatar-img">
                @else
                    <img id="avatarPreview" src="" alt="Foto Profil" class="avatar-img" style="display:none;">
                    <span id="avatarPlaceholder" class="avatar-placeholder">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                @endif
            </div>

            <div class="member-name">{{ $user->name }}</div>
            <div class="member-email">{{ $user->email }}</div>

            @if($member)
                <div class="member-info-list">
                    <div class="member-info-row">
                        <span>Kode Anggota</span>
                        <span>{{ $member->member_code }}</span>
                    </div>
                    <div class="member-info-row">
                        <span>Status</span>
                        <span class="status-active">Anggota Aktif</span>
                    </div>
                    <div class="member-info-row">
                        <span>Bergabung</span>
                        <span>{{ $member->joined_at ? $member->joined_at->format('d M Y') : '-' }}</span>
                    </div>
                </div>
            @endif
        </aside>

        {{-- Kartu Form Edit Profil & Upload Foto --}}
        <section class="card" aria-labelledby="profile-form-title">
            <h2 id="profile-form-title" class="form-title">Edit Profil Anggota</h2>

            <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Nama Lengkap (Read-only) --}}
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" class="form-control" value="{{ $user->name }}" readonly>
                    <div class="file-hint">*Nama terdaftar sesuai identitas anggota perpustakaan.</div>
                </div>

                {{-- Email (Read-only) --}}
                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" id="email" class="form-control" value="{{ $user->email }}" readonly>
                </div>

                {{-- Upload Foto Profil --}}
                <div class="form-group">
                    <label for="photo">Ganti Foto Profil</label>
                    <input type="file" id="photo" name="photo" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
                    <div class="file-hint">Format yang didukung: JPG, PNG, WEBP. Maksimal ukuran file: 2 MB.</div>
                    @error('photo')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nomor Telepon --}}
                <div class="form-group">
                    <label for="phone">Nomor Telepon / WhatsApp</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        class="form-control"
                        value="{{ old('phone', $member->phone ?? '') }}"
                        placeholder="Contoh: 08123456789"
                    >
                    @error('phone')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div class="form-group">
                    <label for="address">Alamat Domisili</label>
                    <textarea
                        id="address"
                        name="address"
                        class="form-control"
                        rows="3"
                        placeholder="Masukkan alamat lengkap domisili saat ini"
                    >{{ old('address', $member->address ?? '') }}</textarea>
                    @error('address')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </form>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    const photoInput = document.getElementById('photo');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarPlaceholder = document.getElementById('avatarPlaceholder');

    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    avatarPreview.src = event.target.result;
                    avatarPreview.style.display = 'block';
                    if (avatarPlaceholder) {
                        avatarPlaceholder.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endpush
