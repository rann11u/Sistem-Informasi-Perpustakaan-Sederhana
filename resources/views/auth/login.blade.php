<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Sistem Informasi Perpustakaan</title>
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

    <main class="auth-shell">
        <section class="auth-glass" aria-labelledby="login-title">
            <div class="auth-card-head">
                <h1 id="login-title" class="auth-card-title">Perpustakaan</h1>
                <p class="auth-card-sub">Silakan masuk dengan email dan password Anda</p>
            </div>

            {{-- Pesan berhasil logout atau notifikasi sukses lainnya --}}
            @if (session('success'))
                <div class="alert alert-success" role="status">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Pesan kesalahan jika login gagal (kredensial salah) --}}
            @if ($errors->has('loginError'))
                <div class="alert alert-error" role="alert">
                    {{ $errors->first('loginError') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="auth-form">
                @csrf

                {{-- Input Email --}}
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
                        placeholder="nama@email.com"
                        required
                        aria-required="true"
                        @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                        autofocus
                    >
                    @error('email')
                        <div id="email-error" class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Input Password --}}
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
                        placeholder="Masukkan password"
                        required
                        aria-required="true"
                        @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                    >
                    @error('password')
                        <div id="password-error" class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol Masuk --}}
                <button type="submit" class="btn-submit">Masuk</button>
            </form>

            <div class="auth-footer">
                <span>Belum memiliki akun anggota?</span>
                <a href="{{ route('register') }}" class="auth-link">Daftar di sini</a>
            </div>
        </section>
    </main>

</body>
</html>
