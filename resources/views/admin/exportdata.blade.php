@extends('admin.layout')

@section('title', 'Export Data - Nurtura Family')
@section('page_title', '')

@push('styles')
<style>
  .export-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.15fr) minmax(280px, 0.85fr);
    gap: 18px;
  }

  .export-card {
    background: var(--clr-surface);
    border: 1px solid var(--clr-border);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-card);
    padding: 22px;
  }

  .export-card__header {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 18px;
  }

  .export-card__icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--clr-primary-light);
    color: var(--clr-primary);
    flex-shrink: 0;
  }

  .export-card__title {
    font-family: var(--font-display);
    font-size: 15px;
    font-weight: 600;
    color: var(--clr-text-heading);
  }

  .export-card__desc {
    margin-top: 3px;
    color: var(--clr-text-muted);
    font-size: 12.5px;
    line-height: 1.5;
  }

  .export-options {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 20px;
  }

  .export-option {
    text-align: left;
    border: 1px solid var(--clr-border);
    border-radius: var(--radius-sm);
    background: var(--clr-surface);
    padding: 14px;
    min-height: 104px;
    cursor: pointer;
    transition: border-color 0.15s, background 0.15s, box-shadow 0.15s;
  }

  .export-option.active {
    background: var(--clr-primary-light);
    border-color: var(--clr-primary);
    box-shadow: 0 0 0 2px rgba(163, 177, 138, 0.14);
  }

  .export-option:disabled {
    opacity: 0.55;
    cursor: not-allowed;
  }

  .export-option__title {
    display: block;
    color: var(--clr-text-heading);
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
  }

  .export-option__desc {
    display: block;
    color: var(--clr-text-muted);
    font-size: 12px;
    line-height: 1.45;
  }

  .export-form {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
  }

  .export-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .export-field--full {
    grid-column: 1 / -1;
  }

  .export-label {
    color: var(--clr-text-label);
    font-size: 12px;
    font-weight: 600;
  }

  .export-input,
  .export-select {
    width: 100%;
    background: var(--clr-bg);
    border: 1px solid var(--clr-border);
    border-radius: var(--radius-sm);
    color: var(--clr-text-body);
    font-family: var(--font-body);
    font-size: 13px;
    outline: none;
    padding: 9px 11px;
    transition: border-color 0.15s, background 0.15s;
  }

  .export-input:focus,
  .export-select:focus {
    background: #fff;
    border-color: var(--clr-primary);
  }

  .export-hint {
    color: var(--clr-text-muted);
    font-size: 11.5px;
    line-height: 1.5;
  }

  .format-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .format-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    border: 1px solid var(--clr-border);
    border-radius: var(--radius-sm);
    background: var(--clr-surface);
    color: var(--clr-text-body);
    padding: 12px;
    text-align: left;
    transition: border-color 0.15s, background 0.15s;
  }

  .format-btn.active {
    background: var(--clr-primary-light);
    border-color: var(--clr-primary);
    color: var(--clr-text-heading);
  }

  .format-btn:disabled {
    opacity: 0.55;
    cursor: not-allowed;
  }

  .format-btn svg {
    color: var(--clr-primary);
    flex-shrink: 0;
  }

  .export-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 18px;
    padding-top: 18px;
    border-top: 1px solid var(--clr-border-light);
  }

  .export-status {
    color: var(--clr-text-muted);
    font-size: 12.5px;
    line-height: 1.45;
  }

  .export-status.error {
    color: var(--clr-high-text);
  }

  .export-submit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--clr-primary);
    color: #fff;
    border-radius: var(--radius-sm);
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 600;
    flex-shrink: 0;
    transition: background 0.15s;
  }

  .export-submit:hover {
    background: var(--clr-primary-dark);
  }

  .export-submit:disabled {
    opacity: 0.65;
    cursor: wait;
  }

  .export-note {
    margin-top: 18px;
    padding: 14px;
    background: var(--clr-bg);
    border-radius: var(--radius-sm);
    color: var(--clr-text-label);
    font-size: 12.5px;
    line-height: 1.55;
  }

  @media (max-width: 920px) {
    .export-grid,
    .export-form,
    .export-options {
      grid-template-columns: 1fr;
    }
  }
