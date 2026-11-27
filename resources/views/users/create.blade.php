@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <div class="admin-card" style="max-width: 560px; margin: 0 auto;">
        <div class="page-header-action">
            <h2>Tambah Pengguna Baru</h2>
            <a href="{{ route('users.index') }}" class="btn-ui btn-ui-secondary">← Kembali</a>
        </div>

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="form-field">
                <label>Nama Pengguna <span style="color:var(--color-error)">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="form-field">
                <label>Alamat Email <span style="color:var(--color-error)">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
            </div>

            <div class="form-field">
                <label>Peran / Role <span style="color:var(--color-error)">*</span></label>
                <select name="role" required>
                    <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="form-field">
                <label>Kata Sandi (Password) <span style="color:var(--color-error)">*</span></label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" required>
            </div>

            <div class="form-field">
                <label>Konfirmasi Kata Sandi <span style="color:var(--color-error)">*</span></label>
                <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required>
            </div>

            <div style="display:flex; align-items:center; gap:12px; margin-top:24px;">
                <button type="submit" class="btn-ui btn-ui-primary">Simpan Pengguna</button>
                <a href="{{ route('users.index') }}" class="btn-ui btn-ui-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
