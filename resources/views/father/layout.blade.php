<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') — Nurtura</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=Lora:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboardayah.css') }}">

    @stack('styles')
</head>
<body>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar">

        {{-- Brand --}}
        <div class="sidebar__brand">
            <div class="sidebar__brand-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2C8 2 5 5 5 8c0 4 3 6 5 9 1 1.5 1.5 3 2 3s1-1.5 2-3c2-3 5-5 5-9 0-3-3-6-7-6z" fill="currentColor" opacity="0.9"/>
                    <circle cx="12" cy="8" r="2.5" fill="white" opacity="0.7"/>
                </svg>
            </div>
            <div class="sidebar__brand-text">
                <span class="sidebar__brand-name">Nurtura Family</span>
                <span class="sidebar__brand-role">Ayah Panel</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar__nav">
            <ul class="sidebar__nav-list">

                <li class="sidebar__nav-item {{ request()->routeIs('father.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('father.dashboard') }}" class="sidebar__nav-link">
                        <span class="sidebar__nav-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                                <rect x="3" y="3" width="7" height="7" rx="1.5" fill="currentColor"/>
                                <rect x="14" y="3" width="7" height="7" rx="1.5" fill="currentColor"/>
                                <rect x="3" y="14" width="7" height="7" rx="1.5" fill="currentColor"/>
                                <rect x="14" y="14" width="7" height="7" rx="1.5" fill="currentColor"/>
                            </svg>
                        </span>
                        <span class="sidebar__nav-label">
                            Beranda
                            <small>Ayah</small>
                        </span>
                    </a>
                </li>

                <li class="sidebar__nav-item {{ request()->routeIs('father.monitoring') ? 'active' : '' }}">
                    <a href="{{ route('father.monitoring') }}" class="sidebar__nav-link">
                        <span class="sidebar__nav-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="sidebar__nav-label">
                            Monitoring
                            <small>Kondisi Istri</small>
                        </span>
                    </a>
                </li>

                <li class="sidebar__nav-item {{ request()->routeIs('father.support') ? 'active' : '' }}">
                    <a href="{{ route('father.support') }}" class="sidebar__nav-link">
                        <span class="sidebar__nav-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                                <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="sidebar__nav-label">
                            Dukungan
                            <small>Untuk Istri</small>
                        </span>
                    </a>
                </li>

                <li class="sidebar__nav-item {{ request()->routeIs('father.profile') ? 'active' : '' }}">
                    <a href="{{ route('father.profile') }}" class="sidebar__nav-link">
                        <span class="sidebar__nav-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="sidebar__nav-label">
                            Profil
                            <small>Akun Saya</small>
                        </span>
                    </a>
                </li>

            </ul>
        </nav>

        {{-- Spacer --}}
        <div class="sidebar__spacer"></div>

        {{-- Logout --}}
        <div class="sidebar__logout">
            <nav class="sidebar__nav">
                <ul class="sidebar__nav-list">
                    <li class="sidebar__nav-item">
                        <a href="#" onclick="event.preventDefault(); logout();" class="sidebar__nav-link" style="color: #EF4444;">
                            <span class="sidebar__nav-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                            </span>
                            <span class="sidebar__nav-label" style="font-weight: 500;">Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        {{-- User Profile --}}
        <div class="sidebar__profile">
            <div class="sidebar__profile-avatar">
                <div id="sidebarInitial">?</div>
            </div>
            <div class="sidebar__profile-info">
                <span class="sidebar__profile-name" id="sidebarUserName">Memuat...</span>
                <span class="sidebar__profile-id" id="sidebarUserId">ID: </span>
            </div>
        </div>

    </aside>
    {{-- ===== END SIDEBAR ===== --}}


    {{-- ===== MAIN WRAPPER ===== --}}
    <div class="main-wrapper">

        {{-- Topbar --}}
        <header class="topbar" style="display: flex; align-items: center; justify-content: space-between; width: 100%;">

            {{-- Bagian Kiri: Logo --}}
            <div class="topbar__left" style="display: flex; align-items: center; gap: 20px;">

                {{-- Kotak Putih untuk Logo --}}
                <div class="topbar__logo-box" style="background-color: #FFFFFF; padding: 10px 30px; border-radius: var(--radius-sm, 8px); display: flex; align-items: center; justify-content: center; min-width: 220px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <img src="{{ asset('images/logo_nurtura.png') }}" alt="Logo Nurtura" style="max-height: 35px; width: auto;"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                    <span style="display:none; font-family: var(--font-display); font-size: 22px; font-weight: 600;">Nurtura</span>
                </div>

            </div>

            {{-- Bagian Kanan: Aksi --}}
            <div class="topbar__actions" style="display: flex; align-items: center; gap: 12px;">
                <div style="position: relative;">
                    <button class="topbar__icon-btn" aria-label="Notifikasi">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.73 21a2 2 0 01-3.46 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <span class="topbar__notif-dot"></span>
                </div>
                <button class="topbar__icon-btn" aria-label="Pengaturan">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z" stroke="currentColor" stroke-width="1.8"/>
                    </svg>
                </button>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="page-content">
            @yield('content')
        </main>

    </div>
    {{-- ===== END MAIN WRAPPER ===== --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('scripts')

    <script>
        function logout() {
            fetch('/api/auth/logout', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json'
                }
            }).finally(() => {
                localStorage.removeItem('token');
                localStorage.removeItem('nurtura_user_name');
                localStorage.removeItem('nurtura_user_id');
                window.location.href = '/login';
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const token = localStorage.getItem('token');
            if (!token) { window.location.href = '/login'; return; }

            const cachedName = localStorage.getItem('nurtura_user_name');
            const cachedId   = localStorage.getItem('nurtura_user_id');
            if (cachedName) {
                document.getElementById('sidebarUserName').textContent = cachedName;
                document.getElementById('sidebarInitial').textContent  = cachedName.charAt(0).toUpperCase();
            }
            if (cachedId) {
                document.getElementById('sidebarUserId').textContent = 'ID: ' + cachedId;
            }
        });

        function setSidebarUser(name, id) {
            if (!name) return;
            localStorage.setItem('nurtura_user_name', name);
            if (id) localStorage.setItem('nurtura_user_id', id);

            document.getElementById('sidebarUserName').textContent = name;
            document.getElementById('sidebarInitial').textContent  = name.charAt(0).toUpperCase();
            if (id) document.getElementById('sidebarUserId').textContent = 'ID: ' + id;
        }
    </script>
</body>
</html>