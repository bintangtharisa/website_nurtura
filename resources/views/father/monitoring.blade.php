@extends('father.layout')

@section('title', 'Monitoring Kondisi Istri')

@section('content')

{{-- Page Header --}}
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
    <div>
        <h1 style="font-family: var(--font-display); font-size: 20px; font-weight: 600; color: var(--clr-text-heading);">
            Monitoring Kondisi Istri
        </h1>
        <p style="font-size: 12.5px; color: var(--clr-text-muted); margin-top: 3px;">Data diperbarui secara berkala</p>
    </div>
    <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 11.5px; font-weight: 500; background: #E6F4EA; color: #2E7D32; padding: 4px 10px; border-radius: 20px;">
        <span style="width: 6px; height: 6px; background: #2E7D32; border-radius: 50%; display: inline-block;"></span>
        LIVE UPDATE
    </span>
</div>

{{-- Grid Utama --}}
<div style="display: grid; grid-template-columns: 1fr 320px; gap: 18px; align-items: start;">

    {{-- Kolom Kiri --}}
    <div style="display: flex; flex-direction: column; gap: 18px;">

        {{-- Card: Ringkasan Kesehatan Mingguan --}}
        <div class="card">
            <div class="card__header">
                <div>
                    <div class="card__title">Ringkasan Kesehatan Mingguan</div>
                    <div class="card__subtitle">Tren kondisi istri 7 hari terakhir</div>
                </div>
                <select class="period-select" id="periodSelect" onchange="updateChart()">
                    <option value="mingguan">Mingguan</option>
                    <option value="bulanan">Bulanan</option>
                </select>
            </div>
            <div class="card__body">
                {{-- Status Summary --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                    <div style="background: var(--clr-bg); border-radius: var(--radius-sm); padding: 14px 16px;">
                        <div style="font-size: 11px; color: var(--clr-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Status 7 Hari</div>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-family: var(--font-display); font-size: 20px; font-weight: 600; color: var(--clr-text-heading);" id="statusLabel">Stabil</span>
                            <span style="font-size: 11.5px; font-weight: 500; background: #E6F4EA; color: #2E7D32; padding: 3px 8px; border-radius: 20px;" id="statusDelta">↗ 5%</span>
                        </div>
                    </div>
                    <div style="background: var(--clr-bg); border-radius: var(--radius-sm); padding: 14px 16px;">
                        <div style="font-size: 11px; color: var(--clr-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Tren Bulanan</div>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-family: var(--font-display); font-size: 20px; font-weight: 600; color: var(--clr-text-heading);">Meningkat</span>
                            <span style="font-size: 11.5px; font-weight: 500; background: var(--clr-high-bg); color: var(--clr-high-text); padding: 3px 8px; border-radius: 20px;">↘ 2%</span>
                        </div>
                    </div>
                </div>
                {{-- Chart --}}
                <div class="chart-wrapper">
                    <canvas id="monitoringChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Card: Riwayat Hasil Singkat --}}
        <div class="card">
            <div class="card__header" style="margin-bottom: 0;">
                <div>
                    <div class="card__title">Riwayat Hasil Singkat</div>
                    <div class="card__subtitle">Pemeriksaan terakhir istri</div>
                </div>
                <a href="#" class="btn-viewall" style="display: inline-flex; align-items: center; gap: 4px;">
                    Lihat Semua →
                </a>
            </div>

            {{-- Table --}}
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--clr-bg); border-top: 1px solid var(--clr-border-light); border-bottom: 1px solid var(--clr-border-light);">
                            <th style="padding: 9px 22px; font-size: 10.5px; font-weight: 500; color: var(--clr-text-muted); text-align: left; text-transform: uppercase; letter-spacing: 0.06em;">Tanggal Skrining</th>
                            <th style="padding: 9px 22px; font-size: 10.5px; font-weight: 500; color: var(--clr-text-muted); text-align: left; text-transform: uppercase; letter-spacing: 0.06em;">Kategori Risiko</th>
                            <th style="padding: 9px 22px; font-size: 10.5px; font-weight: 500; color: var(--clr-text-muted); text-align: left; text-transform: uppercase; letter-spacing: 0.06em;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody id="riwayatTable">
                        {{-- Diisi JavaScript --}}
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Kolom Kanan --}}
    <div style="display: flex; flex-direction: column; gap: 18px;">

        {{-- Card: Preferensi Notifikasi --}}
        <div class="card">
            <div class="card__header">
                <div>
                    <div class="card__title">Preferensi</div>
                    <div class="card__subtitle">Mode notifikasi kondisi istri</div>
                </div>
                <span style="font-size: 18px;">🔔</span>
            </div>
            <div class="card__body">
                <div style="font-size: 12.5px; font-weight: 500; color: var(--clr-text-label); margin-bottom: 12px;">Mode Notifikasi</div>

                {{-- Option 1 --}}
                <label style="display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; padding: 12px 14px; border: 1.5px solid var(--clr-border); border-radius: var(--radius-sm); margin-bottom: 10px; cursor: pointer; transition: border-color 0.15s;" id="optionRendah" onclick="selectNotif('rendah')">
                    <div>
                        <div style="font-size: 13px; font-weight: 500; color: var(--clr-text-heading);">Hanya Risiko Tinggi</div>
                        <div style="font-size: 11.5px; color: var(--clr-text-muted); margin-top: 2px;">Peringatan untuk kondisi kritis</div>
                    </div>
                    <div style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid var(--clr-border); flex-shrink: 0; margin-top: 2px; display: flex; align-items: center; justify-content: center;" id="radioRendah"></div>
                </label>

                {{-- Option 2 --}}
                <label style="display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; padding: 12px 14px; border: 1.5px solid var(--clr-primary); border-radius: var(--radius-sm); margin-bottom: 16px; cursor: pointer; background: var(--clr-primary-light); transition: border-color 0.15s;" id="optionSemua" onclick="selectNotif('semua')">
                    <div>
                        <div style="font-size: 13px; font-weight: 500; color: var(--clr-text-heading);">Semua Perubahan</div>
                        <div style="font-size: 11.5px; color: var(--clr-text-muted); margin-top: 2px;">Update setiap tren kesehatan</div>
                    </div>
                    <div style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid var(--clr-primary); flex-shrink: 0; margin-top: 2px; display: flex; align-items: center; justify-content: center; background: var(--clr-primary);" id="radioSemua">
                        <div style="width: 7px; height: 7px; background: white; border-radius: 50%;"></div>
                    </div>
                </label>

                <button class="btn btn--primary" style="width: 100%; justify-content: center;" onclick="simpanPengaturan()">
                    Simpan Pengaturan
                </button>
                <div id="notifSaved" style="display: none; text-align: center; font-size: 12px; color: var(--clr-low-text); margin-top: 8px;">✓ Pengaturan tersimpan</div>
            </div>
        </div>

        {{-- Card: Status Risiko Saat Ini --}}
        <div class="card">
            <div class="card__header">
                <div>
                    <div class="card__title">Status Saat Ini</div>
                    <div class="card__subtitle">Kondisi terkini istri</div>
                </div>
            </div>
            <div class="card__body">
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: var(--clr-low-bg); border-radius: var(--radius-sm);">
                        <span style="font-size: 13px; font-weight: 500; color: var(--clr-low-text);">Risiko Rendah</span>
                        <span style="font-size: 20px; font-weight: 700; color: var(--clr-low-text);">25%</span>
                    </div>
                    <div style="font-size: 12px; color: var(--clr-text-muted); line-height: 1.6;">
                        Kondisi istri dalam batas normal. Tetap pantau secara rutin dan berikan dukungan emosional.
                    </div>
                    <a href="{{ route('father.support') }}" class="btn btn--outline" style="justify-content: center;">
                        Lihat Tips Dukungan
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
// ── Data dummy ──────────────────────────────────────────────
const dataMingguan = {
    labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
    values: [30, 45, 38, 55, 50, 42, 60]
};

