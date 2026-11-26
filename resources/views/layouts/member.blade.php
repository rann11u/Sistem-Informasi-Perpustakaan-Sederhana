<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Anggota — Sistem Informasi Perpustakaan')</title>
    <script>
        (function () {
            var saved = localStorage.getItem('member-theme');
            var theme = (saved === 'light' || saved === 'dark') ? saved : 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Wix+Madefor+Display:wght@400;500;600&family=Wix+Madefor+Text:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/member/member.css') }}">
</head>
<body>

    <header class="member-header">
        <div class="member-header__inner">
            <div class="member-header__left">
                <a href="{{ route('member.books') }}" class="member-brand">Perpustakaan</a>

                <nav class="member-nav__links" aria-label="Menu utama">
                    <a href="{{ route('member.books') }}" class="nav-link {{ request()->routeIs('member.books') ? 'active' : '' }}">
                        Katalog
                    </a>
                    <a href="{{ route('member.history') }}" class="nav-link {{ request()->routeIs('member.history') ? 'active' : '' }}">
                        Riwayat
                    </a>
                </nav>
            </div>

            <form action="{{ route('member.books') }}" method="GET" class="header-search" role="search">
                @if(request('category_id'))
                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                @endif
                @if(request('in_stock'))
                    <input type="hidden" name="in_stock" value="{{ request('in_stock') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                <svg class="header-search__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
                <input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    class="header-search__input"
                    placeholder="Cari judul buku, penulis, atau ISBN..."
                    aria-label="Cari judul buku, penulis, atau ISBN"
                >
            </form>

            <div class="member-nav__user">
                <button
                    type="button"
                    id="themeToggle"
                    class="btn-theme-toggle"
                    aria-label="Ganti mode terang atau gelap"
                    title="Ganti tema (Terang / Gelap)"
                >
                    <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="12" cy="12" r="4"/>
                        <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                    </svg>
                    <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                    </svg>
                </button>

                <a href="{{ route('member.profile') }}" class="btn-profile-avatar" title="Profil Saya ({{ Auth::user()->name }})" aria-label="Profil Saya">
                    @if(Auth::user()->member && Auth::user()->member->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists(Auth::user()->member->photo))
                        <img src="{{ asset('storage/' . Auth::user()->member->photo) }}" alt="Foto Profil {{ Auth::user()->name }}">
                    @else
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    @endif
                </a>

                @if(Auth::user()->member && Auth::user()->member->member_code)
                    <span class="badge-code">{{ Auth::user()->member->member_code }}</span>
                @endif

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    <main class="member-main @yield('main_class')">
        @if(session('success'))
            <div class="alert alert-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="member-footer">
        <div class="member-footer__inner">
            <span>&copy; {{ date('Y') }} Sistem Informasi Perpustakaan Sederhana</span>
            <div class="member-footer__links">
                <a href="{{ route('member.books') }}">Katalog Buku</a>
                <a href="{{ route('member.history') }}">Riwayat Pinjam</a>
                <a href="{{ route('member.profile') }}">Profil Saya</a>
            </div>
        </div>
    </footer>

    <script>
        (function () {
            var btn = document.getElementById('themeToggle');
            if (!btn) return;
            function syncLabel(theme) {
                btn.setAttribute('aria-pressed', theme === 'light' ? 'true' : 'false');
            }
            syncLabel(document.documentElement.getAttribute('data-theme') || 'dark');
            btn.addEventListener('click', function () {
                var current = document.documentElement.getAttribute('data-theme') || 'dark';
                var next = current === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', next);
                localStorage.setItem('member-theme', next);
                syncLabel(next);
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
