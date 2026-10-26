
<nav class="navigation" aria-label="Navigasi utama">
    <a href="{{ route('dashboard') }}" {{ request()->routeIs('dashboard') ? 'style=background:#e5f1eb;font-weight:bold;' : '' }}>Dashboard</a>
    <a href="{{ route('books.index') }}" {{ request()->routeIs('books.*') ? 'style=background:#e5f1eb;font-weight:bold;' : '' }}>Buku</a>
    <a href="{{ route('categories.index') }}" {{ request()->routeIs('categories.*') ? 'style=background:#e5f1eb;font-weight:bold;' : '' }}>Kategori</a>
    <a href="{{ route('members.index') }}" {{ request()->routeIs('members.*') ? 'style=background:#e5f1eb;font-weight:bold;' : '' }}>Anggota</a>
</nav>
