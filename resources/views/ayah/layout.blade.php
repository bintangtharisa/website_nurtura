<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') — Nurtura</title>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Layout CSS --}}
    <link rel="stylesheet" href="{{ asset('css/dashboardayah.css') }}">

    @stack('styles')
</head>
<body>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar">

        {{-- Brand --}}
        <div class="sidebar-brand">
            <div class="brand-logo">
                <span class="brand-icon">N</span>
            </div>
            <div class="brand-text">
                <span class="brand-name">Nurtura</span>
                <span class="brand-sub">Family Dashboard</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">
            <a href="{{ route('ayah.dashboard') }}"
               class="nav-item {{ request()->routeIs('ayah.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house nav-icon"></i>
                <span>Beranda</span>
            </a>

            <a href="{{ route('ayah.monitoring') }}"
               class="nav-item {{ request()->routeIs('ayah.monitoring') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line nav-icon"></i>
                <span>Monitoring</span>
            </a>

            <a href="{{ route('ayah.dukungan') }}"
               class="nav-item {{ request()->routeIs('ayah.dukungan') ? 'active' : '' }}">
                <i class="fa-solid fa-hands-holding-heart nav-icon"></i>
                <span>Dukungan</span>
            </a>

            <a href="{{ route('ayah.profil') }}"
               class="nav-item {{ request()->routeIs('ayah.profil') ? 'active' : '' }}">
                <i class="fa-solid fa-circle-user nav-icon"></i>
                <span>Profil</span>
            </a>
        </nav>

        {{-- Logout --}}
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fa-solid fa-arrow-right-from-bracket nav-icon"></i>
                    <span>Keluar</span>
                </button>
            </form>

            {{-- User Info --}}
            <div class="sidebar-user">
                <div class="user-avatar">
                    @if(Auth::user()->foto_profil)
                        <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Avatar">
                    @else
                        <span>{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    @endif
                </div>
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <span class="user-role">Premium Member</span>
                </div>
            </div>
        </div>

    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="main-wrapper">

        {{-- Topbar --}}
        <header class="topbar">
            <h1 class="page-title">@yield('page-title', 'Beranda Bapak')</h1>

            <div class="topbar-right">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" placeholder="Cari panduan..." class="search-input">
                </div>

                <button class="icon-btn" title="Notifikasi">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notif-dot"></span>
                </button>

                <button class="icon-btn" title="Pengaturan">
                    <i class="fa-solid fa-gear"></i>
                </button>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="content-area">
            @yield('content')
        </main>

    </div>

    @stack('scripts')
</body>
</html>