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
            padding: 16px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
        }

        .user-nav {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .badge-code {
            background-color: #e5f1eb;
            color: #174a38;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .btn-logout {
            background-color: #dc2626;
            color: white;
            border: none;
            padding: 7px 14px;
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
            margin: 28px auto;
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

        .hero {
            background-color: white;
            padding: 24px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 24px;
        }

        .hero h2 {
            color: #174a38;
            margin-bottom: 6px;
        }

        .hero p {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.5;
        }

        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .book-card {
            background: white;
            border-radius: 8px;
            padding: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 1px solid #e5e7eb;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .book-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
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
        }

        .book-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .book-meta {
            font-size: 13px;
            color: #4b5563;
            margin-bottom: 4px;
        }

        .stock-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 14px;
        }

        .in-stock {
            background-color: #e4f6e9;
            color: #176534;
        }

        .out-of-stock {
            background-color: #fde8e7;
            color: #a12222;
        }
    </style>
</head>
<body>

    <header class="header">
        <h1>Sistem Informasi Perpustakaan</h1>
        <div class="user-nav">
            <a href="{{ route('member.books') }}" style="color:white; text-decoration:none; font-weight:bold; font-size:14px; margin-right:4px;">Katalog Buku</a>
            <a href="{{ route('member.profile') }}" style="color:#dcebe3; text-decoration:none; font-size:14px; margin-right:12px;">Profil Saya</a>
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

        <div class="hero">
            <h2>Katalog Koleksi Buku</h2>
            <p>Selamat datang di portal anggota perpustakaan. Silakan jelajahi koleksi buku yang tersedia.</p>
        </div>

        <div class="book-grid">
            @forelse($books as $book)
                <div class="book-card">
                    <div>
                        <span class="book-category">{{ $book->category->name ?? 'Umum' }}</span>
                        <div class="book-title">{{ $book->title }}</div>
                        <div class="book-meta">Pengarang: <strong>{{ $book->author }}</strong></div>
                        <div class="book-meta">Penerbit: {{ $book->publisher ?? '-' }} ({{ $book->publication_year ?? '-' }})</div>
                        @if($book->isbn)
                            <div class="book-meta" style="font-size: 12px; color: #9ca3af;">ISBN: {{ $book->isbn }}</div>
                        @endif
                    </div>
                    <div>
                        @if($book->stock > 0)
                            <span class="stock-badge in-stock">Tersedia: {{ $book->stock }} buku</span>
                        @else
                            <span class="stock-badge out-of-stock">Stok Habis</span>
                        @endif
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; background: white; padding: 30px; text-align: center; border-radius: 8px; color: #888;">
                    Belum ada koleksi buku yang tersedia saat ini.
                </div>
            @endforelse
        </div>
    </main>

</body>
</html>
