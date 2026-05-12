{{-- views/father/panduan.blade.php --}}
@extends('father.layout')

@section('title', 'Panduan Dukungan Suami')

@section('content')

{{-- Page Header: Jarak bawah dipersempit agar tidak terlalu turun --}}
<div style="margin-bottom: 16px;">
    <h1 style="font-family: var(--font-display); font-size: 20px; font-weight: 600; color: var(--clr-text-heading); margin: 0; line-height: 1.2;">
        Panduan Dukungan Suami
    </h1>
    <p style="font-size: 12.5px; color: var(--clr-text-muted); margin-top: 4px; line-height: 1.4; max-width: 800px;">
        Segala hal yang perlu Ayah ketahui untuk menjadi pendamping terbaik bagi Bunda di masa kehamilan. Mari berikan dukungan penuh kasih setiap harinya.
    </p>
</div>

{{-- Container Utama (Full Width): Gap disesuaikan agar simetris --}}
<div style="display: flex; flex-direction: column; gap: 20px; width: 100%;">

    {{-- Card: Alokasi Perhatian (Full ke Kanan & Menempel ke Atas) --}}
    <div class="card" style="width: 100%; margin: 0;">
        <div class="card__header" style="padding-bottom: 10px;">
            <div class="card__title" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--clr-text-muted);">Alokasi Perhatian Ayah</div>
        </div>
        <div class="card__body" style="padding-top: 0;">
            <div style="display: flex; height: 32px; border-radius: 8px; overflow: hidden; margin-bottom: 12px;">
                <div style="width: 40%; background: #A3B18A; color: white; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;">Edukasi (40%)</div>
                <div style="width: 40%; background: #DAD2BC; color: #847F67; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;">Tindakan (40%)</div>
                <div style="width: 20%; background: #A5A9B4; color: white; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;">Notif (20%)</div>
            </div>
            <div style="display: flex; gap: 20px; font-size: 11px; color: var(--clr-text-muted);">
                <span style="display: flex; align-items: center; gap: 6px;"><span style="width: 7px; height: 7px; border-radius: 50%; background: #A3B18A;"></span> Pengetahuan</span>
                <span style="display: flex; align-items: center; gap: 6px;"><span style="width: 7px; height: 7px; border-radius: 50%; background: #DAD2BC;"></span> Aksi Nyata</span>
                <span style="display: flex; align-items: center; gap: 6px;"><span style="width: 7px; height: 7px; border-radius: 50%; background: #A5A9B4;"></span> Pengingat Cerdas</span>
            </div>
        </div>
    </div>

    {{-- Section: Berita & Edukasi --}}
    <div>
        <h2 style="font-family: var(--font-display); font-size: 16px; font-weight: 600; color: var(--clr-text-heading); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 18px;">🎓</span> Berita & Edukasi
        </h2>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px;">
            {{-- Artikel 1 --}}
            <div class="card" style="padding: 0; overflow: hidden; margin: 0;">
                <div style="height: 140px; background: #EDE0D4;">
                    <img src="{{ asset('images/panduan/hormon-bunda.jpg') }}" onerror="this.style.display='none'" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="padding: 15px;">
                    <span style="font-size: 10px; font-weight: 700; color: var(--clr-primary); text-transform: uppercase;">Trimester 2</span>
                    <h3 style="font-size: 14px; font-weight: 600; margin: 6px 0; color: var(--clr-text-heading); line-height: 1.3;">Memahami Perubahan Hormon Bunda</h3>
                    <p style="font-size: 12px; color: var(--clr-text-muted); line-height: 1.4; margin-bottom: 12px;">Cara menghadapi mood swing Bunda dengan sabar.</p>
                    <a href="#" style="font-size: 12px; font-weight: 600; color: var(--clr-primary); text-decoration: none;">Baca Selengkapnya →</a>
                </div>
            </div>
            {{-- Artikel 2 --}}
            <div class="card" style="padding: 0; overflow: hidden; margin: 0;">
                <div style="height: 140px; background: #E6F4EA;"></div>
                <div style="padding: 15px;">
                    <span style="font-size: 10px; font-weight: 700; color: #2E7D32; text-transform: uppercase;">Umum</span>
                    <h3 style="font-size: 14px; font-weight: 600; margin: 6px 0; color: var(--clr-text-heading); line-height: 1.3;">Nutrisi Awal Kehamilan</h3>
                    <p style="font-size: 12px; color: var(--clr-text-muted); line-height: 1.4; margin-bottom: 12px;">Panduan asupan folat dan zat besi untuk Bunda.</p>
                    <a href="#" style="font-size: 12px; font-weight: 600; color: var(--clr-primary); text-decoration: none;">Baca Selengkapnya →</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Section: Aksi & Pengingat (Full Width & Simetris) --}}
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px;">
        
        {{-- Card: Aksi Nyata --}}
        <div class="card" style="margin: 0;">
            <div class="card__header" style="margin-bottom: 12px;">
                <div class="card__title" style="display: flex; align-items: center; gap: 8px;">
                    <span>💪</span> Aksi Nyata Hari Ini
                </div>
            </div>
            <div class="card__body" style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 10px; padding: 10px; border: 1px solid var(--clr-border-light); border-radius: 8px;">
                    <div style="width: 32px; height: 32px; border-radius: 6px; background: var(--clr-primary-light); display: flex; align-items: center; justify-content: center; font-size: 14px;">💊</div>
                    <div style="flex: 1;">
                        <div style="font-size: 12px; font-weight: 600; color: var(--clr-text-heading);">Minum Vitamin</div>
                        <div style="font-size: 10.5px; color: var(--clr-text-muted);">Ingatkan Bunda pagi ini</div>
                    </div>
                    <input type="checkbox" style="width: 16px; height: 16px; accent-color: var(--clr-primary); cursor: pointer;">
                </div>
                <div style="display: flex; align-items: center; gap: 10px; padding: 10px; border: 1px solid var(--clr-border-light); border-radius: 8px;">
                    <div style="width: 32px; height: 32px; border-radius: 6px; background: #FDE2E4; display: flex; align-items: center; justify-content: center; font-size: 14px;">❤️</div>
                    <div style="flex: 1;">
                        <div style="font-size: 12px; font-weight: 600; color: var(--clr-text-heading);">Berikan Pelukan</div>
                        <div style="font-size: 10.5px; color: var(--clr-text-muted);">Kurangi kecemasan Bunda</div>
                    </div>
                    <input type="checkbox" style="width: 16px; height: 16px; accent-color: var(--clr-primary); cursor: pointer;">
                </div>
            </div>
        </div>

        {{-- Card: Pengingat Cerdas --}}
        <div class="card" style="margin: 0;">
            <div class="card__header" style="margin-bottom: 12px;">
                <div class="card__title" style="display: flex; align-items: center; gap: 8px;">
                    <span>🔔</span> Pengingat Cerdas
                </div>
            </div>
            <div class="card__body">
                <div style="background: var(--clr-bg); padding: 12px; border-radius: 8px; border-left: 4px solid var(--clr-primary);">
                    <div style="font-size: 12px; font-weight: 600; color: var(--clr-text-heading);">Kontrol Kehamilan</div>
                    <div style="font-size: 11px; color: var(--clr-text-muted); margin-top: 3px;">Kamis, 8 Mei 2026 • 09:00</div>
                    <div style="display: inline-block; margin-top: 8px; padding: 2px 8px; background: #E6F4EA; color: #2E7D32; border-radius: 10px; font-size: 10px; font-weight: 600;">3 Hari Lagi</div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection