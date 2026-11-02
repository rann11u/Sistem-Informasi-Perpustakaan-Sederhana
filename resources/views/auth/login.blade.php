<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Perpustakaan</title>
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
            padding: 20px;
        }

        .login-card {
            background-color: white;
            width: 100%;
            max-width: 400px;
            padding: 32px;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
            border-top: 5px solid #174a38;
        }

        .login-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .login-header h1 {
            font-size: 22px;
            color: #174a38;
            margin-bottom: 6px;
        }

        .login-header p {
            font-size: 13px;
            color: #6b7280;
        }

        .alert {
            padding: 12px 14px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 18px;
            line-height: 1.4;
        }

        .alert-error {
            background-color: #fde8e7;
            color: #a12222;
            border: 1px solid #f8b4b4;
        }

        .alert-success {
            background-color: #e4f6e9;
            color: #176534;
            border: 1px solid #bbf0cb;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: bold;
            color: #374151;
        }

        .form-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group input:focus {
            border-color: #174a38;
            box-shadow: 0 0 0 3px rgba(23, 74, 56, 0.15);
        }

        .field-error {
            color: #a12222;
            font-size: 12px;
            margin-top: 4px;
        }

        .btn-submit {
            width: 100%;
            background-color: #174a38;
            color: white;
            padding: 11px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
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

    <div class="login-card">
        <div class="login-header">
            <h1>Perpustakaan</h1>
            <p>Silakan masuk dengan email dan password Anda</p>
        </div>

        {{-- Pesan berhasil logout atau notifikasi sukses lainnya --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan kesalahan jika login gagal (kredensial salah) --}}
        @if ($errors->has('loginError'))
            <div class="alert alert-error">
                {{ $errors->first('loginError') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            {{-- Input Email --}}
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    placeholder="nama@email.com" 
                    required 
                    autofocus
                >
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input Password --}}
            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Masukkan password" 
                    required
                >
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol Masuk --}}
            <button type="submit" class="btn-submit">Masuk</button>
        </form>
    </div>

</body>
</html>