</style>
@endpush

@section('content')

<div class="page-header">
  <div>
    <h2 class="page-header__title">Export Data</h2>
    <p class="page-header__desc">
      Unduh data skrining dalam bentuk anonim untuk laporan admin dan analisis lanjutan.
    </p>
  </div>
</div>

<div class="export-grid">
  <section class="export-card">
    <div class="export-card__header">
      <div class="export-card__icon">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
          <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <div>
        <div class="export-card__title">Jenis Export</div>
        <div class="export-card__desc">Pilih dataset yang ingin diunduh. Tahap awal difokuskan pada riwayat skrining anonim.</div>
      </div>
    </div>

    <div class="export-options">
      <button type="button" class="export-option active" data-report-type="screening-history">
        <span class="export-option__title">Riwayat Skrining</span>
        <span class="export-option__desc">Data prediksi per ibu berdasarkan kode anonim.</span>
      </button>
      <button type="button" class="export-option" data-report-type="trends">
        <span class="export-option__title">Analitik Tren</span>
        <span class="export-option__desc">Rekap Q1-Q4 dan agregasi risiko.</span>
      </button>
      <button type="button" class="export-option" data-report-type="research-dataset">
        <span class="export-option__title">Dataset Penelitian</span>
        <span class="export-option__desc">Jawaban skrining anonim dan hasil prediksi.</span>
      </button>
    </div>

    <form id="exportForm" class="export-form">
      <div class="export-field export-field--full">
        <label class="export-label" for="anonymousId">Kode Ibu Anonim</label>
        <input class="export-input" id="anonymousId" name="anonymous_id" type="text" placeholder="Kosongkan untuk semua ibu" autocomplete="off">
        <span class="export-hint">Jika diisi, sistem mencari user role mother dari `anonymous_id`, lalu mengambil riwayat dari `prediction_results`.</span>
      </div>

      <div class="export-field">
        <label class="export-label" for="dateFrom">Tanggal Awal</label>
        <input class="export-input" id="dateFrom" name="date_from" type="date">
      </div>

      <div class="export-field">
        <label class="export-label" for="dateTo">Tanggal Akhir</label>
        <input class="export-input" id="dateTo" name="date_to" type="date">
      </div>

      <div class="export-field export-field--full">
        <label class="export-label" for="resultFilter">Hasil Prediksi</label>
        <select class="export-select" id="resultFilter" name="result">
          <option value="all">Semua hasil</option>
          <option value="high">Beresiko Depresi</option>
          <option value="low">Tidak Beresiko Depresi</option>
          <option value="unknown">Tidak Diketahui</option>
        </select>
      </div>
    </form>
  </section>

  <aside class="export-card">
    <div class="export-card__header">
      <div class="export-card__icon">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
          <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
          <polyline points="7 10 12 15 17 10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
      </div>
      <div>
        <div class="export-card__title">Format File</div>
        <div class="export-card__desc">CSV dipakai agar ringan dan tetap bisa dibuka di Excel.</div>
      </div>
    </div>

    <div class="format-list">
      <button type="button" class="format-btn active" data-format="csv">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
          <rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/>
          <path d="M8 9h8M8 13h8M8 17h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        <span>CSV / Excel Compatible</span>
      </button>
      <button type="button" class="format-btn" data-format="pdf">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
          <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M14 2v6h6M9 15v-4h2a2 2 0 010 4H9zM15 15v-4h2M15 13h1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>PDF Ringkasan</span>
      </button>
    </div>

    <div class="export-note">
      Data yang diexport tidak memuat email, username, password, atau relasi keluarga. Identitas ibu hanya tampil sebagai kode anonim.
    </div>

    <div class="export-actions">
      <div class="export-status" id="exportStatus">Siap membuat file export.</div>
      <button type="button" class="export-submit" id="exportSubmit">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
          <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          <polyline points="7 10 12 15 17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        Generate
      </button>
    </div>
  </aside>
