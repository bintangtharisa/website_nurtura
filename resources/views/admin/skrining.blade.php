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
      <a href="{{ route('admin.export') }}" class="btn btn--outline">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
          <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"
                stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
          <polyline points="7 10 12 15 17 10"
                    stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round" fill="none"/>
          <line x1="12" y1="15" x2="12" y2="3"
                stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        Export CSV
      </a>
      <button class="btn btn--primary"
              onclick="document.getElementById('modal-input').classList.remove('hidden')">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
          <line x1="12" y1="5" x2="12" y2="19"
                stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          <line x1="5" y1="12" x2="19" y2="12"
                stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        Input Manual
      </button>
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
        <span class="stat-card__delta stat-card__delta--up">↑ 12%</span>
      </div>
      <span class="stat-card__label">Total Skrining</span>
      <span class="stat-card__value">1,284</span>
      <span class="stat-card__note">Berdasarkan 30 hari terakhir</span>
    </div>

    {{-- Resiko Tinggi --}}
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
        <span class="stat-card__delta stat-card__delta--down">↓ 8%</span>
      </div>
      <span class="stat-card__label">Resiko Tinggi</span>
      <span class="stat-card__value">156</span>
      <span class="stat-card__note">Menurun dari bulan lalu</span>
    </div>

    {{-- Rata-rata Skor --}}
    <div class="stat-card">
      <div class="stat-card__top">
        <div class="stat-card__icon stat-card__icon--score">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
            <path d="M18 20V10M12 20V4M6 20v-6"
                  stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <span class="stat-card__delta stat-card__delta--up">↑ 3%</span>
      </div>
      <span class="stat-card__label">Rata-rata Skor</span>
      <span class="stat-card__value">72%</span>
      <span class="stat-card__note">Indikator kesehatan membaik</span>
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
               placeholder="Cari Kode Unik atau Status..."
               id="searchInput" />
      </div>

      {{-- Dropdown Resiko --}}
      <div class="filter-select">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
          <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"
                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <select id="filterResiko" onchange="applyFilters()">
          <option value="">Resiko: Semua</option>
          <option value="rendah">Rendah</option>
          <option value="sedang">Sedang</option>
          <option value="tinggi">Tinggi</option>
        </select>
      </div>

      {{-- Dropdown Waktu --}}
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

      <div class="filter-bar__spacer"></div>

      {{-- Segment Tabs --}}
      <div class="filter-tabs" id="filterTabs">
        <button class="filter-tabs__btn active" data-val="">Semua</button>
        <button class="filter-tabs__btn" data-val="rendah">Rendah</button>
        <button class="filter-tabs__btn" data-val="sedang">Sedang</button>
        <button class="filter-tabs__btn" data-val="tinggi">Tinggi</button>
      </div>

    </div>

    {{-- Tabel --}}
    <table class="data-table" id="skriningTable">
      <thead>
        <tr>
          <th>Kode Unik</th>
          <th>Tanggal</th>
          <th>Kategori Resiko</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        <tr data-resiko="rendah">
          <td><span class="td-code">NUR-123</span></td>
          <td><span class="td-muted">12 Okt 2023, 14:20</span></td>
          <td><span class="badge badge--low">Resiko Rendah</span></td>
        </tr>
        <tr data-resiko="tinggi">
          <td><span class="td-code">NUR-124</span></td>
          <td><span class="td-muted">12 Okt 2023, 11:05</span></td>
          <td><span class="badge badge--high">Resiko Tinggi</span></td>
        </tr>
        <tr data-resiko="sedang">
          <td><span class="td-code">NUR-125</span></td>
          <td><span class="td-muted">11 Okt 2023, 16:45</span></td>
          <td><span class="badge badge--medium">Resiko Sedang</span></td>
        </tr>
        <tr data-resiko="rendah">
          <td><span class="td-code">NUR-126</span></td>
          <td><span class="td-muted">11 Okt 2023, 09:30</span></td>
          <td><span class="badge badge--low">Resiko Rendah</span></td>
        </tr>
        <tr data-resiko="tinggi">
          <td><span class="td-code">NUR-127</span></td>
          <td><span class="td-muted">10 Okt 2023, 22:15</span></td>
          <td><span class="badge badge--high">Resiko Tinggi</span></td>
        </tr>
        <tr data-resiko="sedang">
          <td><span class="td-code">NUR-128</span></td>
          <td><span class="td-muted">10 Okt 2023, 14:00</span></td>
          <td><span class="badge badge--medium">Resiko Sedang</span></td>
        </tr>
        <tr data-resiko="rendah">
          <td><span class="td-code">NUR-129</span></td>
          <td><span class="td-muted">09 Okt 2023, 10:22</span></td>
          <td><span class="badge badge--low">Resiko Rendah</span></td>
        </tr>
        <tr data-resiko="tinggi">
          <td><span class="td-code">NUR-130</span></td>
          <td><span class="td-muted">09 Okt 2023, 08:55</span></td>
          <td><span class="badge badge--high">Resiko Tinggi</span></td>
        </tr>
        <tr data-resiko="rendah">
          <td><span class="td-code">NUR-131</span></td>
          <td><span class="td-muted">08 Okt 2023, 19:10</span></td>
          <td><span class="badge badge--low">Resiko Rendah</span></td>
        </tr>
        <tr data-resiko="sedang">
          <td><span class="td-code">NUR-132</span></td>
          <td><span class="td-muted">08 Okt 2023, 13:45</span></td>
          <td><span class="badge badge--medium">Resiko Sedang</span></td>
        </tr>
      </tbody>
    </table>

    {{-- Empty State --}}
    <div class="empty-state hidden" id="emptyState">
      <div class="empty-state__icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
          <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"
                stroke="#9CA3AF" stroke-width="1.8" stroke-linecap="round"/>
          <rect x="9" y="3" width="6" height="4" rx="1" stroke="#9CA3AF" stroke-width="1.8"/>
        </svg>
      </div>
      <span class="empty-state__title">Tidak ada data ditemukan</span>
      <span class="empty-state__desc">Coba ubah filter atau kata kunci pencarian.</span>
    </div>

    {{-- Pagination --}}
    <div class="pagination">
      <span class="pagination__info" id="paginationInfo">Menampilkan 1–5 dari 10 hasil</span>
      <div class="pagination__pages">
        <button class="pagination__btn" id="btnPrev" onclick="changePage(-1)" disabled>
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
            <polyline points="15 18 9 12 15 6"
                      stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
        <button class="pagination__btn active" onclick="goPage(1)" id="pg1">1</button>
        <button class="pagination__btn"        onclick="goPage(2)" id="pg2">2</button>
        <button class="pagination__btn" id="btnNext" onclick="changePage(1)">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
            <polyline points="9 18 15 12 9 6"
                      stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
      </div>
    </div>

  </div>

  {{-- MODAL: INPUT MANUAL --}}
  <div class="modal-backdrop hidden" id="modal-input"
       onclick="if(event.target===this) this.classList.add('hidden')">
    <div class="modal">
      <div class="modal__header">
        <span class="modal__title">Input Data Manual</span>
        <button class="modal__close"
                onclick="document.getElementById('modal-input').classList.add('hidden')">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
            <line x1="18" y1="6" x2="6" y2="18"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <line x1="6" y1="6" x2="18" y2="18"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
      </div>
      <div class="modal__body">
        <div class="form-group">
          <label class="form-label">Kode Unik</label>
          <input type="text" class="form-input" placeholder="Contoh: NUR-133" />
        </div>
        <div class="form-group">
          <label class="form-label">Tanggal Skrining</label>
          <input type="datetime-local" class="form-input" />
        </div>
        <div class="form-group">
          <label class="form-label">Kategori Resiko</label>
          <select class="form-select">
            <option value="">— Pilih Kategori —</option>
            <option value="rendah">Resiko Rendah</option>
            <option value="sedang">Resiko Sedang</option>
            <option value="tinggi">Resiko Tinggi</option>
          </select>
        </div>
      </div>
      <div class="modal__footer">
        <button class="btn btn--outline"
                onclick="document.getElementById('modal-input').classList.add('hidden')">
          Batal
        </button>
        <button class="btn btn--primary"
                onclick="document.getElementById('modal-input').classList.add('hidden')">
          Simpan Data
        </button>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
<script>
  const PER_PAGE    = 5;
  let currentPage   = 1;
  let activeResiko  = '';
  let searchKw      = '';

  function allRows() {
    return Array.from(document.querySelectorAll('#tableBody tr'));
  }

  function filteredRows() {
    return allRows().filter(row => {
      const code   = row.querySelector('.td-code').textContent.toLowerCase();
      const resiko = row.dataset.resiko;
      return (!searchKw    || code.includes(searchKw))
          && (!activeResiko || resiko === activeResiko);
    });
  }

  function renderTable() {
    const rows   = filteredRows();
    const total  = rows.length;
    const pages  = Math.max(1, Math.ceil(total / PER_PAGE));
    const start  = (currentPage - 1) * PER_PAGE;
    const end    = start + PER_PAGE;

    // Tampilkan / sembunyikan baris
    allRows().forEach(r => r.style.display = 'none');
    rows.slice(start, end).forEach(r => r.style.display = '');

    // Empty state
    const empty = document.getElementById('emptyState');
    const table = document.getElementById('skriningTable');
    if (total === 0) {
      empty.classList.remove('hidden');
      table.style.display = 'none';
    } else {
      empty.classList.add('hidden');
      table.style.display = '';
    }

    // Info teks
    document.getElementById('paginationInfo').textContent = total === 0
      ? 'Tidak ada hasil'
      : `Menampilkan ${start + 1}–${Math.min(end, total)} dari ${total} hasil`;

    // Tombol halaman
    const pg1 = document.getElementById('pg1');
    const pg2 = document.getElementById('pg2');
    pg1.classList.toggle('active', currentPage === 1);
    pg2.classList.toggle('active', currentPage === 2);
    pg2.style.display = pages >= 2 ? '' : 'none';

    document.getElementById('btnPrev').disabled = currentPage <= 1;
    document.getElementById('btnNext').disabled = currentPage >= pages;
  }

  // Segment tabs — sinkron ke dropdown
  document.getElementById('filterTabs').addEventListener('click', function (e) {
    const btn = e.target.closest('.filter-tabs__btn');
    if (!btn) return;
    this.querySelectorAll('.filter-tabs__btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    activeResiko = btn.dataset.val;
    document.getElementById('filterResiko').value = activeResiko;
    currentPage = 1;
    renderTable();
  });

  // Dropdown resiko — sinkron ke tabs
  function applyFilters() {
    activeResiko = document.getElementById('filterResiko').value;
    document.querySelectorAll('.filter-tabs__btn').forEach(btn => {
      btn.classList.toggle('active', btn.dataset.val === activeResiko);
    });
    currentPage = 1;
    renderTable();
  }

  // Search dengan debounce
  let st;
  document.getElementById('searchInput').addEventListener('input', function () {
    clearTimeout(st);
    st = setTimeout(() => {
      searchKw    = this.value.trim().toLowerCase();
      currentPage = 1;
      renderTable();
    }, 300);
  });

  function changePage(dir) { currentPage += dir; renderTable(); }
  function goPage(p)        { currentPage = p;   renderTable(); }

  // Init
  renderTable();
</script>
@endpush