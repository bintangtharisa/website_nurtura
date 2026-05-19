@extends('father.layout')

@section('title', 'Monitoring Kondisi Istri')

@section('content')

<div style="display:flex; flex-wrap:wrap; align-items:flex-start; justify-content:space-between; gap:16px; margin-bottom:18px;">
    <div>
        <h1 style="font-family: var(--font-display); font-size:22px; font-weight:700; color:var(--clr-text-heading); margin:0; line-height:1.2;">Monitoring Kondisi Istri</h1>
        <p style="font-size:13px; color:var(--clr-text-muted); margin-top:6px; line-height:1.6; max-width:760px;">Lihat riwayat skrining ibu dengan username terdaftar, bukan anonymous_id. Header dan tabel dirancang mirip admin riwayat.</p>
    </div>
    <div style="display:inline-flex; align-items:center; gap:8px; background:#EFF6FF; color:#1D4ED8; border-radius:999px; padding:8px 14px; font-size:12px; font-weight:700;">HISTORY MODE</div>
</div>

<style>
  .monitoring-wrapper{width:100%; max-width:none; margin:0; padding:12px; box-sizing:border-box;}
  .monitoring-grid{display:grid; gap:18px; width:100%;}
  .cards-grid{display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:16px; width:100%;}
  .monitoring-controls{display:grid; grid-template-columns:1fr minmax(140px,220px) minmax(140px,220px); gap:12px; align-items:center; margin-top:18px; width:100%; min-width:0;}
  .monitoring-controls > *{min-width:0;}
  .monitoring-controls input, .monitoring-controls select{min-width:0;}
  .table-scroll{overflow-x:auto; width:100%;}
  .monitoring-table{width:100%; border-collapse:collapse; min-width:0; table-layout: auto}
  .monitoring-table th, .monitoring-table td{padding:10px 12px; vertical-align: middle; word-break: break-word; white-space: normal;}
  .monitoring-table th{font-size:11px; font-weight:700; text-transform:uppercase; color:var(--clr-text-muted)}
  @media (max-width: 880px) {
    .cards-grid{grid-template-columns:1fr}
    .monitoring-controls{grid-template-columns:1fr;}
    .monitoring-grid{grid-auto-rows:auto}
    .monitoring-wrapper{padding:10px}
  }
  @media (max-width: 640px) {
    .monitoring-table th, .monitoring-table td{padding:8px 8px; font-size:12px;}
    .monitoring-wrapper{padding:8px;}
  }
</style>