</div>

@endsection

@push('scripts')
<script>
  const exportSubmit = document.getElementById('exportSubmit');
  const exportStatus = document.getElementById('exportStatus');
  const exportForm = document.getElementById('exportForm');
  let selectedReportType = 'screening-history';
  let selectedFormat = 'csv';

  const reportEndpoints = {
    'screening-history': '/api/admin/export/screenings',
    'trends': '/api/admin/export/trends',
    'research-dataset': '/api/admin/export/research-dataset'
  };

  function setExportStatus(message, isError = false) {
    exportStatus.textContent = message;
    exportStatus.classList.toggle('error', isError);
  }

  function filenameFromDisposition(disposition) {
    if (!disposition) return null;
    const match = disposition.match(/filename="?([^"]+)"?/i);
    return match ? match[1] : null;
  }

  function activateButton(buttons, activeButton) {
    buttons.forEach(button => button.classList.toggle('active', button === activeButton));
  }

  function downloadExtension() {
    return selectedFormat === 'pdf' ? 'pdf' : 'csv';
  }

  function exportEndpoint() {
    if (selectedFormat === 'pdf') {
      return '/api/admin/export/summary-pdf';
    }

    return reportEndpoints[selectedReportType] || reportEndpoints['screening-history'];
  }

  async function downloadExport() {
    const token = localStorage.getItem('token');
    if (!token) {
      window.location.href = '/login';
      return;
    }

    const formData = new FormData(exportForm);
    const params = new URLSearchParams();

    for (const [key, value] of formData.entries()) {
      if (String(value).trim() !== '') {
        params.set(key, String(value).trim());
      }
    }

    exportSubmit.disabled = true;
    setExportStatus(selectedFormat === 'pdf' ? 'Membuat PDF ringkasan...' : 'Membuat file export...');

    try {
      const response = await fetch(`${exportEndpoint()}?${params.toString()}`, {
        method: 'GET',
        headers: {
          'Authorization': 'Bearer ' + token,
          'Accept': selectedFormat === 'pdf' ? 'application/pdf' : 'text/csv'
        }
      });

      if (response.status === 401 || response.status === 403) {
        localStorage.removeItem('token');
        window.location.href = '/login';
        return;
      }

      if (!response.ok) {
        throw new Error('Gagal membuat file export.');
      }

      const blob = await response.blob();
      const filename = filenameFromDisposition(response.headers.get('Content-Disposition')) || `export-nurtura.${downloadExtension()}`;
      const url = window.URL.createObjectURL(blob);
      const link = document.createElement('a');

      link.href = url;
      link.download = filename;
      document.body.appendChild(link);
      link.click();
      link.remove();
      window.URL.revokeObjectURL(url);

      setExportStatus('File export berhasil dibuat.');
    } catch (error) {
      console.error(error);
      setExportStatus(error.message || 'Export gagal.', true);
    } finally {
      exportSubmit.disabled = false;
    }
  }

  document.querySelectorAll('.export-option[data-report-type]').forEach(button => {
    button.addEventListener('click', function () {
      selectedReportType = this.dataset.reportType;
      activateButton(document.querySelectorAll('.export-option[data-report-type]'), this);
      setExportStatus('Siap membuat file export.');
    });
  });

  document.querySelectorAll('.format-btn[data-format]').forEach(button => {
    button.addEventListener('click', function () {
      selectedFormat = this.dataset.format;
      activateButton(document.querySelectorAll('.format-btn[data-format]'), this);
      setExportStatus(selectedFormat === 'pdf' ? 'PDF akan berisi ringkasan dari filter saat ini.' : 'Siap membuat file export.');
    });
  });

  exportSubmit.addEventListener('click', downloadExport);
</script>
@endpush
