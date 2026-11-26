<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Koleksi Buku - Portal Anggota</title>
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
        }

        .header {
            background-color: #174a38;
            color: white;
            padding: 14px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .header h1 {
            font-size: 19px;
            margin: 0;
        }

        .user-nav {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .nav-link {
            color: #dcebe3;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            color: white;
            font-weight: bold;
        }

        /* Tombol Profil dengan Foto / Kosong jika belum ada */
        .btn-profile-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 2px solid #e5f1eb;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-color: #265a48;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
            flex-shrink: 0;
        }

        .btn-profile-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 0 0 3px rgba(229, 241, 235, 0.4);
        }

        .btn-profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-profile-avatar .empty-avatar {
            width: 18px;
            height: 18px;
            fill: #dcebe3;
        }

        .badge-code {
            background-color: #e5f1eb;
            color: #174a38;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-logout {
            background-color: #dc2626;
            color: white;
            border: none;
            padding: 7px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
        }

        .btn-logout:hover {
            background-color: #b91c1c;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 28px auto 40px;
        }

        .alert-success {
            background-color: #e4f6e9;
            color: #176534;
            padding: 14px 18px;
            border-radius: 8px;
            border: 1px solid #bbf0cb;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .alert-error {
            background-color: #fde8e7;
            color: #a12222;
            padding: 14px 18px;
            border-radius: 8px;
            border: 1px solid #f8b4b4;
            margin-bottom: 24px;
            font-size: 14px;
        }

        /* Banner Status Kuota Buku */
        .quota-banner {
            background-color: white;
            padding: 14px 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        /* Filter & Search Bar */
        .filter-card {
            background-color: white;
            padding: 20px 24px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
            margin-bottom: 24px;
        }

        .filter-form {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-input-wrapper {
            flex: 1;
            min-width: 240px;
        }

        .filter-form input,
        .filter-form select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        .filter-form input:focus,
        .filter-form select:focus {
            border-color: #174a38;
            box-shadow: 0 0 0 3px rgba(23, 74, 56, 0.15);
        }

        .btn-search {
            background-color: #174a38;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
            white-space: nowrap;
        }

        .btn-search:hover {
            background-color: #103629;
        }

        .btn-reset {
            background-color: #e5e7eb;
            color: #4b5563;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-reset:hover {
            background-color: #d1d5db;
        }

        .search-info {
            font-size: 13px;
            color: #6b7280;
            margin-top: 10px;
        }

        /* Grid Kartu Buku */
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .book-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 1px solid #e5e7eb;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .book-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 14px rgba(0,0,0,0.08);
            border-color: #cbd5e1;
        }

        .book-category {
            display: inline-block;
            background-color: #e5f1eb;
            color: #174a38;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .book-title {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 8px;
            line-height: 1.35;
        }

        .book-meta {
            font-size: 13px;
            color: #4b5563;
            margin-bottom: 5px;
        }

        .stock-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
        }

        .in-stock {
            background-color: #e4f6e9;
            color: #176534;
            border: 1px solid #bbf0cb;
        }

        .out-of-stock {
            background-color: #fde8e7;
            color: #a12222;
            border: 1px solid #f8b4b4;
        }

        /* Tombol Booking */
        .btn-book {
            width: 100%;
            background-color: #174a38;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-book:hover {
            background-color: #103629;
        }

        .btn-book-disabled {
            width: 100%;
            background-color: #f3f4f6;
            color: #9ca3af;
            border: 1px solid #e5e7eb;
            padding: 10px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            cursor: not-allowed;
        }

        .empty-state {
            grid-column: 1 / -1;
            background: white;
            padding: 40px 20px;
            text-align: center;
            border-radius: 10px;
            color: #6b7280;
            border: 1px solid #e5e7eb;
        }

        /* Paginasi */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <header class="header">
        <h1>Sistem Informasi Perpustakaan</h1>

        <div class="user-nav">
            <a href="{{ route('member.books') }}" class="nav-link active">Katalog Buku</a>

            {{-- Tombol Profil: Menampilkan foto yang di-up, kosong jika belum ada --}}
            <a href="{{ route('member.profile') }}" class="btn-profile-avatar" title="Profil Saya">
                @if(Auth::user()->member && Auth::user()->member->photo)
                    <img src="{{ asset('storage/' . Auth::user()->member->photo) }}" alt="Foto Profil">
                @else
                    <svg class="empty-avatar" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                @endif
            </a>

            <span>Halo, <strong>{{ Auth::user()->name }}</strong></span>

            @if(Auth::user()->member)
                <span class="badge-code">{{ Auth::user()->member->member_code }}</span>
            @endif

            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout">Keluar</button>
            </form>
        </div>
    </header>

    <main class="container">
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        {{-- Status Kuota Buku Member --}}
        <div class="quota-banner">
            <div>
                <span style="font-size: 14px; color: #374151;">
                    Buku Aktif Anda: <strong>{{ $activeBookingsCount }} / 5 buku</strong>
                </span>
                <span style="font-size: 13px; color: #6b7280; margin-left: 8px;">
                    (Maksimal pinjam + booking adalah 5 buku. Batas pengambilan booking: 1 hari)
                </span>
            </div>
            <div>
                @if($activeBookingsCount >= 5)
                    <span style="background: #fde8e7; color: #a12222; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold;">
                        Kuota Penuh
                    </span>
                @else
                    <span style="background: #e5f1eb; color: #174a38; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold;">
                        Sisa Kuota: {{ 5 - $activeBookingsCount }} buku
                    </span>
                @endif
            </div>
        </div>

        {{-- Form Pencarian & Filter Kategori --}}
        <div class="filter-card">
            <form action="{{ route('member.books') }}" method="GET" class="filter-form">
                
                {{-- Input Pencarian Judul, Pengarang, atau ISBN --}}
                <div class="search-input-wrapper">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari berdasarkan judul buku, nama pengarang, atau nomor ISBN..."
                    >
                </div>

                {{-- Filter Kategori --}}
                <div style="min-width: 180px;">
                    <select name="category_id">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol Cari & Reset --}}
                <button type="submit" class="btn-search">Cari Buku</button>
                @if(request('search') || request('category_id'))
                    <a href="{{ route('member.books') }}" class="btn-reset">Reset</a>
                @endif
            </form>

            @if(request('search') || request('category_id'))
                <div class="search-info">
                    Menampilkan hasil untuk 
                    @if(request('search')) pencarian "<strong>{{ request('search') }}</strong>" @endif
                    @if(request('search') && request('category_id')) dan @endif
                    @if(request('category_id')) kategori "<strong>{{ $categories->find(request('category_id'))->name ?? '' }}</strong>" @endif
                    ({{ $books->total() }} buku ditemukan)
                </div>
            @endif
        </div>

        {{-- Grid Katalog Buku --}}
        <div class="book-grid">
            @forelse($books as $book)
                <div class="book-card">
                    <div>
                        <span class="book-category">{{ $book->category->name ?? 'Umum' }}</span>
                        <div class="book-title">{{ $book->title }}</div>
                        <div class="book-meta">Pengarang: <strong>{{ $book->author }}</strong></div>
                        <div class="book-meta">Penerbit: {{ $book->publisher ?? '-' }} ({{ $book->publication_year ?? '-' }})</div>
                        @if($book->isbn)
                            <div class="book-meta" style="font-size: 12px; color: #6b7280;">ISBN: {{ $book->isbn }}</div>
                        @endif
                        
                        {{-- Status Stok Buku --}}
                        <div>
                            @if($book->stock > 0)
                                <span class="stock-badge in-stock">Tersedia {{ $book->stock }} buku</span>
                            @else
                                <span class="stock-badge out-of-stock">Stok Habis</span>
                            @endif
                        </div>
                    </div>

                    {{-- Tombol Booking Buku --}}
                    <div style="margin-top: 16px;">
                        @if($book->stock > 0)
                            @if($activeBookingsCount < 5)
                                <form action="{{ route('member.books.book', $book->id) }}" method="POST" onsubmit="return confirm('Apakah Anda ingin membooking buku \'{{ addslashes($book->title) }}\'?\n\nBatas pengambilan adalah 1 hari (24 jam) di perpustakaan.')">
                                    @csrf
                                    <button type="submit" class="btn-book">Booking Buku</button>
                                </form>
                            @else
                                <button type="button" class="btn-book-disabled" title="Batas kuota 5 buku tercapai" disabled>Kuota Penuh (Maks 5)</button>
                            @endif
                        @else
                            <button type="button" class="btn-book-disabled" disabled>Stok Habis</button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <p style="font-size: 16px; font-weight: bold; margin-bottom: 6px;">Buku tidak ditemukan</p>
                    <p style="font-size: 13px;">Silakan coba kata kunci pencarian atau pilih kategori lain.</p>
                </div>
            @endforelse
        </div>

        {{-- Paginasi --}}
        @if($books->hasPages())
            <div class="pagination-wrapper">
                {{ $books->links() }}
            </div>
        @endif
    </main>

</body>
</html>
