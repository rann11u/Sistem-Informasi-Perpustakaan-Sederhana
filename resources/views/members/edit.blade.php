@extends('layouts.app')

@section('title', 'Edit Anggota')

@section('content')
    <h2 style="margin-top:0;">Edit Anggota</h2>

    <form action="{{ route('members.update', $member->id) }}" method="POST" style="max-width:480px;">
        @csrf @method('PUT')

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Kode Anggota <span style="color:red">*</span></label>
            <input type="text" name="member_code" value="{{ old('member_code', $member->member_code) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Nama <span style="color:red">*</span></label>
            <input type="text" name="name" value="{{ old('name', $member->name) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Email</label>
            <input type="email" name="email" value="{{ old('email', $member->email) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Telepon</label>
            <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Alamat</label>
            <textarea name="address" rows="3" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">{{ old('address', $member->address) }}</textarea>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Tanggal Bergabung</label>
            <input type="date" name="joined_at" value="{{ old('joined_at', $member->joined_at?->format('Y-m-d')) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">
        </div>

        <button type="submit" style="background:#174a38; color:white; padding:10px 20px; border:none; border-radius:6px; cursor:pointer;">Perbarui</button>
        <a href="{{ route('members.index') }}" style="margin-left:12px; color:#555;">Batal</a>
    </form>
@endsection

