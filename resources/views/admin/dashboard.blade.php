@extends('admin.layout')

@section('title', 'Dashboard Monitoring — Nurtura Family')

{{-- Tambahkan baris ini untuk menghilangkan tulisan default --}}
@section('page_title', '') 

@section('content')

{{-- ── Stat Cards ──────────────────────────────────────────── --}}
<section class="stats-grid">

  {{-- Total Users --}}
  <div class="stat-card">
    <div class="stat-card__top">
      <div class="stat-card__icon">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
          <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
          <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/>
          <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
      </div>
    </div>
    <div class="stat-card__label">Total Users</div>
    <div class="stat-card__value" id="totalUser">Loading...</div>
    <p class="stat-desc">User yang aktif dalam 2 bulan terakhir</p>
  </div>

  {{-- Total Pengguna (highlight) --}}
  <div class="stat-card stat-card--highlight">
    <div class="stat-card__top">
      <div class="stat-card__icon">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
          <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
          <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/>
        </svg>
      </div>
    </div>
    <div class="stat-card__label">Total Pengguna</div>
    <div class="stat-card__value" id="totalPengguna">Loading...</div>
  </div>

</section>


{{-- ── Prediction Trends Chart ─────────────────────────────── --}}
<section class="chart-section">
  <div class="chart-section__header">
    <div>
      <div class="chart-section__title">Prediction Trends</div>
      <div class="chart-section__subtitle" id="predictionTrendSubtitle">Tren hasil prediksi ibu per kuartal</div>
    </div>
    <div class="chart-section__filter" id="predictionTrendYear">Tahun Ini</div>
  </div>

  <div class="trend-chart" id="predictionTrendChart">
    <div class="trend-chart__empty">Memuat grafik...</div>
  </div>

  <div class="trend-legend">
    <span><i class="trend-legend__dot trend-legend__dot--high"></i>Beresiko</span>
    <span><i class="trend-legend__dot trend-legend__dot--low"></i>Tidak Beresiko</span>
    <span><i class="trend-legend__dot trend-legend__dot--unknown"></i>Tidak Diketahui</span>
  </div>
</section>


{{-- ── Recent Screenings Table ──────────────────────────────── --}}
<section class="table-section">
  <div class="table-section__header">
    <div class="table-section__title">Recent Anonymous Screenings</div>
    <a href="{{ route('admin.riwayat') }}" class="table-section__viewall">View All Data</a>
  </div>

  <table class="data-table">
    <thead>
      <tr>
        <th>Kode Ibu Anonim</th>
        <th>Tanggal</th>
        <th>Kategori Resiko</th>
      </tr>
    </thead>
    <tbody id="screeningTable">
      <tr>
        <td colspan="3">Loading...</td>
      </tr>
    </tbody>
  </table>
</section>
<script src="{{ asset('js/admin/dashboard.js') }}"></script>
@endsection
