<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Anggota — Sistem Informasi Perpustakaan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Wix+Madefor+Display:wght@400;500;600&family=Wix+Madefor+Text:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
</head>
<body>

    <div class="dusk-backdrop" aria-hidden="true">
        <img src="{{ asset('images/image.png') }}" alt="" class="dusk-backdrop__img">
        <div class="dusk-backdrop__scrim"></div>
    </div>

    <main class="auth-shell auth-shell--wide">
        <section class="auth-glass" aria-labelledby="register-title">
            <div class="auth-card-head">
                <h1 id="register-title" class="auth-card-title">Daftar Anggota Baru</h1>
                <p class="auth-card-sub">Kode Anggota otomatis dibuatkan oleh sistem</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="auth-form">
                @csrf

                {{-- Nama Lengkap --}}
                <div class="form-group">
                    <label for="name" class="form-label">
                        <span>Nama Lengkap</span>
                        <span class="field-required">Wajib</span>
                    </label>
                    <input
                        class="form-control"
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Budi Santoso"
                        required
                        aria-required="true"
                        @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
                        autofocus
                    >
                    @error('name')
                        <div id="name-error" class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-row">
                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <span>Email</span>
                            <span class="field-required">Wajib</span>
                        </label>
                        <input
                            class="form-control"
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="budi@example.com"
                            required
                            aria-required="true"
                            @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                        >
                        @error('email')
                            <div id="email-error" class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nomor Telepon --}}
                    <div class="form-group">
                        <label for="phone" class="form-label">
                            <span>Nomor Telepon / WhatsApp</span>
                        </label>
                        <input
                            class="form-control"
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="Contoh: 08123456789"
                            @error('phone') aria-invalid="true" aria-describedby="phone-error" @enderror
                        >
                        @error('phone')
                            <div id="phone-error" class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="form-group">
                    <label for="address" class="form-label">
                        <span>Alamat Tempat Tinggal</span>
                    </label>
                    <textarea
                        class="form-control"
                        id="address"
                        name="address"
                        rows="2"
                        placeholder="Alamat domisili"
                        @error('address') aria-invalid="true" aria-describedby="address-error" @enderror
                    >{{ old('address') }}</textarea>
                    @error('address')
                        <div id="address-error" class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-row">
                    {{-- Password --}}
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <span>Password</span>
                            <span class="field-required">Wajib</span>
                        </label>
                        <input
                            class="form-control"
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            required
                            aria-required="true"
                            aria-describedby="password-hint @error('password') password-error @enderror"
                            @error('password') aria-invalid="true" @enderror
                        >
                        @error('password')
                            <div id="password-error" class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <span>Konfirmasi Password</span>
                            <span class="field-required">Wajib</span>
                        </label>
                        <input
                            class="form-control"
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ketik ulang password Anda"
                            required
                            aria-required="true"
                        >
                    </div>
                </div>

                <div id="password-hint" class="field-hint">
                    *Wajib minimal 8 karakter, kombinasi huruf besar, huruf kecil, angka, dan simbol (@$!%*#?&amp;).
                </div>

                <button type="submit" class="btn-submit">Daftar Sebagai Anggota</button>
            </form>

            <div class="auth-footer">
                <span>Sudah terdaftar?</span>
                <a href="{{ route('login') }}" class="auth-link">Masuk di sini</a>
            </div>
        </section>
    </main>

</body>
</html>
