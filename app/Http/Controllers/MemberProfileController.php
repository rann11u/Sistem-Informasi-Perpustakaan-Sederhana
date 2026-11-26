<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Member;

class MemberProfileController extends Controller
{
    /**
     * Menampilkan halaman profil member
     */
    public function show()
    {
        $user = Auth::user();
        $member = $user->member;

        // Fallback jika member dibuat sebelum relasi user_id aktif
        if (! $member) {
            $member = Member::where('email', $user->email)->first();
            if ($member && ! $member->user_id) {
                $member->update(['user_id' => $user->id]);
            }
        }

        return view('member.profile', compact('user', 'member'));
    }

    /**
     * Memperbarui foto profil, nomor telepon, dan alamat
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $member = $user->member ?? Member::where('email', $user->email)->firstOrFail();

        $request->validate([
            'phone'   => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:1000'],
            'photo'   => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar yang diizinkan: jpeg, png, jpg, atau webp.',
            'photo.max'   => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        // Proses unggah foto profil baru
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($member->photo && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }

            $path = $request->file('photo')->store('members', 'public');
            $member->photo = $path;
        }

        $member->phone   = $request->phone;
        $member->address = $request->address;
        $member->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
