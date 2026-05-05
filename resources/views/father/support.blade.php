{{-- views/father/panduan.blade.php --}}
@extends('father.layout')

@section('title', 'Panduan Dukungan Suami')

@section('content')

<div class="page-content">

  {{-- ── PAGE HEADER ─────────────────────────────────────────── --}}
  <div class="panduan-header">
    <div>
      <h1 class="panduan-header__title">Panduan Dukungan Suami</h1>
      <p class="panduan-header__subtitle">
        Segala hal yang perlu Ayah ketahui untuk menjadi pendamping terbaik bagi
        Bunda di masa kehamilan. Mari berikan dukungan penuh kasih setiap harinya.
      </p>
    </div>
  </div>

  {{-- ── ALOKASI PERHATIAN AYAH ──────────────────────────────── --}}
  <div class="card alokasi-card">
    <div class="card__header">
      <div>
        <p class="alokasi-card__label">ALOKASI PERHATIAN AYAH</p>
      </div>
    </div>
    <div class="card__body">
      {{-- Progress bar --}}
      <div class="alokasi-bar">
        <div class="alokasi-bar__segment alokasi-bar__segment--edukasi"  style="width:40%">Edukasi (40%)</div>
        <div class="alokasi-bar__segment alokasi-bar__segment--tindakan" style="width:40%">Tindakan (40%)</div>
        <div class="alokasi-bar__segment alokasi-bar__segment--notif"    style="width:20%">Notif (20%)</div>
      </div>
      {{-- Legend --}}
      <div class="alokasi-legend">
        <span class="alokasi-legend__item">
          <span class="alokasi-legend__dot alokasi-legend__dot--pengetahuan"></span>
          Pengetahuan
        </span>
        <span class="alokasi-legend__item">
          <span class="alokasi-legend__dot alokasi-legend__dot--aksi"></span>
          Aksi Nyata
        </span>
        <span class="alokasi-legend__item">
          <span class="alokasi-legend__dot alokasi-legend__dot--pengingat"></span>
          Pengingat Cerdas
        </span>
      </div>
    </div>
  </div>

  {{-- ── BERITA / ARTIKEL ────────────────────────────────────── --}}
  <div class="panduan-section">
    <h2 class="panduan-section__heading">
      <span class="panduan-section__icon">🎓</span> Berita
    </h2>

    <div class="artikel-grid">

      {{-- Card 1 – Featured (wide) --}}
      <div class="artikel-card artikel-card--featured">
        <div class="artikel-card__img-wrap">
          <img
            src="{{ asset('images/panduan/hormon-bunda.jpg') }}"
            alt="Memahami Perubahan Hormon Bunda"
            class="artikel-card__img"
            onerror="this.style.background='#EDE0D4';this.removeAttribute('src')"
          >
        </div>
        <div class="artikel-card__body">
          <span class="artikel-card__trimester">TRIMESTER 2</span>
          <h3 class="artikel-card__title">Memahami Perubahan Hormon Bunda</h3>
          <p class="artikel-card__desc">
            Pelajari cara menghadapi mood swing dan perubahan fisik yang dialami istri.
          </p>
          <a href="#" class="btn btn--outline artikel-card__btn">Baca Selengkapnya</a>
        </div>
      </div>

      {{-- Card 2 --}}
      <div class="artikel-card">
        <div class="artikel-card__img-wrap artikel-card__img-wrap--sm">
          <div class="artikel-card__img-placeholder artikel-card__img-placeholder--sage"></div>
        </div>
        <div class="artikel-card__body">
          <span class="artikel-card__trimester">TRIMESTER 1</span>
          <h3 class="artikel-card__title">Nutrisi Penting di Awal Kehamilan</h3>
          <p class="artikel-card__desc">
            Panduan asupan folat, zat besi, dan vitamin yang dibutuhkan Bunda.
          </p>
          <a href="#" class="btn btn--outline artikel-card__btn">Baca Selengkapnya</a>
        </div>
      </div>

      {{-- Card 3 --}}
      <div class="artikel-card">
        <div class="artikel-card__img-wrap artikel-card__img-wrap--sm">
          <div class="artikel-card__img-placeholder artikel-card__img-placeholder--rose"></div>
        </div>
        <div class="artikel-card__body">
          <span class="artikel-card__trimester">TRIMESTER 3</span>
          <h3 class="artikel-card__title">Persiapan Persalinan Bersama</h3>
          <p class="artikel-card__desc">
            Tips praktis mendampingi Bunda menjelang hari persalinan tiba.
          </p>
          <a href="#" class="btn btn--outline artikel-card__btn">Baca Selengkapnya</a>
        </div>
      </div>

      {{-- Card 4 --}}
      <div class="artikel-card">
        <div class="artikel-card__img-wrap artikel-card__img-wrap--sm">
          <div class="artikel-card__img-placeholder artikel-card__img-placeholder--sand"></div>
        </div>
        <div class="artikel-card__body">
          <span class="artikel-card__trimester">UMUM</span>
          <h3 class="artikel-card__title">Komunikasi Efektif dengan Pasangan</h3>
          <p class="artikel-card__desc">
            Cara mengungkapkan kasih sayang dan empati kepada Bunda setiap hari.
          </p>
          <a href="#" class="btn btn--outline artikel-card__btn">Baca Selengkapnya</a>
        </div>
      </div>

    </div>{{-- /.artikel-grid --}}

    <div class="panduan-viewall-wrap">
      <a href="#" class="btn-viewall">Lihat Semua Artikel</a>
    </div>
  </div>

  {{-- ── AKSI NYATA ──────────────────────────────────────────── --}}
  <div class="panduan-section">
    <h2 class="panduan-section__heading">
      <span class="panduan-section__icon">💪</span> Aksi Nyata Hari Ini
    </h2>

    <div class="aksi-list">

      <div class="aksi-item">
        <div class="aksi-item__icon-wrap aksi-item__icon-wrap--primary">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18z"/><path d="M12 7v5l3 3"/></svg>
        </div>
        <div class="aksi-item__info">
          <p class="aksi-item__title">Ingatkan Bunda minum vitamin pagi ini</p>
          <p class="aksi-item__desc">Konsistensi asupan vitamin sangat penting di trimester ini.</p>
        </div>
        <label class="aksi-item__check">
          <input type="checkbox" class="aksi-item__checkbox">
          <span class="aksi-item__checkmark"></span>
        </label>
      </div>

      <div class="aksi-item">
        <div class="aksi-item__icon-wrap aksi-item__icon-wrap--rose">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </div>
        <div class="aksi-item__info">
          <p class="aksi-item__title">Berikan pelukan hangat untuk Bunda</p>
          <p class="aksi-item__desc">Sentuhan kasih sayang membantu mengurangi kecemasan ibu hamil.</p>
        </div>
        <label class="aksi-item__check">
          <input type="checkbox" class="aksi-item__checkbox">
          <span class="aksi-item__checkmark"></span>
        </label>
      </div>

      <div class="aksi-item">
        <div class="aksi-item__icon-wrap aksi-item__icon-wrap--sage">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <div class="aksi-item__info">
          <p class="aksi-item__title">Siapkan camilan sehat untuk Bunda</p>
          <p class="aksi-item__desc">Buah segar atau kacang-kacangan bisa jadi pilihan yang baik.</p>
        </div>
        <label class="aksi-item__check">
          <input type="checkbox" class="aksi-item__checkbox">
          <span class="aksi-item__checkmark"></span>
        </label>
      </div>

    </div>
  </div>

  {{-- ── PENGINGAT CERDAS ────────────────────────────────────── --}}
  <div class="panduan-section">
    <h2 class="panduan-section__heading">
      <span class="panduan-section__icon">🔔</span> Pengingat Cerdas
    </h2>

    <div class="pengingat-grid">

      <div class="pengingat-card pengingat-card--primary">
        <div class="pengingat-card__icon">📅</div>
        <div>
          <p class="pengingat-card__title">Kontrol Kehamilan</p>
          <p class="pengingat-card__desc">Kamis, 8 Mei 2025 • 09:00</p>
        </div>
        <span class="pengingat-card__badge pengingat-card__badge--soon">3 hari lagi</span>
      </div>

      <div class="pengingat-card">
        <div class="pengingat-card__icon">💊</div>
        <div>
          <p class="pengingat-card__title">Vitamin Pagi Bunda</p>
          <p class="pengingat-card__desc">Setiap hari • 07:30</p>
        </div>
        <span class="pengingat-card__badge pengingat-card__badge--done">Rutin</span>
      </div>

      <div class="pengingat-card">
        <div class="pengingat-card__icon">🏃</div>
        <div>
          <p class="pengingat-card__title">Jalan Pagi Bersama</p>
          <p class="pengingat-card__desc">Sabtu & Minggu • 06:30</p>
        </div>
        <span class="pengingat-card__badge pengingat-card__badge--done">Rutin</span>
      </div>

    </div>
  </div>

</div>{{-- /.page-content --}}

@endsection