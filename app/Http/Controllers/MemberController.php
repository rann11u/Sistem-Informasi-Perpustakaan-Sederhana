<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;

class MemberController extends Controller
{
    // Read - Tampilkan semua anggota
    public function index() {
        $members = Member::all();

        return view('members.index', compact('members'));
    }

    // Tampilkan detail satu anggota
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
            'joined_at'   => 'nullable|date',
        ]);

        Member::create([
            'member_code' => $request->member_code,
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'address'     => $request->address,
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
            'joined_at'   => 'nullable|date',
        ]);

        $member->update([
            'member_code' => $request->member_code,
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'address'     => $request->address,
            'joined_at'   => $request->joined_at,
        ]);

        return redirect()->route('members.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    // Delete - Hapus anggota
    public function destroy($id) {
        $member = Member::findOrFail($id);
        $member->delete();

        return redirect()->route('members.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
