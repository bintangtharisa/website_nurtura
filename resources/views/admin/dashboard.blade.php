@extends('admin.layout')

@section('title', 'Dashboard Monitoring — Nurtura Family')
@section('page_title', 'Dashboard Monitoring Admin')

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
      <span class="stat-card__delta stat-card__delta--up">+12.5%</span>
    </div>
    <div class="stat-card__label">Total Users</div>
    <div class="stat-card__value">4,821</div>
  </div>

  {{-- Prediction Accuracy --}}
  <div class="stat-card">
    <div class="stat-card__top">
      <div class="stat-card__icon">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
          <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
        </svg>
      </div>
      <span class="stat-card__delta stat-card__delta--up">+19.3%</span>
    </div>
    <div class="stat-card__label">Prediction Accuracy</div>
    <div class="stat-card__value">94.8%</div>
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
    <div class="stat-card__value">1,284</div>
    <div class="stat-card__note">
      <span class="up">+12%</span> bulan ini
    </div>
  </div>

</section>


{{-- ── Prediction Trends Chart ─────────────────────────────── --}}
<section class="chart-section">
  <div class="chart-section__header">
    <div>
      <div class="chart-section__title">Prediction Trends</div>
      <div class="chart-section__subtitle">Monthly screening activity volume</div>
    </div>
    <button class="chart-section__filter">
      Last 6 Months
      <svg width="11" height="11" viewBox="0 0 24 24" fill="none">
        <polyline points="6 9 12 15 18 9" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
      </svg>
    </button>
  </div>

  {{-- Bars --}}
  <div class="bar-chart">
    <div class="bar-wrap"><div class="bar bar--rose"  style="height: 42%"></div></div>
    <div class="bar-wrap"><div class="bar bar--rose"  style="height: 55%"></div></div>
    <div class="bar-wrap"><div class="bar bar--sand"  style="height: 48%"></div></div>
    <div class="bar-wrap"><div class="bar bar--sand"  style="height: 78%"></div></div>
    <div class="bar-wrap"><div class="bar bar--green" style="height: 72%"></div></div>
    <div class="bar-wrap"><div class="bar bar--green" style="height: 96%"></div></div>
  </div>

  {{-- Month Labels --}}
  <div class="bar-labels">
    <span>Jan</span>
    <span>Feb</span>
    <span>Mar</span>
    <span>Apr</span>
    <span>May</span>
    <span>Jun</span>
  </div>
</section>


{{-- ── Recent Screenings Table ──────────────────────────────── --}}
<section class="table-section">
  <div class="table-section__header">
    <div class="table-section__title">Recent Anonymous Screenings</div>
    <a href="{{ route('admin.skrining') }}" class="table-section__viewall">View All Data</a>
  </div>

  <table class="data-table">
    <thead>
      <tr>
        <th>Unique Code</th>
        <th>Timestamp</th>
        <th>Model Version</th>
        <th>Risk Level</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($screenings as $item)
        <tr>
          <td class="td-code">{{ $item->unique_code }}</td>
          <td class="td-muted">{{ $item->created_at->format('Y-m-d H:i') }}</td>
          <td>{{ $item->model_version }}</td>
          <td>
            <span class="badge badge--{{ strtolower($item->risk_level) }}">
              {{ $item->risk_level }}
            </span>
          </td>
          <td>
            @if ($item->status === 'Verified')
              <span class="status--verified">Verified</span>
            @elseif ($item->status === 'Alert Sent')
              <span class="status--alert">Alert Sent</span>
            @else
              <span class="status--pending">{{ $item->status }}</span>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" style="text-align:center; padding: 28px; color: var(--clr-text-muted);">
            Belum ada data skrining.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</section>

@endsection