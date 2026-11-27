<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sistem Informasi Perpustakaan')</title>

    {{-- Dark/Light Theme Persistence via LocalStorage --}}
    <script>
        (function () {
            var saved = localStorage.getItem('admin-theme');
            var theme = (saved === 'light' || saved === 'dark') ? saved : 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    {{-- Fonts & Flatpickr Modern Datepicker --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Wix+Madefor+Display:wght@400;500;600;700&family=Wix+Madefor+Text:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    @stack('styles')
</head>

<body>

    <div class="admin-wrapper">
        {{-- NAVBAR SAMPING (SIDEBAR) --}}
        @include('partials.navbar')

        {{-- AREA UTAMA --}}
        <div class="admin-main-area">
            {{-- Topbar --}}
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button type="button" class="menu-toggle-btn" id="menuToggle" aria-label="Toggle Menu">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <h1 class="topbar-page-title">@yield('title', 'Panel Admin')</h1>
                </div>

                <div class="topbar-right">
                    {{-- Toggle Dark / Light Mode --}}
                    <button type="button" id="themeToggle" class="btn-theme-toggle" title="Ganti Tema (Terang / Gelap)" aria-label="Ganti tema">
                        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="4"/>
                            <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                        </svg>
                        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                        </svg>
                    </button>
                </div>
            </header>

            {{-- Main Content Body --}}
            <main class="admin-body-content">
                @include('partials.flash-message')

                @yield('content')
            </main>
        </div>
    </div>

    @include('partials.delete-modal')

    {{-- Flatpickr Library --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    {{-- Script Theme, Mobile Sidebar Toggle & Inisialisasi Kalender Modern --}}
    <script>
        (function () {
            var btn = document.getElementById('themeToggle');
            if (btn) {
                btn.addEventListener('click', function () {
                    var current = document.documentElement.getAttribute('data-theme') || 'dark';
                    var next = current === 'dark' ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-theme', next);
                    localStorage.setItem('admin-theme', next);
                });
            }

            var menuBtn = document.getElementById('menuToggle');
            var sidebar = document.getElementById('adminSidebar');
            if (menuBtn && sidebar) {
                menuBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('sidebar-open');
                });
            }

            // Inisialisasi Flatpickr ke semua input bertipe date atau berkelas .datepicker
            flatpickr(".input-datepicker, input[type='date']", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "j F Y",
                locale: "id",
                disableMobile: true
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
