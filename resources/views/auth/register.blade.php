<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Anggota - Sistem Informasi Perpustakaan</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 24px;
        }

        .auth-card {
            background-color: white;
            width: 100%;
            max-width: 480px;
            padding: 32px;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
            border-top: 5px solid #174a38;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .auth-header h1 {
            font-size: 22px;
            color: #174a38;
            margin-bottom: 6px;
        }

        .auth-header p {
            font-size: 13px;
            color: #6b7280;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: bold;
            color: #374151;
        }

        .form-group input, 
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #174a38;
            box-shadow: 0 0 0 3px rgba(23, 74, 56, 0.15);
        }

        .field-error {
            color: #a12222;
            font-size: 12px;
            margin-top: 4px;
        }

        .password-hint {
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
            line-height: 1.4;
        }

        .btn-submit {
            width: 100%;
            background-color: #174a38;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: #103629;
        }

        .auth-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #6b7280;
        }

        .auth-footer a {
            color: #174a38;
            font-weight: bold;
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="auth-header">
            <h1>Daftar Anggota Baru</h1>
            <p>Kode Anggota otomatis dibuatkan oleh sistem</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            {{-- Nama Lengkap --}}
            <div class="form-group">
                <label for="name">Nama Lengkap <span style="color:red">*</span></label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}" 
                    placeholder="Contoh: Budi Santoso" 
                    required 
                    autofocus
                >
                @error('name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label for="email">Email <span style="color:red">*</span></label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    placeholder="budi@example.com" 
                    required
                >
                @error('email')
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
                    value="{{ old('phone') }}" 
                    placeholder="Contoh: 08123456789"
                >
                @error('phone')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Alamat --}}
            <div class="form-group">
                <label for="address">Alamat Tempat Tinggal</label>
                <textarea 
                    id="address" 
                    name="address" 
                    rows="2" 
                    placeholder="Alamat domisili"
                >{{ old('address') }}</textarea>
                @error('address')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label for="password">Password <span style="color:red">*</span></label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Minimal 8 karakter" 
                    required
                >
                <div class="password-hint">
                    *Wajib minimal 8 karakter, kombinasi huruf besar, huruf kecil, angka, dan simbol (@$!%*#?&).
                </div>
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password <span style="color:red">*</span></label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="Ketik ulang password Anda" 
                    required
                >
            </div>

            <button type="submit" class="btn-submit">Daftar Sebagai Anggota</button>
        </form>

        <div class="auth-footer">
            Sudah terdaftar? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>

</body>
</html>
