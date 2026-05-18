@extends('admin.layout')

@section('title', 'Data Skrining — Nurtura Family')

{{-- Tambahkan baris ini untuk menghilangkan tulisan default --}}
@section('page_title', '') 

@section('content')

  {{-- PAGE HEADER --}}
  <div class="page-header">
    <div>
      <h2 class="page-header__title">Data Skrining</h2>
      <p class="page-header__desc">
        Kelola dan pantau hasil skrining kesehatan keluarga secara anonim untuk menjaga privasi pengguna.
      </p>
    </div>
    <div class="page-header__actions">
    </div>
  </div>

  {{-- STAT CARDS --}}
  <div class="stats-grid">

    {{-- Total Skrining --}}
    <div class="stat-card">
      <div class="stat-card__top">
        <div class="stat-card__icon">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"
                  stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <rect x="9" y="3" width="6" height="4" rx="1" stroke="currentColor" stroke-width="1.8"/>
            <path d="M9 12h6M9 16h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
          </svg>
        </div>
        <span class="stat-card__delta stat-card__delta--up">&nbsp;</span>
      </div>
      <span class="stat-card__label">Total Skrining</span>
      <span class="stat-card__value">{{ number_format($totalScreenings) }}</span>
      <span class="stat-card__note">Berdasarkan collection prediction_results</span>
    </div>

    {{-- Beresiko Depresi --}}
    <div class="stat-card">
      <div class="stat-card__top">
        <div class="stat-card__icon stat-card__icon--alert">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
            <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"
                  stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            <line x1="12" y1="9" x2="12" y2="13"
                  stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <circle cx="12" cy="17" r="0.8" fill="currentColor"/>
          </svg>
        </div>
        <span class="stat-card__delta stat-card__delta--down">&nbsp;</span>
      </div>
      <span class="stat-card__label">Total Beresiko Depresi</span>
      <span class="stat-card__value">{{ number_format($highRiskCount) }}</span>
      <span class="stat-card__note">Diambil dari prediction_results</span>
    </div>

    {{-- Total Tidak Beresiko Depresi --}}
    <div class="stat-card">
      <div class="stat-card__top">
        <div class="stat-card__icon stat-card__icon--score">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
            <path d="M18 20V10M12 20V4M6 20v-6"
                  stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <span class="stat-card__delta stat-card__delta--up">&nbsp;</span>
      </div>
      <span class="stat-card__label">Total Tidak Beresiko Depresi</span>
      <span class="stat-card__value">{{ number_format($lowRiskCount) }}</span>
      <span class="stat-card__note">Diambil dari prediction_results</span>
    </div>

  </div>

  {{-- DATA TABLE --}}
  <div class="table-section">

    <div class="table-section__header">
      <span class="table-section__title">Hasil Skrining</span>
    </div>

    {{-- Filter Bar --}}
    <div class="filter-bar">

      {{-- Search --}}
      <div class="filter-bar__search">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" class="filter-bar__search-icon">
          <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="1.8"/>
          <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        <input type="text" class="filter-bar__search-input"
               placeholder="Cari Kode Ibu Anonim atau Hasil Skrining..."
               id="searchInput" />
      </div>

      <div class="filter-select">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
          <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/>
          <path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        <select id="filterWaktu">
          <option value="30">Waktu: 30 Hari</option>
          <option value="7">7 Hari</option>
          <option value="90">90 Hari</option>
          <option value="0">Semua</option>
        </select>
      </div>

      <div class="filter-select">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
          <path d="M4 7h16M7 12h10M10 17h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        <select id="perPageSelect">
          <option value="10">Tampilkan 10</option>
          <option value="25">Tampilkan 25</option>
          <option value="50">Tampilkan 50</option>
        </select>
      </div>

      <div class="filter-bar__spacer"></div>
    </div>

    {{-- Tabel --}}
    <div id="tableWrapper" class="table-scroll-wrapper" style="max-height: 520px; overflow-y: auto;">
      <table class="data-table" id="skriningTable">
        <thead>
          <tr>
            <th>Kode Ibu Anonim</th>
            <th>Tanggal</th>
            <th>Kategori Resiko</th>
          </tr>
        </thead>
        <tbody id="tableBody"></tbody>
      </table>
    </div>

    <div id="emptyState" class="empty-state hidden" style="margin-top: 1.5rem; text-align: center;">
      <div class="empty-state__icon" aria-hidden="true">
        <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <circle cx="12" cy="12" r="10" opacity="0.08" />
          <path d="M8 12h8M12 8v8" stroke-linecap="round"/>
        </svg>
      </div>
      <h3 class="empty-state__title">Tidak ada riwayat skrining</h3>
      <p class="empty-state__desc">Gunakan filter atau periksa kembali data untuk melihat hasil skrining.</p>
    </div>

    {{-- Pagination --}}
    <div class="pagination">
      <span class="pagination__info" id="paginationInfo">Menampilkan 0 hasil</span>
      <div class="pagination__pages">
        <button class="pagination__btn" id="btnPrev" onclick="changePage(-1)" disabled>
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
            <polyline points="15 18 9 12 15 6"
                      stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
        <button class="pagination__btn" id="btnNext" onclick="changePage(1)" disabled>
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
            <polyline points="9 18 15 12 9 6"
                      stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
      </div>
    </div>

  </div>

