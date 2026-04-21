@extends('admin.layout')

@section('title', 'Manajemen Model — Nurtura Family')

{{-- Dikosongkan agar judul di topbar (layout) tidak muncul, persis seperti halaman Skrining --}}
@section('page_title', '') 

@push('styles')
<style>
  /* =========================================================
     CSS KHUSUS HALAMAN MANAJEMEN MODEL
     ========================================================= */

  /* Search & Button di Header */
  .m-search-box {
    position: relative;
  }
  .m-search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94A3B8;
  }
  .m-search-input {
    padding: 10px 16px 10px 36px;
    border-radius: 6px;
    border: 1px solid #E2E8F0;
    outline: none;
    font-size: 13px;
    width: 240px;
    background: #FFFFFF;
    color: var(--clr-text-heading);
    transition: all 0.2s;
  }
  .m-search-input:focus {
    border-color: var(--clr-primary);
  }
  .m-btn-publish {
    background: #A3B18A;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    transition: background 0.2s;
  }
  .m-btn-publish:hover {
    background: #8E9C76;
  }

  /* Tabs */
  .m-tabs {
    display: flex;
    gap: 24px;
    border-bottom: 1px solid var(--clr-border);
    margin-bottom: 24px;
  }
  .m-tab {
    padding: 12px 0;
    color: var(--clr-text-muted);
    font-weight: 500;
    font-size: 14px;
    text-decoration: none;
    position: relative;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .m-tab.active {
    color: var(--clr-text-heading);
    font-weight: 600;
  }
  .m-tab.active::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    height: 2px;
    background-color: var(--clr-primary);
  }
  .m-tab-badge {
    font-size: 10px;
    background: var(--clr-primary-light);
    color: var(--clr-primary-dark);
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 600;
  }

  /* Grid Layout */
  .m-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    align-items: start;
  }

  /* Cards */
  .m-card {
    background: var(--clr-surface);
    border-radius: var(--radius-card);
    padding: 24px;
    box-shadow: var(--shadow-card);
    border: 1px solid var(--clr-border-light);
    margin-bottom: 24px;
  }
  .m-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }
  .m-card-title {
    font-size: 15px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--clr-text-heading);
  }
  .m-tag {
    font-size: 11px;
    font-weight: 600;
    background: var(--clr-primary-light);
    color: var(--clr-primary-dark);
    padding: 4px 10px;
    border-radius: 20px;
  }

  /* Question List */
  .q-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }
  .q-item {
    background: var(--clr-accent-sand);
    padding: 16px 20px;
    border-radius: 8px;
  }
  .q-text {
    font-size: 14px;
    font-weight: 600;
    color: var(--clr-text-heading);
    margin-bottom: 12px;
  }
  .q-options {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }
  .q-btn {
    background: #FFFFFF;
    border: 1px solid var(--clr-border);
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    color: var(--clr-text-body);
    cursor: pointer;
    transition: all 0.2s;
  }
  .q-btn:hover {
    border-color: var(--clr-primary);
    color: var(--clr-primary-dark);
  }

  /* Timeline Riwayat */
  .tl-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  .tl-item {
    display: flex;
    gap: 16px;
  }
  .tl-date {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 30px;
  }
  .tl-month {
    font-size: 10px;
    font-weight: 600;
    color: var(--clr-text-muted);
    text-transform: uppercase;
  }
  .tl-day {
    font-size: 14px;
    font-weight: 600;
    color: var(--clr-text-heading);
  }
  .tl-content h4 {
    font-size: 13px;
    font-weight: 600;
    color: var(--clr-text-heading);
    margin-bottom: 4px;
  }
  .tl-content p {
    font-size: 12px;
    color: var(--clr-text-label);
    line-height: 1.4;
  }
  .tl-link {
    display: block;
    text-align: center;
    font-size: 13px;
    font-weight: 600;
    color: var(--clr-primary-dark);
    margin-top: 24px;
    text-decoration: none;
  }
  .tl-link:hover {
    text-decoration: underline;
  }

  /* Confidence Card */
  .conf-label {
    font-size: 11px;
    font-weight: 600;
    color: var(--clr-primary-dark);
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 8px;
  }
  .conf-value-wrap {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin-bottom: 8px;
  }
  .conf-value {
    font-size: 32px;
    font-weight: 700;
    font-family: var(--font-display);
    color: var(--clr-text-heading);
  }
  .conf-up {
    font-size: 13px;
    font-weight: 600;
    color: #2E7D32;
  }
  .conf-desc {
    font-size: 12px;
    color: var(--clr-text-label);
  }
</style>
@endpush

@section('content')

