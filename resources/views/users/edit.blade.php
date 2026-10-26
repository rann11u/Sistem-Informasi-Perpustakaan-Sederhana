@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
    <h2 style="margin-top:0;">Edit Pengguna</h2>

    <form action="{{ route('users.update', $user->id) }}" method="POST" style="max-width:480px;">
        @csrf @method('PUT')

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Nama <span style="color:red">*</span></label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Email <span style="color:red">*</span></label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Role <span style="color:red">*</span></label>
            <select name="role" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
                <option value="petugas" {{ old('role', $user->role) === 'petugas' ? 'selected' : '' }}>Petugas</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Password Baru <small style="color:#888;">(kosongkan jika tidak diubah)</small></label>
            <input type="password" name="password" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">
        </div>

        <button type="submit" style="background:#174a38; color:white; padding:10px 20px; border:none; border-radius:6px; cursor:pointer;">Perbarui</button>
        <a href="{{ route('users.index') }}" style="margin-left:12px; color:#555;">Batal</a>
    </form>
@endsection

