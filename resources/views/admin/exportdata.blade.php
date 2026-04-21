@extends('admin.layout')

@section('title', 'Export Data — Nurtura Family')

{{-- Dikosongkan agar judul di topbar (layout) tidak muncul, persis seperti halaman Skrining --}}
@section('page_title', '') 

@push('styles')
<style>
  /* =========================================================
     CSS KHUSUS HALAMAN EXPORT DATA (Hanya untuk Card)
     ========================================================= */

  /* Cards */
  .e-card {
    background: #FFFFFF;
    border-radius: 12px;
    padding: 32px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    border: 1px solid var(--clr-border-light, #E2E8F0);
    margin-bottom: 24px;
    min-height: 200px;
  }

  .e-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 600;
    color: var(--clr-text-heading, #1E293B);
    margin-bottom: 32px;
  }

  .e-card-header svg {
    color: #9EB384; /* Warna hijau icon header */
  }

  /* Options Grid (Card 1) */
  .e-options {
    display: flex;
    gap: 60px;
    margin-left: 32px; /* Indentasi agar sejajar dengan teks header */
  }

  .e-opt {
    display: flex;
    gap: 16px;
    align-items: flex-start;
  }

  .e-opt-icon {
    color: #9EB384;
  }

  .e-opt-text h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--clr-text-heading, #1E293B);
    margin-bottom: 6px;
  }

  .e-opt-text p {
    font-size: 12px;
    color: var(--clr-text-muted, #94A3B8);
  }

  /* Actions Area (Card 2) */
  .e-actions-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-left: 32px;
  }

  .e-formats {
    display: flex;
    gap: 12px;
  }

  .e-format-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 8px;
    border: none;
    background: transparent;
    font-size: 13px;
    font-weight: 600;
    color: var(--clr-text-body, #64748B);
    cursor: pointer;
    transition: all 0.2s;
  }

  .e-format-btn svg {
    color: var(--clr-text-muted, #94A3B8);
  }

  .e-format-btn.active {
    background-color: #F3EBE1; /* Warna beige seperti SS */
    color: var(--clr-text-heading, #1E293B);
  }

  .e-format-btn.active svg {
    color: #2E7D32; /* Hijau logo excel */
  }

  .e-submit-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background-color: #A3B18A; /* Hijau button */
    color: #FFFFFF;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
  }

  .e-submit-btn:hover {
    background-color: #8A9973;
  }
</style>
@endpush

@section('content')

{{-- ── Header Area (Menggunakan class yang sama dengan Skrining) ── --}}
<div class="page-header">
  <div>
    <h2 class="page-header__title">Export Data Statistik</h2>
    <p class="page-header__desc">
      Membuat dan mengunduh laporan kesehatan agregat dan statistik
    </p>
  </div>
</div>

{{-- ── Card 1: Select Report Type ────────────────────────────── --}}
<div class="e-card">
  <div class="e-card-header">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
      <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    1. Select Report Type
  </div>

  <div class="e-options">
    
    {{-- Option 1: Aggregate --}}
    <div class="e-opt">
      <div class="e-opt-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M3 3v18h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M18 9l-5 5-4-4-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          <circle cx="18" cy="9" r="3" stroke="currentColor" stroke-width="1.8"/>
        </svg>
      </div>
      <div class="e-opt-text">
        <h4>Aggregate Statistics</h4>
        <p>Summary of overall health trends</p>
      </div>
    </div>

    {{-- Option 2: Medical --}}
    <div class="e-opt">
      <div class="e-opt-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M14 2v6h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          <circle cx="10" cy="13" r="2" stroke="currentColor" stroke-width="1.8"/>
          <path d="M7 18v-1a3 3 0 016 0v1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
      </div>
      <div class="e-opt-text">
        <h4>Medical Reports</h4>
        <p>Detailed patient medical records</p>
      </div>
    </div>

  </div>
</div>

{{-- ── Card 2: Format & Export ───────────────────────────────── --}}
<div class="e-card">
  <div class="e-card-header">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
      <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
      <polyline points="7 10 12 15 17 10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
      <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
      2. Choose Format & Export
  </div>

  <div class="e-actions-wrapper">
    
    {{-- Formats --}}
    <div class="e-formats">
      {{-- Inactive (PDF) --}}
      <button class="e-format-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
          <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M14 2v6h6M9 15v-4h2a2 2 0 010 4H9zM15 15v-4h2M15 13h1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        PDF Document
      </button>

      {{-- Active (Excel) --}}
      <button class="e-format-btn active">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
          <rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/>
          <line x1="3" y1="9" x2="21" y2="9" stroke="currentColor" stroke-width="1.8"/>
          <line x1="9" y1="21" x2="9" y2="9" stroke="currentColor" stroke-width="1.8"/>
          <path d="M12 12l4 4M16 12l-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        Excel Spreadsheet
      </button>
    </div>

    {{-- Generate Button --}}
    <button class="e-submit-btn">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <polyline points="7 10 12 15 17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      Generate Report
    </button>

  </div>
</div>

@endsection