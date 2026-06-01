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
    <style>
        .topbar__notif-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            min-width: 18px;
            height: 18px;
            border-radius: 999px;
            background: #EF4444;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
        }
        .topbar__notif-panel {
            position: absolute;
            right: 0;
            top: calc(100% + 10px);
            width: 320px;
            max-height: 420px;
            overflow: hidden;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.16);
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            z-index: 50;
        }
        .topbar__notif-list {
            max-height: 320px;
            overflow-y: auto;
        }
        .topbar__notif-item:hover {
            background: rgba(15, 23, 42, 0.04);
        }
        .topbar__notif-item-unread {
            background: rgba(239, 68, 68, 0.06);
        }
    </style>

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

                {{-- MENU ASISTEN AYAH (CHATBOT) DITAMBAHKAN DI SINI --}}
                <li class="sidebar__nav-item {{ request()->routeIs('father.chatbot') ? 'active' : '' }}">
                    <a href="{{ route('father.chatbot') }}" class="sidebar__nav-link">
                        <span class="sidebar__nav-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                                <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="sidebar__nav-label">
                            Asisten Ayah
                            <small>Chatbot AI</small>
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
                        <a href="#" onclick="event.preventDefault(); openLogoutConfirm();" class="sidebar__nav-link" style="color: #EF4444;">
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
                <div class="topbar__logo-box" style="background-color: #FFFFFF; padding: 10px 30px; border-radius: var(--radius-sm, 8px); display: flex; align-items: center; justify-content: center; min-width: 220px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <img src="{{ asset('images/logo_nurtura.png') }}" alt="Logo Nurtura" style="max-height: 35px; width: auto;"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                    <span style="display:none; font-family: var(--font-display); font-size: 22px; font-weight: 600;">Nurtura</span>
                </div>
            </div>

            {{-- Bagian Kanan: hanya notifikasi --}}
            <div class="topbar__actions" style="display: flex; align-items: center; gap: 12px;">
                <div class="topbar__notif-wrapper" style="position: relative;">
                    <button id="notificationButton" type="button" class="topbar__icon-btn topbar__notif-button" aria-label="Notifikasi">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.73 21a2 2 0 01-3.46 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                        <span id="notificationCount" class="topbar__notif-badge">0</span>
                    </button>
                    <div id="notificationPanel" class="topbar__notif-panel" hidden>
                        <div style="padding: 14px 16px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(15, 23, 42, 0.08);">
                            <span style="font-weight: 700; color: #0F172A;">Notifikasi</span>
                            <button id="markAllReadBtn" type="button" style="background: transparent; border: none; color: #4B5563; font-size: 12px; cursor: pointer;">Tandai semua</button>
                        </div>
                        <div id="notificationList" class="topbar__notif-list"></div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="page-content">
            @yield('content')
        </main>

    </div>
    {{-- ===== END MAIN WRAPPER ===== --}}

    {{-- ===== MODAL CONFIRM LOGOUT ===== --}}
    <div id="logoutConfirmModal" class="logout-modal" hidden>
        <div class="logout-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="logoutConfirmTitle">
            <div class="logout-modal__icon" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </div>
            <h2 id="logoutConfirmTitle" class="logout-modal__title">Konfirmasi Logout</h2>
            <p class="logout-modal__text">Anda yakin ingin keluar dari akun ini?</p>
            <div class="logout-modal__actions">
                <button type="button" class="logout-modal__btn logout-modal__btn--cancel" onclick="closeLogoutConfirm()">Batal</button>
                <button type="button" class="logout-modal__btn logout-modal__btn--danger" onclick="logout()">Logout</button>
            </div>
        </div>
    </div>

    {{-- ===== POP UP SUKSES SIMPAN PROFIL ===== --}}
    <div id="successModal" class="logout-modal" hidden>
        <div class="logout-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="successModalTitle">
            <div class="logout-modal__icon" aria-hidden="true" style="background-color: #EFF6EE; color: #8FA874;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h2 id="successModalTitle" class="logout-modal__title" style="margin-top: 15px;">Berhasil</h2>
            <p class="logout-modal__text">Profil berhasil diperbarui!</p>
            <div class="logout-modal__actions" style="justify-content: center; margin-top: 20px;">
                <button type="button" class="logout-modal__btn" style="background-color: #8FA874; color: #fff; width: 100%; max-width: 150px; border: none;" onclick="closeSuccessModal()">OK</button>
            </div>
        </div>
    </div>

    {{-- ===== MODAL DETAIL PEMERIKSAAN (DIBUTUHKAN HALAMAN MONITORING) ===== --}}
    <div id="detailCheckModal" class="logout-modal" hidden>
        <div class="logout-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="detailModalTitle">
            <div class="logout-modal__icon" aria-hidden="true" style="background-color: #F0FDF4; color: #16A34A;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
            </div>
            <h2 id="detailModalTitle" class="logout-modal__title">Detail Skrining</h2>
            <p id="detailModalText" class="logout-modal__text" style="font-size: 14px; color: #4B5563;">Memuat detail...</p>
            <div class="logout-modal__actions" style="justify-content: center; margin-top: 20px;">
                <button type="button" class="logout-modal__btn" style="background-color: #4B5563; color: #fff; width: 100%; max-width: 150px; border: none;" onclick="document.getElementById('detailCheckModal').hidden = true;">Tutup</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('scripts')
    <script src="{{ asset('js/shared/notifications.js') }}"></script>

    <script>
        function openLogoutConfirm() {
            document.getElementById('logoutConfirmModal').hidden = false;
        }

        function closeLogoutConfirm() {
            document.getElementById('logoutConfirmModal').hidden = true;
        }

        function openSuccessModal() {
            document.getElementById('successModal').hidden = false;
        }

        function closeSuccessModal() {
            document.getElementById('successModal').hidden = true;
            window.location.reload(); // Refresh akan terjadi HANYA setelah tombol OK di klik
        }

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
            const logoutModal = document.getElementById('logoutConfirmModal');
            logoutModal.addEventListener('click', function (event) {
                if (event.target === logoutModal) closeLogoutConfirm();
            });

            const successModal = document.getElementById('successModal');
            successModal.addEventListener('click', function (event) {
                if (event.target === successModal) closeSuccessModal();
            });

            // Handler menutup modal detail jika area luar diklik
            const detailModal = document.getElementById('detailCheckModal');
            if (detailModal) {
                detailModal.addEventListener('click', function (event) {
                    if (event.target === detailModal) detailModal.hidden = true;
                });
            }

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !logoutModal.hidden) closeLogoutConfirm();
                if (event.key === 'Escape' && !successModal.hidden) closeSuccessModal();
                if (event.key === 'Escape' && detailModal && !detailModal.hidden) detailModal.hidden = true;
            });

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

            // --- PERBAIKAN TOMBOL SIMPAN DI SINI ---
            const btnSimpan = document.getElementById('btnSimpanProfil');
            if (btnSimpan) {
                btnSimpan.addEventListener('click', function(e) {
                    e.preventDefault(); // Mencegah form langsung submit/refresh sendiri
                    
                    const originalText = btnSimpan.textContent;
                    btnSimpan.disabled = true;
                    btnSimpan.textContent = 'Menyimpan...'; // Loading cepat
                    
                    // Simulasikan request jika endpoint belum siap, atau gunakan fetch aslimu
                    Promise.resolve()
                        .then(() => {
                            // Tampilkan pop-up!
                            openSuccessModal(); 
                        })
                        .finally(() => {
                            // Kembalikan tombol ke bentuk semula
                            btnSimpan.disabled = false;
                            btnSimpan.textContent = originalText;
                        });
                });
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