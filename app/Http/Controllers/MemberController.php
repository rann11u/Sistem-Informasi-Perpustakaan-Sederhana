<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    // Read - Tampilkan semua anggota
    public function index() {
        $members = Member::all();

        return view('members.index', compact('members'));
    }

    // AJAX - Cek apakah kode anggota, email, atau alamat sudah terdaftar
    public function checkDuplicate(Request $request) {
        $field = $request->query('field');
        $value = trim($request->query('value'));
        $ignoreId = $request->query('ignore_id');

        if (!in_array($field, ['member_code', 'email', 'address']) || empty($value)) {
            return response()->json(['exists' => false]);
        }

        $query = Member::where($field, $value);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $exists = $query->exists();

        $labels = [
            'member_code' => 'Kode anggota',
            'email'       => 'Email',
            'address'     => 'Alamat',
        ];

        $label = $labels[$field] ?? $field;

        return response()->json([
            'exists'  => $exists,
            'message' => $exists ? "⚠️ {$label} ini sudah digunakan/terdaftar!" : '',
        ]);
    }

    // Detail - Tampilkan detail satu anggota
    public function show($id) {
        $member = Member::findOrFail($id);

        return view('members.show', compact('member'));
    }

    // Tampilkan form tambah
    public function create() {
        return view('members.create');
    }

    // Store - Simpan anggota baru
    public function store(Request $request) {
        $request->validate([
            'member_code' => 'required|string|max:50|unique:members,member_code',
            'name'        => 'required|string|max:255',
            'email'       => 'nullable|email|max:255|unique:members,email',
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:1000',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'joined_at'   => 'nullable|date',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('members', 'public');
        }

        Member::create([
            'member_code' => $request->member_code,
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'address'     => $request->address,
            'photo'       => $photoPath,
            'joined_at'   => $request->joined_at ?? now()->toDateString(),
        ]);

        return redirect()->route('members.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    // Tampilkan form edit
    public function edit($id) {
        $member = Member::findOrFail($id);

        return view('members.edit', compact('member'));
    }

    // Update - Perbarui data anggota
    public function update(Request $request, $id) {
        $member = Member::findOrFail($id);

        $request->validate([
            'member_code' => 'required|string|max:50|unique:members,member_code,' . $id,
            'name'        => 'required|string|max:255',
            'email'       => 'nullable|email|max:255|unique:members,email,' . $id,
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:1000',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'joined_at'   => 'nullable|date',
        ]);

        $photoPath = $member->photo;
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($member->photo && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }
            $photoPath = $request->file('photo')->store('members', 'public');
        }

        $member->update([
            'member_code' => $request->member_code,
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'address'     => $request->address,
            'photo'       => $photoPath,
            'joined_at'   => $request->joined_at,
        ]);

        return redirect()->route('members.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    // Delete - Hapus anggota
    public function destroy($id) {
        $member = Member::findOrFail($id);

        // Hapus foto jika ada
        if ($member->photo && Storage::disk('public')->exists($member->photo)) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();

        return redirect()->route('members.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
