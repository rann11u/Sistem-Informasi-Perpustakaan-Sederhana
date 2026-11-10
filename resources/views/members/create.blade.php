@extends('layouts.app')

@section('title', 'Tambah Anggota')

@section('content')
    <h2 style="margin-top:0;">Tambah Anggota Baru</h2>

    <form id="memberForm" action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data" style="max-width:520px;">
        @csrf

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Kode Anggota <span style="color:red">*</span></label>
            <input type="text" id="member_code" name="member_code" value="{{ old('member_code') }}" 
                   style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px; transition:border-color 0.2s;" 
                   placeholder="Contoh: 01 atau M-001" required>
            <div id="warning-member_code" style="color:#dc2626; font-size:13px; font-weight:600; margin-top:5px; display:none;"></div>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Nama Lengkap <span style="color:red">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                   style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px;" 
                   placeholder="Masukkan nama lengkap" required>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Foto Profil</label>
            <input type="file" id="photoInput" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp" 
                   style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px; background:#fafafa; font-size:13px;"
                   onchange="previewProfilePhoto(event)">
            <small style="color:#6b7280; display:block; margin-top:4px;">Format: JPG, JPEG, PNG, WEBP (Maks. 2MB)</small>

            <!-- Pratinjau Foto Langsung -->
            <div id="photoPreviewContainer" style="display:none; margin-top:10px; align-items:center; gap:12px;">
                <img id="photoPreview" src="" alt="Pratinjau Foto" 
                     style="width:70px; height:70px; border-radius:50%; object-fit:cover; border:2px solid #174a38; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                <span style="font-size:13px; color:#174a38; font-weight:600;">✓ Foto siap di-upload</span>
            </div>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" 
                   style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px; transition:border-color 0.2s;" 
                   placeholder="Contoh: nama@gmail.com">
            <div id="warning-email" style="color:#dc2626; font-size:13px; font-weight:600; margin-top:5px; display:none;"></div>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Telepon</label>
            <input type="text" name="phone" value="{{ old('phone') }}" 
                   style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px;" 
                   placeholder="Contoh: 08123456789">
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Alamat</label>
            <textarea id="address" name="address" rows="3" 
                      style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px; transition:border-color 0.2s;" 
                      placeholder="Masukkan alamat domisili">{{ old('address') }}</textarea>
            <div id="warning-address" style="color:#d97706; font-size:13px; font-weight:600; margin-top:5px; display:none;"></div>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Tanggal Bergabung</label>
            <input type="date" name="joined_at" value="{{ old('joined_at', date('Y-m-d')) }}" 
                   style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px;">
        </div>

        <div style="display:flex; align-items:center; gap:12px;">
            <button type="submit" id="submitBtn" style="background:#174a38; color:white; padding:10px 24px; border:none; border-radius:6px; font-weight:600; cursor:pointer;">Simpan</button>
            <a href="{{ route('members.index') }}" style="color:#555; text-decoration:none;">Batal</a>
        </div>
    </form>

    <script>
        // Pratinjau Foto Langsung saat user memilih file
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

        // Pengecekan Duplikasi Real-time saat Mengetik (Debounce)
        const checkUrl = "{{ route('members.check-duplicate') }}";
        const duplicateFlags = { member_code: false, email: false };

        function checkFieldDuplicate(field, inputEl) {
            const val = inputEl.value.trim();
            const warningEl = document.getElementById('warning-' + field);

            if (!val) {
                warningEl.style.display = 'none';
                inputEl.style.borderColor = '#ccc';
                duplicateFlags[field] = false;
                return;
            }

            fetch(`${checkUrl}?field=${field}&value=${encodeURIComponent(val)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.exists) {
                        warningEl.innerHTML = data.message;
                        warningEl.style.display = 'block';
                        inputEl.style.borderColor = '#dc2626';
                        duplicateFlags[field] = true;
                    } else {
                        warningEl.style.display = 'none';
                        inputEl.style.borderColor = '#10b981';
                        duplicateFlags[field] = false;
                        setTimeout(() => {
                            if (!duplicateFlags[field]) inputEl.style.borderColor = '#ccc';
                        }, 1200);
                    }
                })
                .catch(err => console.error(err));
        }

        // Debounce helper
        function createDebouncedCheck(field) {
            let timeout = null;
            return function(e) {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    checkFieldDuplicate(field, e.target);
                }, 350);
            };
        }

        document.getElementById('member_code').addEventListener('input', createDebouncedCheck('member_code'));
        document.getElementById('email').addEventListener('input', createDebouncedCheck('email'));
        document.getElementById('address').addEventListener('input', createDebouncedCheck('address'));

        // Cegah submit jika ada kode atau email yang sudah terdaftar
        document.getElementById('memberForm').addEventListener('submit', function(e) {
            if (duplicateFlags.member_code || duplicateFlags.email) {
                e.preventDefault();
                alert('Mohon periksa kembali form Anda. Terdapat data (Kode atau Email) yang sudah digunakan!');
            }
        });
    </script>
@endsection
