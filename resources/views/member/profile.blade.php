<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Portal Anggota</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f6f9;
            color: #263238;
        }

        .header {
            background-color: #174a38;
            color: white;
            padding: 16px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
        }

        .user-nav {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .nav-link {
            color: #dcebe3;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }

        /* Tombol Profil dengan Foto / Kosong jika belum ada */
        .btn-profile-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 2px solid #e5f1eb;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-color: #265a48;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
            flex-shrink: 0;
        }

        .btn-profile-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 0 0 3px rgba(229, 241, 235, 0.4);
        }

        .btn-profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-profile-avatar .empty-avatar {
            width: 18px;
            height: 18px;
            fill: #dcebe3;
        }

        .badge-code {
            background-color: #e5f1eb;
            color: #174a38;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: bold;
        }

        .btn-logout {
            background-color: #dc2626;
            color: white;
            border: none;
            padding: 7px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
        }

        .btn-logout:hover {
            background-color: #b91c1c;
        }

        .container {
            width: 90%;
            max-width: 950px;
            margin: 32px auto;
        }

        .alert-success {
            background-color: #e4f6e9;
            color: #176534;
            padding: 14px 18px;
            border-radius: 8px;
            border: 1px solid #bbf0cb;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 24px;
        }

        @media (max-width: 768px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
        }

        .photo-card {
            text-align: center;
        }

        .avatar-wrapper {
            width: 140px;
            height: 140px;
            margin: 0 auto 16px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #e5f1eb;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-placeholder {
            font-size: 48px;
            font-weight: bold;
            color: #174a38;
        }

        .member-name {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 6px;
        }

        .member-email {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 14px;
        }

        .member-info-box {
            background-color: #f9fafb;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            text-align: left;
            margin-top: 14px;
            border: 1px solid #f3f4f6;
        }

        .member-info-box div {
            margin-bottom: 6px;
        }

        .member-info-box div:last-child {
            margin-bottom: 0;
        }

        .form-title {
            font-size: 18px;
            font-weight: bold;
            color: #174a38;
            margin-bottom: 20px;
            border-bottom: 2px solid #e5f1eb;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: bold;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #174a38;
            box-shadow: 0 0 0 3px rgba(23, 74, 56, 0.15);
        }

        .form-control[readonly] {
            background-color: #f9fafb;
            color: #6b7280;
            cursor: not-allowed;
        }

        .file-hint {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        .field-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
        }

        .btn-submit {
            background-color: #174a38;
            color: white;
            border: none;
            padding: 11px 22px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-submit:hover {
            background-color: #103629;
        }
    </style>
</head>
<body>

    <header class="header">
        <h1>Sistem Informasi Perpustakaan</h1>
        <div class="user-nav">
            <a href="{{ route('member.books') }}" class="nav-link">Katalog Buku</a>
            <a href="{{ route('member.history') }}" class="nav-link">Riwayat Pinjam</a>
            
            {{-- Tombol Profil: Menampilkan foto yang di-up, kosong jika belum ada --}}
            <a href="{{ route('member.profile') }}" class="btn-profile-avatar" title="Profil Saya">
                @if($member && $member->photo)
                    <img src="{{ asset('storage/' . $member->photo) }}" alt="Foto Profil">
                @else
                    <svg class="empty-avatar" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                @endif
            </a>

            <span>Halo, <strong>{{ $user->name }}</strong></span>
            @if($member && $member->member_code)
                <span class="badge-code">{{ $member->member_code }}</span>
            @endif
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-logout">Keluar</button>
            </form>
        </div>
    </header>

    <main class="container">
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="profile-grid">
            
            {{-- Kartu Info Foto & Identitas Anggota --}}
            <div class="card photo-card">
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
                    <div class="member-info-box">
                        <div><strong>Kode Anggota:</strong> {{ $member->member_code }}</div>
                        <div><strong>Status:</strong> <span style="color:#176534; font-weight:bold;">Anggota Aktif</span></div>
                        <div><strong>Bergabung:</strong> {{ $member->joined_at ? $member->joined_at->format('d M Y') : '-' }}</div>
                    </div>
                @endif
            </div>

            {{-- Kartu Form Edit Profil & Upload Foto --}}
            <div class="card">
                <h2 class="form-title">Edit Profil Anggota</h2>

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

                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                </form>
            </div>

        </div>
    </main>

    {{-- Script untuk preview langsung foto yang dipilih sebelum diupload --}}
    <script>
        const photoInput = document.getElementById('photo');
        const avatarPreview = document.getElementById('avatarPreview');
        const avatarPlaceholder = document.getElementById('avatarPlaceholder');

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
    </script>

</body>
</html>
