<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta username="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Nurtura Family')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=Lora:wght@500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" />
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
        <span class="sidebar__brand-username">Nurtura Family</span>
        <span class="sidebar__brand-role">Admin Panel</span>
      </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar__nav">
      <ul class="sidebar__nav-list">

        <li class="sidebar__nav-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
          <a href="{{ route('admin.dashboard') }}" class="sidebar__nav-link">
            <span class="sidebar__nav-icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                <rect x="3" y="3" width="7" height="7" rx="1.5" fill="currentColor"/>
                <rect x="14" y="3" width="7" height="7" rx="1.5" fill="currentColor"/>
                <rect x="3" y="14" width="7" height="7" rx="1.5" fill="currentColor"/>
                <rect x="14" y="14" width="7" height="7" rx="1.5" fill="currentColor"/>
              </svg>
            </span>
            <span class="sidebar__nav-label">
              Dashboard
              <small>Monitoring</small>
            </span>
          </a>
        </li>

        <li class="sidebar__nav-item {{ request()->routeIs('admin.skrining*') ? 'active' : '' }}">
          <a href="{{ route('admin.skrining') }}" class="sidebar__nav-link">
            <span class="sidebar__nav-icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L4 5v7c0 5.25 3.5 10.15 8 11 4.5-.85 8-5.75 8-11V5l-8-3z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
            <span class="sidebar__nav-label">
              Data Skrining
              <small>(Anonim)</small>
            </span>
          </a>
        </li>

        <li class="sidebar__nav-item {{ request()->routeIs('admin.model*') ? 'active' : '' }}">
          <a href="{{ route('admin.model') }}" class="sidebar__nav-link">
            <span class="sidebar__nav-icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/>
                <path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.93 4.93l2.12 2.12M16.95 16.95l2.12 2.12M4.93 19.07l2.12-2.12M16.95 7.05l2.12-2.12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
              </svg>
            </span>
            <span class="sidebar__nav-label">Manajemen Model</span>
          </a>
        </li>

        <li class="sidebar__nav-item {{ request()->routeIs('admin.export*') ? 'active' : '' }}">
          <a href="{{ route('admin.export') }}" class="sidebar__nav-link">
            <span class="sidebar__nav-icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <polyline points="7 10 12 15 17 10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
              </svg>
            </span>
            <span class="sidebar__nav-label">Export Data</span>
          </a>
        </li>

      </ul>
    </nav>

    {{-- Spacer --}}
    <div class="sidebar__spacer"></div>

    {{-- Logout --}}
    <div class="sidebar__logout">
<button onclick="logout()">Logout</button>

<script>
function logout() {
    fetch('/api/auth/logout', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token'),
            'Accept': 'application/json'
        }
    }).then(() => {
        localStorage.removeItem('token');
        window.location.href = '/login';
    });
}
</script>
    </div>

    {{-- User Profile --}}
    <div class="sidebar__profile">
      <div class="sidebar__profile-avatar">
        <div id="userInitial">U</div>
      </div>
      <div class="sidebar__profile-info">
        <span class="sidebar__profile-username"  id="usernameText" ></span>
        <span class="sidebar__profile-id" id="userIdText">ID: </span>
      </div>
    </div>

  </aside>
  {{-- ===== END SIDEBAR ===== --}}


  {{-- ===== MAIN WRAPPER ===== --}}
  <div class="main-wrapper">

    {{-- Topbar --}}
    <header class="topbar">
      <h1 class="topbar__page-title">@yield('page_title', 'Dashboard')</h1>
      <div class="topbar__actions">
        <div class="topbar__search">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="topbar__search-icon">
            <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="1.8"/>
            <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
          </svg>
          <input type="text" class="topbar__search-input" placeholder="Search unique ID..." />
        </div>
        <button class="topbar__icon-btn" aria-label="Notifikasi">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M13.73 21a2 2 0 01-3.46 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
          </svg>
        </button>
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

  @stack('scripts')

<script>
document.addEventListener("DOMContentLoaded", function () {

    const token = localStorage.getItem("token");

    if (!token) {
        window.location.href = "/login";
        return;
    }

    fetch("/api/profile", {
        headers: {
            "Authorization": "Bearer " + token,
            "Accept": "application/json"
        }
    })
    .then(res => res.json())
    .then(data => {

        if (!data || !data.username) {
            window.location.href = "/login";
            return;
        }

        document.getElementById("userInitial").innerText =
            data.username.charAt(0).toUpperCase();

        document.getElementById("usernameText").innerText =
            data.username;

        document.getElementById("userIdText").innerText =
            "ID: " + data.id;

    })
    .catch(() => {
        window.location.href = "/login";
    });

});
</script>
</body>
</html>