{{-- ── Header Area (Menggunakan class yang sama dengan Skrining) ── --}}
<div class="page-header">
  <div>
    <h2 class="page-header__title">Manajemen Model & Dataset</h2>
    <p class="page-header__desc">
      Konfigurasi parameter klinis, kuesioner, dan riwayat dataset sistem Nurtura untuk akurasi diagnosis risiko kesehatan keluarga.
    </p>
  </div>
  
  <div class="page-header__actions">
    {{-- Search Bar --}}
    <div class="m-search-box">
      <svg class="m-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none">
        <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
        <line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
      <input type="text" class="m-search-input" placeholder="Cari parameter...">
    </div>

    {{-- Tombol Publish --}}
    <button class="m-btn-publish">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5-5 5 5M12 15V5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      Publish Versi Baru
    </button>
  </div>
</div>

{{-- ── Tabs ──────────────────────────────────────────────────── --}}
<div class="m-tabs">
  <a href="#" class="m-tab">Daftar Gejala</a>
  <a href="#" class="m-tab active">Kuesioner</a>
  <a href="#" class="m-tab">Bobot Risiko</a>
  <a href="#" class="m-tab">Riwayat Versi <span class="m-tab-badge">V.2.4</span></a>
</div>

{{-- ── Main Grid ─────────────────────────────────────────────── --}}
<div class="m-grid">

  {{-- KOLOM KIRI (Preview Kuesioner) --}}
  <div class="m-col-left">
    <div class="m-card">
      <div class="m-card-header">
        <div class="m-card-title">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/>
            <path d="M9 10h6M9 14h6M9 18h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
          </svg>
          Preview Kuesioner
        </div>
        <span class="m-tag">Mental Health</span>
      </div>

      <div class="q-list">
        {{-- Q1 --}}
        <div class="q-item">
          <div class="q-text">Q1: Berapa frekuensi konsumsi protein hewani dalam seminggu?</div>
          <div class="q-options">
            <button class="q-btn">< 3 kali</button>
            <button class="q-btn">3-5 kali</button>
            <button class="q-btn">> 5 kali</button>
          </div>
        </div>

        {{-- Q2 --}}
        <div class="q-item">
          <div class="q-text">Q2: Apakah anak menunjukkan tanda keterlambatan bicara sesuai usianya?</div>
          <div class="q-options">
            <button class="q-btn">Ya</button>
            <button class="q-btn">Tidak</button>
          </div>
        </div>

        {{-- Q3 --}}
        <div class="q-item">
          <div class="q-text">Q3: Apakah anak menunjukkan tanda keterlambatan bicara sesuai usianya?</div>
          <div class="q-options">
            <button class="q-btn">Ya</button>
            <button class="q-btn">Tidak</button>
          </div>
        </div>

        {{-- Q4 --}}
        <div class="q-item">
          <div class="q-text">Q4: Apakah anak menunjukkan tanda keterlambatan bicara sesuai usianya?</div>
          <div class="q-options">
            <button class="q-btn">Ya</button>
            <button class="q-btn">Tidak</button>
          </div>
        </div>
        
        {{-- Q5 --}}
        <div class="q-item">
          <div class="q-text">Q5: Apakah anak menunjukkan tanda keterlambatan bicara sesuai usianya?</div>
          <div class="q-options">
            <button class="q-btn">Ya</button>
            <button class="q-btn">Tidak</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- KOLOM KANAN (Riwayat & Confidence) --}}
  <div class="m-col-right">
    
    {{-- Card Riwayat --}}
    <div class="m-card">
      <div class="m-card-header" style="margin-bottom: 24px;">
        <div class="m-card-title">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/>
            <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
          </svg>
          Riwayat Versi Model
        </div>
      </div>

      <div class="tl-list">
        {{-- Item 1 --}}
        <div class="tl-item">
          <div class="tl-date">
            <span class="tl-month">DEC</span>
            <span class="tl-day">24</span>
          </div>
          <div class="tl-content">
            <h4>V.2.4.0 (Active)</h4>
            <p>Penambahan 5 parameter baru untuk deteksi dini stunting.</p>
          </div>
        </div>

        {{-- Item 2 --}}
        <div class="tl-item">
          <div class="tl-date">
            <span class="tl-month">NOV</span>
            <span class="tl-day">24</span>
          </div>
          <div class="tl-content">
            <h4>V.2.3.8</h4>
            <p>Optimasi bobot faktor lingkungan pada diagnosis asma.</p>
          </div>
        </div>

        {{-- Item 3 --}}
        <div class="tl-item">
          <div class="tl-date">
            <span class="tl-month">OCT</span>
            <span class="tl-day">24</span>
          </div>
          <div class="tl-content">
            <h4>V.2.3.0</h4>
            <p>Update database kuesioner kesehatan mental remaja.</p>
          </div>
        </div>
      </div>

      <a href="#" class="tl-link">Lihat Changelog Lengkap</a>
    </div>

    {{-- Card Confidence --}}
    <div class="m-card">
      <div class="conf-label">Model Confidence</div>
      <div class="conf-value-wrap">
        <div class="conf-value">94.2%</div>
        <div class="conf-up">↑1.2%</div>
      </div>
      <div class="conf-desc">Berdasarkan 12,450 dataset valid.</div>
    </div>

  </div>

</div>

@endsection