@endsection

@push('scripts')
<script>
  const DEFAULT_PER_PAGE = 10;
  let currentPage = 1;
  let perPage = DEFAULT_PER_PAGE;
  let searchKw = '';
  let filterDays = 30;
  let screenings = [];

  const tableBody = document.getElementById('tableBody');
  const emptyState = document.getElementById('emptyState');
  const tableWrapper = document.getElementById('tableWrapper');
  const tableElement = document.getElementById('skriningTable');
  const paginationInfo = document.getElementById('paginationInfo');
  const btnPrev = document.getElementById('btnPrev');
  const btnNext = document.getElementById('btnNext');

  function formatDate(dateString) {
    if (!dateString) return '-';
    const parsed = new Date(dateString);
    if (Number.isNaN(parsed.getTime())) return dateString;
    return parsed.toLocaleDateString('id-ID', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  }

  function renderBadge(result) {
    const normalized = String(result || '').toLowerCase();
    if (normalized.includes('ya') || normalized.includes('beresiko') || normalized.includes('tinggi')) {
      return '<span class="badge badge--high">Beresiko Depresi</span>';
    }
    if (normalized.includes('tidak') || normalized.includes('rendah')) {
      return '<span class="badge badge--low">Tidak Beresiko Depresi</span>';
    }
    return '<span class="badge">Tidak Diketahui</span>';
  }

  function filteredData() {
    const keyword = searchKw.trim().toLowerCase();
    const cutoff = filterDays === 0 ? null : Date.now() - (filterDays * 24 * 60 * 60 * 1000);

    return screenings.filter(item => {
      const rowText = `${item.anonymous_id} ${item.result} ${item.risk_category}`.toLowerCase();
      const matchesSearch = !keyword || rowText.includes(keyword);
      const matchesTime = !cutoff || new Date(item.created_at).getTime() >= cutoff;
      return matchesSearch && matchesTime;
    });
  }

  function renderRow(item) {
    return `
      <tr data-resiko="${item.risk_category}">
        <td><span class="td-code">${item.anonymous_id || '-'}</span></td>
        <td><span class="td-muted">${formatDate(item.created_at)}</span></td>
        <td>${renderBadge(item.risk_category)}</td>
      </tr>
    `;
  }

  function renderTable() {
    const rows = filteredData();
    const total = rows.length;
    const pageCount = Math.max(1, Math.ceil(total / perPage));
    currentPage = Math.min(Math.max(currentPage, 1), pageCount);
    const start = (currentPage - 1) * perPage;
    const pageRows = rows.slice(start, start + perPage);

    tableBody.innerHTML = pageRows.map(renderRow).join('');
    emptyState.style.display = total === 0 ? '' : 'none';
    tableWrapper.style.display = total === 0 ? 'none' : 'block';
    paginationInfo.textContent = total === 0
      ? 'Tidak ada hasil'
      : `Menampilkan ${Math.min(start + 1, total)}–${Math.min(start + perPage, total)} dari ${total} hasil`;

    btnPrev.disabled = currentPage <= 1;
    btnNext.disabled = currentPage >= pageCount;
  }

  function applyFilters() {
    currentPage = 1;
    renderTable();
  }

  async function loadScreenings() {
    const token = localStorage.getItem('token');
    if (!token) {
      window.location.href = '/login';
      return;
    }

    try {
      const response = await fetch('/api/admin/screenings', {
        headers: {
          'Authorization': 'Bearer ' + token,
          'Accept': 'application/json'
        }
      });
      const result = await response.json();
      if (!response.ok || !result.status) {
        if (response.status === 401 || response.status === 403) {
          window.location.href = '/login';
          return;
        }
        throw new Error(result.message || 'Gagal memuat data');
      }

      screenings = result.data || [];
      currentPage = 1;
      renderTable();
    } catch (error) {
      console.error(error);
      screenings = [];
      tableBody.innerHTML = '';
      emptyState.style.display = '';
      tableWrapper.style.display = 'none';
      paginationInfo.textContent = 'Tidak dapat memuat riwayat skrining.';
    }
  }

  document.getElementById('perPageSelect').addEventListener('change', function () {
    perPage = Number(this.value);
    currentPage = 1;
    applyFilters();
  });

  document.getElementById('filterWaktu').addEventListener('change', function () {
    filterDays = Number(this.value);
    applyFilters();
  });

  let debounce;
  document.getElementById('searchInput').addEventListener('input', function () {
    clearTimeout(debounce);
    debounce = setTimeout(() => {
      searchKw = this.value.trim();
      applyFilters();
    }, 250);
  });

  function changePage(dir) {
    currentPage += dir;
    renderTable();
  }

  document.addEventListener('DOMContentLoaded', loadScreenings);
</script>
@endpush