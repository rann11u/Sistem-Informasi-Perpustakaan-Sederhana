<nav class="navigation" aria-label="Navigasi utama" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('dashboard') }}" {{ request()->routeIs('dashboard') ? 'style=background:#e5f1eb;font-weight:bold;' : '' }}>Dashboard</a>
        <a href="{{ route('books.index') }}" {{ request()->routeIs('books.*') ? 'style=background:#e5f1eb;font-weight:bold;' : '' }}>Buku</a>
        <a href="{{ route('categories.index') }}" {{ request()->routeIs('categories.*') ? 'style=background:#e5f1eb;font-weight:bold;' : '' }}>Kategori</a>
        <a href="{{ route('members.index') }}" {{ request()->routeIs('members.*') ? 'style=background:#e5f1eb;font-weight:bold;' : '' }}>Anggota</a>
        <a href="{{ route('borrowings.index') }}" {{ request()->routeIs('borrowings.*') ? 'style=background:#e5f1eb;font-weight:bold;' : '' }}>Peminjaman</a>

        {{-- HANYA ADMIN YANG DAPAT MELIHAT MENU KELOLA PENGGUNA --}}
        @if (Auth::check() && Auth::user()->role === 'admin')
            <a href="{{ route('users.index') }}" {{ request()->routeIs('users.*') ? 'style=background:#e5f1eb;font-weight:bold;' : '' }}>Kelola Pengguna</a>
        @endif
    </div>

    @auth
        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 14px; color: #374151;">
                Halo, <strong>{{ Auth::user()->name }}</strong> 
                <small style="background: {{ Auth::user()->role === 'admin' ? '#fde8e7; color:#a12222;' : '#e5f1eb; color:#174a38;' }} padding: 3px 8px; border-radius: 4px; font-weight: bold; text-transform: uppercase;">
                    {{ Auth::user()->role }}
                </small>
            </span>

            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" style="background-color: #dc2626; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: bold;">
                    Keluar (Logout)
                </button>
            </form>
        </div>
    @endauth
</nav>