const dataBulanan = {
    labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
    values: [40, 52, 47, 63]
};

const riwayatData = [
    { tanggal: '24 Okt 2023', waktu: '09:15 WIB', risiko: 'Rendah' },
    { tanggal: '22 Okt 2023', waktu: '18:30 WIB', risiko: 'Sedang' },
    { tanggal: '18 Okt 2023', waktu: '14:20 WIB', risiko: 'Tinggi' },
    { tanggal: '15 Okt 2023', waktu: '08:00 WIB', risiko: 'Rendah' },
];

// ── Chart ────────────────────────────────────────────────────
let chart;

function buildChart(data) {
    const ctx = document.getElementById('monitoringChart').getContext('2d');
    if (chart) chart.destroy();
    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.values,
                borderColor: '#A3B18A',
                backgroundColor: 'rgba(163,177,138,0.12)',
                borderWidth: 2,
                pointBackgroundColor: '#A3B18A',
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11, family: 'DM Sans' }, color: '#9CA3AF' }
                },
                y: {
                    grid: { color: '#F0EDE8' },
                    ticks: { font: { size: 11, family: 'DM Sans' }, color: '#9CA3AF' },
                    min: 0, max: 100
                }
            }
        }
    });
}

function updateChart() {
    const val = document.getElementById('periodSelect').value;
    buildChart(val === 'bulanan' ? dataBulanan : dataMingguan);
}