<div class="monitoring-wrapper monitoring-grid">
  <div class="cards-grid">
        <div style="background:#FFFFFF; border:1px solid rgba(15, 23, 42, 0.08); border-radius:24px; padding:20px;">
            <div style="font-size:11px; text-transform:uppercase; letter-spacing:0.08em; color:var(--clr-text-muted); margin-bottom:10px;">Total Skrining</div>
            <div style="font-size:28px; font-weight:700; color:var(--clr-text-heading);">{{ number_format($totalScreenings) }}</div>
            <div style="font-size:12px; color:var(--clr-text-muted); margin-top:10px;">Berdasarkan collection prediction_results</div>
        </div>
        <div style="background:#FFFFFF; border:1px solid rgba(15, 23, 42, 0.08); border-radius:24px; padding:20px;">
            <div style="font-size:11px; text-transform:uppercase; letter-spacing:0.08em; color:var(--clr-text-muted); margin-bottom:10px;">Total Beresiko</div>
            <div style="font-size:28px; font-weight:700; color:var(--clr-text-heading);">{{ number_format($highRiskCount) }}</div>
            <div style="font-size:12px; color:var(--clr-text-muted); margin-top:10px;">Hasil skrining berisiko tinggi</div>
        </div>
        <div style="background:#FFFFFF; border:1px solid rgba(15, 23, 42, 0.08); border-radius:24px; padding:20px;">
            <div style="font-size:11px; text-transform:uppercase; letter-spacing:0.08em; color:var(--clr-text-muted); margin-bottom:10px;">Total Tidak Beresiko</div>
            <div style="font-size:28px; font-weight:700; color:var(--clr-text-heading);">{{ number_format($lowRiskCount) }}</div>
            <div style="font-size:12px; color:var(--clr-text-muted); margin-top:10px;">Hasil skrining berisiko rendah</div>
        </div>
    </div>

    <div style="background:#FFFFFF; border:1px solid rgba(15, 23, 42, 0.08); border-radius:24px; overflow:hidden;">
        <div style="padding:20px; border-bottom:1px solid rgba(15, 23, 42, 0.08);">
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
                <div>
                    <div style="font-size:18px; font-weight:700; color:var(--clr-text-heading);">Riwayat Skrining Istri</div>
                    <div style="font-size:13px; color:var(--clr-text-muted); margin-top:6px; max-width:720px;">Menampilkan hasil skrining ibu dengan nama pengguna yang terdaftar, bukan anonymous_id.</div>
                </div>
                <a href="javascript:void(0)" style="font-size:13px; font-weight:700; color:var(--clr-primary);">Lihat Semua →</a>
            </div>
            <div class="monitoring-controls">
                <div style="display:flex; align-items:center; gap:10px; background:#F8FAFC; border:1px solid rgba(15, 23, 42, 0.08); border-radius:14px; padding:10px 14px; min-width:0;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                    <input id="searchInput" type="text" placeholder="Cari nama ibu atau hasil skrining..." style="width:100%; min-width:0; border:none; outline:none; background:transparent; font-size:13px; color:var(--clr-text-heading);" />
                </div>
                <select id="filterWaktu" style="width:100%; min-width:0; padding:10px 14px; border-radius:14px; border:1px solid rgba(15, 23, 42, 0.12); background:#fff; font-size:13px; color:var(--clr-text-heading);">
                    <option value="30">Waktu: 30 Hari</option>
                    <option value="7">7 Hari</option>
                    <option value="90">90 Hari</option>
                    <option value="0">Semua</option>
                </select>
                <select id="perPageSelect" style="width:100%; min-width:0; padding:10px 14px; border-radius:14px; border:1px solid rgba(15, 23, 42, 0.12); background:#fff; font-size:13px; color:var(--clr-text-heading);">
                    <option value="10">Tampilkan 10</option>
                    <option value="25">Tampilkan 25</option>
                    <option value="50">Tampilkan 50</option>
                </select>
            </div>
          <div class="table-scroll" style="padding:0 6px 20px;">
                <table class="monitoring-table" style="width:100%; border-collapse:collapse; min-width:0;">
                <thead>
                    <tr style="background:#F8FAFC; border-bottom:1px solid rgba(15, 23, 42, 0.08);">
                        <th style="text-align:left; letter-spacing:0.08em;">Nama Ibu</th>
                        <th style="text-align:left; letter-spacing:0.08em;">Tanggal Skrining</th>
                        <th style="text-align:left; letter-spacing:0.08em;">Kategori Risiko</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
            <div id="emptyState" style="display:none; text-align:center; padding:40px 0; color:var(--clr-text-muted); font-size:13px;">
                <div style="font-size:14px; font-weight:600; margin-bottom:8px;">Tidak ada riwayat skrining</div>
                <div>Gunakan pencarian atau ubah filter untuk melihat lebih banyak data.</div>
            </div>
        </div>
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; padding:0 20px 20px;">
            <div id="paginationInfo" style="font-size:13px; color:var(--clr-text-muted);">Menampilkan 0 hasil</div>
            <div style="display:inline-flex; gap:10px;">
                <button id="btnPrev" type="button" style="border:1px solid rgba(15, 23, 42, 0.12); border-radius:14px; padding:10px 14px; background:#fff; color:var(--clr-text-heading); cursor:pointer;" disabled>Previous</button>
                <button id="btnNext" type="button" style="border:1px solid rgba(15, 23, 42, 0.12); border-radius:14px; padding:10px 14px; background:#fff; color:var(--clr-text-heading); cursor:pointer;" disabled>Next</button>
            </div>
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
  const screenings = @json($screenings ?? []);

  const tableBody = document.getElementById('tableBody');
  const emptyState = document.getElementById('emptyState');
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
      return '<span style="display:inline-flex;align-items:center;padding:6px 14px;border-radius:999px;font-size:12px;font-weight:700;background:#FEE2E2;color:#B91C1C;">Beresiko Depresi</span>';
    }
    if (normalized.includes('tidak') || normalized.includes('rendah')) {
      return '<span style="display:inline-flex;align-items:center;padding:6px 14px;border-radius:999px;font-size:12px;font-weight:700;background:#ECFDF5;color:#166534;">Tidak Beresiko Depresi</span>';
    }
    return '<span style="display:inline-flex;align-items:center;padding:6px 14px;border-radius:999px;font-size:12px;font-weight:700;background:#E5E7EB;color:#374151;">Tidak Diketahui</span>';
  }

  function filteredData() {
    const keyword = searchKw.trim().toLowerCase();
    const cutoff = filterDays === 0 ? null : Date.now() - filterDays * 24 * 60 * 60 * 1000;
    return screenings.filter(item => {
      const rowText = `${item.mother_username} ${item.result} ${item.risk_category}`.toLowerCase();
      const matchesSearch = !keyword || rowText.includes(keyword);
      const matchesTime = !cutoff || new Date(item.created_at).getTime() >= cutoff;
      return matchesSearch && matchesTime;
    });
  }

  function renderRow(item) {
    return `
      <tr style="border-bottom: 1px solid rgba(15, 23, 42, 0.08);">
        <td style="padding:12px 12px; vertical-align:middle;">
          <div style="font-weight:600; color:var(--clr-text-heading);">${item.mother_username || 'Tidak tersedia'}</div>
          <div style="font-size:12px; color:var(--clr-text-muted); margin-top:4px;">${item.result || '-'}</div>
        </td>
        <td style="padding:12px 12px; vertical-align:middle; color:var(--clr-text-muted);">${formatDate(item.created_at)}</td>
        <td style="padding:12px 12px; vertical-align:middle;">${renderBadge(item.risk_category)}</td>
      </tr>`;
  }

  function renderTable() {
    const rows = filteredData();
    const total = rows.length;
    const pageCount = Math.max(1, Math.ceil(total / perPage));
    currentPage = Math.min(Math.max(currentPage, 1), pageCount);
    const start = (currentPage - 1) * perPage;
    const pageRows = rows.slice(start, start + perPage);

    if (tableBody) tableBody.innerHTML = pageRows.map(renderRow).join('');
    if (emptyState) emptyState.style.display = total === 0 ? '' : 'none';
    if (paginationInfo) paginationInfo.textContent = total === 0
      ? 'Tidak ada hasil'
      : `Menampilkan ${Math.min(start + 1, total)}–${Math.min(start + perPage, total)} dari ${total} hasil`;
    if (btnPrev) btnPrev.disabled = currentPage <= 1;
    if (btnNext) btnNext.disabled = currentPage >= pageCount;
  }

  function applyFilters() {
    currentPage = 1;
    renderTable();
  }

  document.addEventListener('DOMContentLoaded', function () {
    const perPageSelect = document.getElementById('perPageSelect');
    const filterWaktu = document.getElementById('filterWaktu');
    const searchInput = document.getElementById('searchInput');

    if (perPageSelect) {
      perPageSelect.addEventListener('change', function () {
        perPage = Number(this.value);
        currentPage = 1;
        applyFilters();
      });
    }

    if (filterWaktu) {
      filterWaktu.addEventListener('change', function () {
        filterDays = Number(this.value);
        applyFilters();
      });
    }

    if (searchInput) {
      let debounce;
      searchInput.addEventListener('input', function () {
        clearTimeout(debounce);
        debounce = setTimeout(() => {
          searchKw = this.value.trim();
          applyFilters();
        }, 200);
      });
    }

    if (btnPrev) {
      btnPrev.addEventListener('click', function () {
        if (currentPage > 1) {
          currentPage -= 1;
          renderTable();
        }
      });
    }

    if (btnNext) {
      btnNext.addEventListener('click', function () {
        currentPage += 1;
        renderTable();
      });
    }

    renderTable();
  });
</script>
@endpush'''); Path(r'c:\laragon\www\semester_4\nurtura-web\resources\views\father\monitoring.blade.php').write_text(content, encoding='utf-8')"