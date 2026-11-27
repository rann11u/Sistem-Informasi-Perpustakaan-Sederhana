@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="admin-card" style="max-width: 560px; margin: 0 auto;">
        <div class="page-header-action">
            <h2>Edit Data Pengguna</h2>
            <a href="{{ route('users.index') }}" class="btn-ui btn-ui-secondary">← Kembali</a>
        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="form-field">
                <label>Nama Pengguna <span style="color:var(--color-error)">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-field">
                <label>Alamat Email <span style="color:var(--color-error)">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-field">
                <label>Peran / Role <span style="color:var(--color-error)">*</span></label>
                <select name="role" required>
                    <option value="petugas" {{ old('role', $user->role) === 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="form-field">
                <label>Kata Sandi Baru</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah kata sandi">
                <small>*Biarkan kosong jika tidak ada perubahan password.</small>
            </div>

            <div class="form-field">
                <label>Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi baru">
            </div>

            <div style="display:flex; align-items:center; gap:12px; margin-top:24px;">
                <button type="submit" class="btn-ui btn-ui-primary">Perbarui Pengguna</button>
                <a href="{{ route('users.index') }}" class="btn-ui btn-ui-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