// ── Tabel Riwayat ────────────────────────────────────────────
function buildTable() {
    const badgeMap = {
        'Rendah': `<span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:500;background:var(--clr-low-bg);color:var(--clr-low-text);">Rendah</span>`,
        'Sedang': `<span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:500;background:var(--clr-med-bg);color:var(--clr-med-text);">Sedang</span>`,
        'Tinggi': `<span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:500;background:var(--clr-high-bg);color:var(--clr-high-text);">Tinggi</span>`,
    };

    const tbody = document.getElementById('riwayatTable');
    tbody.innerHTML = riwayatData.map(r => `
        <tr style="border-bottom: 1px solid var(--clr-border-light); transition: background 0.12s;" onmouseover="this.style.background='var(--clr-bg)'" onmouseout="this.style.background=''">
            <td style="padding: 13px 22px;">
                <div style="font-size: 13px; font-weight: 500; color: var(--clr-text-heading);">${r.tanggal}</div>
                <div style="font-size: 11px; color: var(--clr-text-muted);">${r.waktu}</div>
            </td>
            <td style="padding: 13px 22px;">${badgeMap[r.risiko]}</td>
            <td style="padding: 13px 22px;">
                <button onclick="lihatDetail('${r.tanggal}')" style="display:inline-flex;align-items:center;gap:4px;font-size:12px;color:var(--clr-primary);font-weight:500;padding:4px 10px;border-radius:var(--radius-sm);border:1px solid var(--clr-primary-light);background:var(--clr-primary-light);cursor:pointer;transition:all 0.15s;" onmouseover="this.style.background='var(--clr-primary)';this.style.color='white'" onmouseout="this.style.background='var(--clr-primary-light)';this.style.color='var(--clr-primary)'">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    Detail
                </button>
            </td>
        </tr>
    `).join('');
}

// ── Notifikasi Preferensi ────────────────────────────────────
let notifMode = 'semua';

function selectNotif(mode) {
    notifMode = mode;
    const optR = document.getElementById('optionRendah');
    const optS = document.getElementById('optionSemua');
    const radR = document.getElementById('radioRendah');
    const radS = document.getElementById('radioSemua');

    if (mode === 'rendah') {
        optR.style.borderColor = 'var(--clr-primary)';
        optR.style.background  = 'var(--clr-primary-light)';
        radR.style.borderColor = 'var(--clr-primary)';
        radR.style.background  = 'var(--clr-primary)';
        radR.innerHTML = '<div style="width:7px;height:7px;background:white;border-radius:50%;"></div>';

        optS.style.borderColor = 'var(--clr-border)';
        optS.style.background  = 'transparent';
        radS.style.borderColor = 'var(--clr-border)';
        radS.style.background  = 'transparent';
        radS.innerHTML = '';
    } else {
        optS.style.borderColor = 'var(--clr-primary)';
        optS.style.background  = 'var(--clr-primary-light)';
        radS.style.borderColor = 'var(--clr-primary)';
        radS.style.background  = 'var(--clr-primary)';
        radS.innerHTML = '<div style="width:7px;height:7px;background:white;border-radius:50%;"></div>';

        optR.style.borderColor = 'var(--clr-border)';
        optR.style.background  = 'transparent';
        radR.style.borderColor = 'var(--clr-border)';
        radR.style.background  = 'transparent';
        radR.innerHTML = '';
    }

    document.getElementById('notifSaved').style.display = 'none';
}

function simpanPengaturan() {
    localStorage.setItem('notifMode', notifMode);
    const el = document.getElementById('notifSaved');
    el.style.display = 'block';
    setTimeout(() => el.style.display = 'none', 2500);
}

function lihatDetail(tanggal) {
    alert('Detail pemeriksaan: ' + tanggal);
    // Ganti dengan modal atau redirect ke halaman detail
}

// ── Init ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    buildChart(dataMingguan);
    buildTable();

    const saved = localStorage.getItem('notifMode');
    if (saved) selectNotif(saved);
});
</script>
@endpush