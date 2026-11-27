@extends('layouts.app')

@section('title', 'Catat Peminjaman')

@section('content')
    <h2 style="margin-top:0;">Catat Peminjaman Baru</h2>

    @php
        $selectedMember = $members->firstWhere('id', old('member_id'));
    @endphp

    <form action="{{ route('borrowings.store') }}" method="POST" style="max-width:520px;">
        @csrf

        <div style="margin-bottom:14px;">
            <label for="member-search" style="display:block; margin-bottom:4px; font-weight:bold;">Anggota <span style="color:red">*</span></label>
            <div id="member-picker" style="position:relative;">
                <input type="hidden" id="member_id" name="member_id" value="{{ $selectedMember ? $selectedMember->id : '' }}">
                <input type="text" id="member-search" value="{{ $selectedMember ? $selectedMember->member_code . ' — ' . $selectedMember->name : '' }}"
                    role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="member-picker-options"
                    aria-describedby="member-picker-error" autocomplete="off" placeholder="Ketik ID anggota atau nama..."
                    required style="width:100%; padding:9px 38px 9px 12px; border:1px solid #ccc; border-radius:6px;">
                <button type="button" id="member-clear" aria-label="Hapus pilihan anggota" title="Hapus pilihan"
                    @if (!$selectedMember) hidden @endif
                    style="position:absolute; top:50%; right:8px; transform:translateY(-50%); padding:2px 6px; border:0; background:transparent; color:#666; font-size:20px; line-height:1; cursor:pointer;">&times;</button>

                <div id="member-autocomplete-results" hidden
                    style="position:absolute; top:calc(100% + 4px); left:0; right:0; z-index:10; max-height:260px; overflow-y:auto; padding:6px; border:1px solid #ccc; border-radius:6px; background:white; box-shadow:0 4px 12px rgba(0,0,0,.12);">
                    <div id="member-picker-options" role="listbox" aria-label="Hasil pencarian anggota">
                        @foreach ($members as $member)
                            <button type="button" class="member-picker-option" role="option"
                                aria-selected="{{ old('member_id') == $member->id ? 'true' : 'false' }}"
                                data-member-id="{{ $member->id }}"
                                data-search="{{ $member->member_code }} {{ $member->name }} {{ $member->id }}"
                                data-display="{{ $member->member_code }} — {{ $member->name }}"
                                style="display:block; width:100%; padding:8px; border:0; border-radius:4px; background:white; color:#263238; text-align:left; cursor:pointer;">
                                {{ $member->member_code }} — {{ $member->name }}
                            </button>
                        @endforeach
                    </div>
                    <p id="member-picker-empty" hidden style="margin:8px 0 0; color:#666;">Tidak ada anggota yang cocok.</p>
                    <small id="member-search-results" aria-live="polite" style="display:block; margin-top:8px; color:#666;"></small>
                </div>
                <p id="member-picker-error" role="alert" hidden style="margin:5px 0 0; color:#a12222; font-size:13px;">Pilih anggota dari hasil pencarian.</p>
            </div>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Buku <span style="color:red">*</span></label>
            <select name="book_id" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
                <option value="">-- Pilih Buku --</option>
                @foreach ($books as $book)
                    <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                        {{ $book->title }} (Stok: {{ $book->stock }})
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Jumlah <span style="color:red">*</span></label>
            <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Tanggal Pinjam <span style="color:red">*</span></label>
            <input type="date" id="borrow_date" name="borrow_date" value="{{ old('borrow_date', date('Y-m-d')) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
        </div>

        {{-- JATUH TEMPO OTOMATIS (READONLY) --}}
        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold;">Jatuh Tempo (Otomatis 1 Minggu)</label>
            <input type="date" id="due_date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}" style="width:100%; padding:8px; border:1px solid #ddd; background:#f4f6f9; border-radius:6px; color:#555;" readonly>
            <small style="color:#666; font-size:12px;">*Jatuh tempo otomatis dihitung 1 minggu (7 hari) dari tanggal pinjam.</small>
        </div>

        <button type="submit" style="background:#174a38; color:white; padding:10px 20px; border:none; border-radius:6px; cursor:pointer;">Simpan</button>
        <a href="{{ route('borrowings.index') }}" style="margin-left:12px; color:#555; text-decoration:none;">Batal</a>
    </form>

    {{-- Script untuk update otomatis jatuh tempo jika tanggal pinjam diubah --}}
    <script>
        const memberPicker = document.getElementById('member-picker');
        const memberSearch = document.getElementById('member-search');
        const memberId = document.getElementById('member_id');
        const memberClear = document.getElementById('member-clear');
        const memberAutocompleteResults = document.getElementById('member-autocomplete-results');
        const memberPickerOptions = Array.from(document.querySelectorAll('.member-picker-option'));
        const memberPickerEmpty = document.getElementById('member-picker-empty');
        const memberSearchResults = document.getElementById('member-search-results');
        const memberPickerError = document.getElementById('member-picker-error');

        function filterMembers() {
            const terms = memberSearch.value.trim().toLocaleLowerCase().split(/\s+/).filter(Boolean);

            if (!terms.length) {
                memberAutocompleteResults.hidden = true;
                memberSearch.setAttribute('aria-expanded', 'false');
                return;
            }

            let visibleCount = 0;

            memberPickerOptions.forEach(function(option) {
                const searchableText = option.dataset.search.toLocaleLowerCase();
                const matches = terms.every(function(term) {
                    return searchableText.includes(term);
                });

                option.style.display = matches ? 'block' : 'none';
                if (matches) visibleCount++;
            });

            memberAutocompleteResults.hidden = false;
            memberPickerEmpty.hidden = visibleCount > 0;
            memberSearchResults.textContent = `${visibleCount} anggota ditemukan.`;
            memberSearch.setAttribute('aria-expanded', 'true');
        }

        memberSearch.addEventListener('input', function() {
            memberId.value = '';
            memberClear.hidden = true;
            memberPickerOptions.forEach(function(option) {
                option.setAttribute('aria-selected', 'false');
            });
            memberPickerError.hidden = true;
            memberSearch.removeAttribute('aria-invalid');
            filterMembers();
        });

        memberPickerOptions.forEach(function(option) {
            option.addEventListener('click', function() {
                memberId.value = this.dataset.memberId;
                memberSearch.value = this.dataset.display;
                memberClear.hidden = false;
                memberPickerOptions.forEach(function(item) {
                    item.setAttribute('aria-selected', item === option ? 'true' : 'false');
                });
                memberAutocompleteResults.hidden = true;
                memberSearch.setAttribute('aria-expanded', 'false');
                memberPickerError.hidden = true;
                memberSearch.removeAttribute('aria-invalid');
                memberSearch.focus();
            });
        });

        memberSearch.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                memberAutocompleteResults.hidden = true;
                memberSearch.setAttribute('aria-expanded', 'false');
            } else if (event.key === 'ArrowDown' && !memberAutocompleteResults.hidden) {
                const firstMatch = memberPickerOptions.find(function(option) {
                    return option.style.display !== 'none';
                });
                if (firstMatch) {
                    event.preventDefault();
                    firstMatch.focus();
                }
            }
        });

        memberClear.addEventListener('click', function() {
            memberId.value = '';
            memberSearch.value = '';
            memberClear.hidden = true;
            memberPickerError.hidden = true;
            memberSearch.removeAttribute('aria-invalid');
            memberPickerOptions.forEach(function(option) {
                option.setAttribute('aria-selected', 'false');
            });
            memberAutocompleteResults.hidden = true;
            memberSearch.setAttribute('aria-expanded', 'false');
            memberSearch.focus();
        });

        memberPickerOptions.forEach(function(option) {
            option.addEventListener('keydown', function(event) {
                const visibleOptions = memberPickerOptions.filter(function(item) {
                    return item.style.display !== 'none';
                });
                const currentIndex = visibleOptions.indexOf(option);

                if (event.key === 'ArrowDown' && currentIndex < visibleOptions.length - 1) {
                    event.preventDefault();
                    visibleOptions[currentIndex + 1].focus();
                } else if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    if (currentIndex > 0) {
                        visibleOptions[currentIndex - 1].focus();
                    } else {
                        memberSearch.focus();
                    }
                } else if (event.key === 'Escape') {
                    memberAutocompleteResults.hidden = true;
                    memberSearch.setAttribute('aria-expanded', 'false');
                    memberSearch.focus();
                }
            });
        });

        document.addEventListener('click', function(event) {
            if (!memberPicker.contains(event.target)) {
                memberAutocompleteResults.hidden = true;
                memberSearch.setAttribute('aria-expanded', 'false');
            }
        });

        memberSearch.closest('form').addEventListener('submit', function(event) {
            if (!memberId.value) {
                event.preventDefault();
                memberPickerError.hidden = false;
                memberSearch.setAttribute('aria-invalid', 'true');
                filterMembers();
                memberSearch.focus();
            }
        });

        document.getElementById('borrow_date').addEventListener('change', function() {
            if (this.value) {
                const borrowDate = new Date(this.value);
                borrowDate.setDate(borrowDate.getDate() + 7);
                const yyyy = borrowDate.getFullYear();
                const mm = String(borrowDate.getMonth() + 1).padStart(2, '0');
                const dd = String(borrowDate.getDate()).padStart(2, '0');
                document.getElementById('due_date').value = `${yyyy}-${mm}-${dd}`;
            }
        });
    </script>
@endsection