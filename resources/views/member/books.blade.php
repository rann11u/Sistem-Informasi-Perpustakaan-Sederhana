@extends('layouts.member')

@section('title', 'Katalog Koleksi Buku — Portal Anggota')

@section('content')
    @php
        $activeCategory = request('category_id') ? $categories->find(request('category_id')) : null;
        $hasFilters = request('search') || request('category_id') || request('in_stock');
    @endphp

    {{-- Breadcrumb & Status Kuota --}}
    <div class="catalog-topbar">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('member.books') }}">Katalog</a>
            <span class="breadcrumb__sep" aria-hidden="true">/</span>
            <span>{{ $activeCategory ? $activeCategory->name : 'Semua Koleksi' }}</span>
        </nav>

        <div class="quota-inline">
            <span class="quota-inline__text">
                Kuota Aktif: <strong>{{ $activeBookingsCount }} / 5 buku</strong>
            </span>
            @if($activeBookingsCount >= 5)
                <span class="quota-pill quota-pill--full">Kuota Penuh</span>
            @else
                <span class="quota-pill quota-pill--ok">Sisa {{ 5 - $activeBookingsCount }}</span>
            @endif
        </div>
    </div>

    <div class="catalog-layout">
        {{-- Sidebar Filter Kiri --}}
        <aside class="catalog-sidebar" aria-label="Filter katalog buku">
            <h2 class="sidebar-heading">Filter</h2>

            <div class="filter-section">
                <h3 class="filter-section__title">Kategori</h3>
                <ul class="category-list">
                    <li>
                        <a
                            href="{{ route('member.books', request()->except('category_id', 'page')) }}"
                            class="category-link {{ !request('category_id') ? 'is-active' : '' }}"
                        >
                            <span>Semua Kategori</span>
                        </a>
                    </li>
                    @foreach($categories as $category)
                        <li>
                            <a
                                href="{{ route('member.books', array_merge(request()->except('page'), ['category_id' => $category->id])) }}"
                                class="category-link {{ (string) request('category_id') === (string) $category->id ? 'is-active' : '' }}"
                            >
                                <span>{{ $category->name }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="filter-section">
                <h3 class="filter-section__title">Stok</h3>
                <a
                    href="{{ route('member.books', request('in_stock') ? request()->except('in_stock', 'page') : array_merge(request()->except('page'), ['in_stock' => 1])) }}"
                    class="stock-toggle {{ request('in_stock') ? 'is-on' : '' }}"
                    role="switch"
                    aria-checked="{{ request('in_stock') ? 'true' : 'false' }}"
                >
                    <span class="stock-toggle__track" aria-hidden="true">
                        <span class="stock-toggle__thumb"></span>
                    </span>
                    <span class="stock-toggle__label">Hanya yang tersedia</span>
                </a>
            </div>
        </aside>

        {{-- Area Konten Utama Kanan --}}
        <section class="catalog-content" aria-label="Daftar koleksi buku">
            <div class="catalog-head">
                <div>
                    <h1 class="catalog-title">{{ $activeCategory ? $activeCategory->name : 'Semua Koleksi Buku' }}</h1>
                    <p class="catalog-count">{{ $books->total() }} buku ditemukan</p>
                </div>

                <form action="{{ route('member.books') }}" method="GET" class="sort-form">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('category_id'))
                        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                    @endif
                    @if(request('in_stock'))
                        <input type="hidden" name="in_stock" value="{{ request('in_stock') }}">
                    @endif

                    <label for="sortSelect" class="sort-label">Urutkan</label>
                    <select id="sortSelect" name="sort" class="form-control sort-select" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="title_asc" {{ request('sort') === 'title_asc' ? 'selected' : '' }}>Judul (A–Z)</option>
                        <option value="stock_desc" {{ request('sort') === 'stock_desc' ? 'selected' : '' }}>Stok Terbanyak</option>
                    </select>
                </form>
            </div>

            {{-- Chip Filter Aktif --}}
            @if($hasFilters)
                <div class="active-chips">
                    <a href="{{ route('member.books') }}" class="chip-clear" title="Hapus semua filter">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M18 6 6 18M6 6l12 12"/>
                        </svg>
                        <span>Reset</span>
                    </a>

                    @if(request('in_stock'))
                        <a href="{{ route('member.books', request()->except('in_stock', 'page')) }}" class="filter-chip">
                            <span>Stok Tersedia</span>
                            <span aria-hidden="true">&times;</span>
                        </a>
                    @endif

                    @if($activeCategory)
                        <a href="{{ route('member.books', request()->except('category_id', 'page')) }}" class="filter-chip">
                            <span>{{ $activeCategory->name }}</span>
                            <span aria-hidden="true">&times;</span>
                        </a>
                    @endif

                    @if(request('search'))
                        <a href="{{ route('member.books', request()->except('search', 'page')) }}" class="filter-chip">
                            <span>"{{ request('search') }}"</span>
                            <span aria-hidden="true">&times;</span>
                        </a>
                    @endif
                </div>
            @endif

            {{-- Grid 5 Kolom Kartu Buku --}}
            <div class="book-grid">
                @forelse($books as $book)
                    <article class="book-card">
                        <div class="book-card__top">
                            <div class="book-card__cover">
                                @if($book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                                @else
                                    <div class="book-cover-fallback">
                                        <span class="book-cover-fallback__cat">{{ $book->category->name ?? 'Umum' }}</span>
                                        <span class="book-cover-fallback__title">{{ $book->title }}</span>
                                        <span class="book-cover-fallback__author">{{ $book->author }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="book-card__body">
                                <div class="book-card__meta-row">
                                    <span class="book-category">{{ $book->category->name ?? 'Umum' }}</span>
                                    @if($book->stock > 0)
                                        <span class="stock-dot stock-dot--ok">Stok {{ $book->stock }}</span>
                                    @else
                                        <span class="stock-dot stock-dot--empty">Habis</span>
                                    @endif
                                </div>

                                <div class="book-author">{{ $book->author }}</div>
                                <h2 class="book-title" title="{{ $book->title }}">{{ $book->title }}</h2>
                                <div class="book-publisher">{{ $book->publisher ?? '-' }} · {{ $book->publication_year ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="book-card__action">
                            @if($book->stock > 0)
                                @if($activeBookingsCount < 5)
                                    <form action="{{ route('member.books.book', $book->id) }}" method="POST" onsubmit="return confirm('Apakah Anda ingin membooking buku \'{{ addslashes($book->title) }}\'?\n\nBatas pengambilan adalah 1 hari (24 jam) di perpustakaan.')">
                                        @csrf
                                        <button type="submit" class="btn-primary btn-book">Booking</button>
                                    </form>
                                @else
                                    <button type="button" class="btn-book-disabled" title="Batas kuota 5 buku tercapai" disabled>Kuota Penuh</button>
                                @endif
                            @else
                                <button type="button" class="btn-book-disabled" disabled>Stok Habis</button>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <h3>Buku tidak ditemukan</h3>
                        <p>Silakan coba kata kunci pencarian atau pilih kategori lain.</p>
                    </div>
                @endforelse
            </div>

            {{-- Paginasi --}}
            @if($books->hasPages())
                <div class="pagination-wrapper">
                    {{ $books->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
