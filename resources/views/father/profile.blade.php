{{-- views/father/profile.blade.php --}}
@extends('father.layout')

@section('title', 'Profil Bapak')

@section('content')

{{-- PAGE HEADER --}}
<div style="margin-bottom: 20px;">
    <h1 style="font-family: var(--font-display); font-size: 20px; font-weight: 600; color: var(--clr-text-heading); margin: 0; line-height: 1.2;">
        Profil Bapak
    </h1>
    <p style="font-size: 12.5px; color: var(--clr-text-muted); margin-top: 3px;">
        Kelola informasi pribadi dan preferensi notifikasi pendampingan Anda.
    </p>
</div>

{{-- Layout Stack (Vertikal) --}}
<div style="display: flex; flex-direction: column; gap: 18px;">

    {{-- BAGIAN ATAS: DATA DIRI --}}
    <div class="card">
        <div class="card__header">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; background: var(--clr-primary-light); color: var(--clr-primary); border-radius: 6px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </span>
                <div class="card__title">Data Diri</div>
            </div>
        </div>
        <div class="card__body" style="padding: 20px;">
            <div style="display: flex; gap: 24px; align-items: center;">
                {{-- Avatar --}}
                <div style="position: relative; flex-shrink: 0;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--clr-bg); border: 2.5px solid var(--clr-border-light); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        @if(Auth::user()->photo ?? false)
                            <img src="{{ asset('storage/' . Auth::user()->photo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span style="font-size: 28px; font-weight: 600; color: var(--clr-primary);">
                                {{ strtoupper(substr(Auth::user()->name ?? 'B', 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <button type="button" style="position: absolute; bottom: 0; right: 0; width: 26px; height: 26px; border-radius: 50%; background: var(--clr-primary); color: white; border: 2px solid white; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                </div>

                {{-- Form Fields --}}
                <div style="flex: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 11px; color: var(--clr-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Nama Lengkap</label>
                        <input type="text" value="{{ Auth::user()->name ?? 'Budi Santoso' }}" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid var(--clr-border-light); font-size: 13px; color: var(--clr-text-heading); outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; color: var(--clr-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Alamat Email</label>
                        <input type="email" value="{{ Auth::user()->email ?? 'budi.santoso@email.com' }}" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid var(--clr-border-light); font-size: 13px; color: var(--clr-text-heading); outline: none;">
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn--outline" style="padding: 8px 20px; font-size: 13px;">Batalkan</button>
                <button type="button" class="btn btn--primary" style="padding: 8px 20px; font-size: 13px;">Simpan Perubahan</button>
            </div>
        </div>
    </div>

    {{-- BAGIAN BAWAH: STATUS KONEKSI --}}
    <div class="card">
        <div class="card__header" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="card__title">Status Koneksi</div>
            <span style="font-size: 10px; font-weight: 700; background: #E6F4EA; color: #2E7D32; padding: 3px 10px; border-radius: 20px;">TERHUBUNG</span>
        </div>
        <div class="card__body" style="padding: 20px;">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 20px;">
                
                {{-- Profil Istri --}}
                <div style="display: flex; align-items: center; gap: 12px; min-width: 200px;">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--clr-primary-light); overflow: hidden; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                        @if(isset($wife) && $wife->photo)
                            <img src="{{ asset('storage/' . $wife->photo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span style="font-weight: 600; color: var(--clr-primary);">{{ strtoupper(substr($wife->name ?? 'S', 0, 1)) }}</span>
                        @endif
                    </div>
                    <div>
                        <div style="font-size: 14px; font-weight: 600; color: var(--clr-text-heading);">{{ $wife->name ?? 'Siti Aminah' }}</div>
                        <div style="font-size: 11px; color: var(--clr-text-muted);">Terhubung Sejak {{ isset($wife) ? \Carbon\Carbon::parse($wife->connected_at)->translatedFormat('d M Y') : '12 Jan 2024' }}</div>
                    </div>
                </div>

                {{-- Indikator Fitur --}}
                <div style="display: flex; gap: 8px; flex: 1;">
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 11.5px; color: var(--clr-text-heading); background: var(--clr-bg); padding: 6px 12px; border-radius: 6px; border: 1px solid var(--clr-border-light);">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        Kesehatan Aktif
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 11.5px; color: var(--clr-text-heading); background: var(--clr-bg); padding: 6px 12px; border-radius: 6px; border: 1px solid var(--clr-border-light);">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Notifikasi Janji
                    </div>
                </div>

                {{-- Warning Ringkas --}}
                <div style="padding: 8px 12px; background: #FFF4E5; border-radius: 8px; border-left: 3px solid #FF9800; max-width: 280px;">
                    <p style="font-size: 10px; color: #856404; margin: 0; line-height: 1.3;">
                        <b>Peringatan:</b> Update harian akan berhenti jika koneksi diputus.
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection