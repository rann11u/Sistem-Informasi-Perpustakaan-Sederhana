@extends('layouts.app')

@section('title', 'Edit Anggota')

@section('content')
    <h2 style="margin-top:0;">Edit Data Anggota</h2>

    <form id="memberEditForm" action="{{ route('members.update', $member->id) }}" method="POST" enctype="multipart/form-data" style="max-width:520px;">
        @csrf @method('PUT')

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Kode Anggota <span style="color:red">*</span></label>
            <input type="text" id="member_code" name="member_code" value="{{ old('member_code', $member->member_code) }}" 
                   style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px; transition:border-color 0.2s;" required>
            <div id="warning-member_code" style="color:#dc2626; font-size:13px; font-weight:600; margin-top:5px; display:none;"></div>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Nama Lengkap <span style="color:red">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $member->name) }}" 
                   style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px;" required>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Foto Profil</label>
            @if ($member->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($member->photo))
                <div id="currentPhotoWrapper" style="margin-bottom:8px; display:flex; align-items:center; gap:12px;">
                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" 
                         style="width:60px; height:60px; border-radius:50%; object-fit:cover; border:2px solid #174a38;">
                    <span style="font-size:13px; color:#6b7280;">Foto profil saat ini</span>
                </div>
            @endif
            <input type="file" id="photoInput" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp" 
                   style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px; background:#fafafa; font-size:13px;"
                   onchange="previewProfilePhoto(event)">
            <small style="color:#6b7280; display:block; margin-top:4px;">Kosongkan jika tidak ingin mengubah foto. Format: JPG, PNG, WEBP (Maks. 2MB)</small>

            <!-- Pratinjau Foto Baru -->
            <div id="photoPreviewContainer" style="display:none; margin-top:10px; align-items:center; gap:12px;">
                <img id="photoPreview" src="" alt="Pratinjau Foto Baru" 
                     style="width:60px; height:60px; border-radius:50%; object-fit:cover; border:2px solid #10b981; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                <span style="font-size:13px; color:#10b981; font-weight:600;">✓ Foto baru dipilih</span>
            </div>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $member->email) }}" 
                   style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px; transition:border-color 0.2s;">
            <div id="warning-email" style="color:#dc2626; font-size:13px; font-weight:600; margin-top:5px; display:none;"></div>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Telepon</label>
            <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" 
                   style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px;">
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Alamat</label>
            <textarea id="address" name="address" rows="3" 
                      style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px; transition:border-color 0.2s;">{{ old('address', $member->address) }}</textarea>
            <div id="warning-address" style="color:#d97706; font-size:13px; font-weight:600; margin-top:5px; display:none;"></div>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Tanggal Bergabung</label>
            <input type="date" name="joined_at" value="{{ old('joined_at', $member->joined_at?->format('Y-m-d')) }}" 
                   style="width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; font-size:14px;">
        </div>

        <div style="display:flex; align-items:center; gap:12px;">
            <button type="submit" id="submitBtn" style="background:#174a38; color:white; padding:10px 24px; border:none; border-radius:6px; font-weight:600; cursor:pointer;">Perbarui Data</button>
            <a href="{{ route('members.index') }}" style="color:#555; text-decoration:none;">Batal</a>
        </div>
    </form>

    <script>
        // Pratinjau Foto Baru saat user memilih file
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
        const currentMemberId = "{{ $member->id }}";
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

            fetch(`${checkUrl}?field=${field}&value=${encodeURIComponent(val)}&ignore_id=${currentMemberId}`)
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

        document.getElementById('memberEditForm').addEventListener('submit', function(e) {
            if (duplicateFlags.member_code || duplicateFlags.email) {
                e.preventDefault();
                alert('Mohon periksa kembali form Anda. Terdapat data (Kode atau Email) yang sudah digunakan anggota lain!');
            }
        });
    </script>
@endsection
