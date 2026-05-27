@extends('father.layout')

@section('title', 'Monitoring Kondisi Istri')

@section('content')

{{-- Page Header --}}
<div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 4px;">
    <div>
        <h1 style="font-family: var(--font-display); font-size: 20px; font-weight: 600; color: var(--clr-text-heading);">
            Monitoring Kondisi Istri
        </h1>
        <p style="font-size: 12.5px; color: var(--clr-text-muted); margin-top: 3px;" id="connectedMotherLabel">Data diperbarui secara berkala</p>
    </div>
    <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 11.5px; font-weight: 500; background: #E6F4EA; color: #2E7D32; padding: 4px 10px; border-radius: 20px;">
        <span style="width: 6px; height: 6px; background: #2E7D32; border-radius: 50%; display: inline-block;"></span>
        LIVE UPDATE
    </span>
</div>

{{-- Grid Utama (Untuk Chart dan Status Saja) --}}
{{-- UBAH: align-items dari start menjadi stretch agar tinggi kolom sama --}}
<div style="display: grid; grid-template-columns: 1fr 320px; gap: 18px; align-items: stretch;">

    {{-- Kolom Kiri --}}
    <div style="display: flex; flex-direction: column; gap: 18px; min-width: 0;">

        {{-- Card: Ringkasan Kesehatan Mingguan --}}
        <div class="card" style="height: 100%;">
            <div class="card__header">
                <div>
                    <div class="card__title">Frekuensi Skrining</div>
                    <div class="card__subtitle">Jumlah screening berdasarkan periode yang dipilih</div>
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
                            <span style="font-size: 11.5px; font-weight: 500; background: #E6F4EA; color: #2E7D32; padding: 3px 8px; border-radius: 20px;" id="statusDelta">Naik 5%</span>
                        </div>
                    </div>
                    <div style="background: var(--clr-bg); border-radius: var(--radius-sm); padding: 14px 16px;">
                        <div style="font-size: 11px; color: var(--clr-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Tren Bulanan</div>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-family: var(--font-display); font-size: 20px; font-weight: 600; color: var(--clr-text-heading);">Meningkat</span>
                            <span style="font-size: 11.5px; font-weight: 500; background: var(--clr-high-bg); color: var(--clr-high-text); padding: 3px 8px; border-radius: 20px;">Turun 2%</span>
                        </div>
                    </div>
                </div>
                {{-- Chart --}}
                <div class="chart-wrapper">
                    <canvas id="monitoringChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- Kolom Kanan --}}
    {{-- UBAH: Tambah height 100% --}}
    <div style="display: flex; flex-direction: column; gap: 18px; height: 100%;">

        {{-- Card: Status Risiko Saat Ini --}}
        {{-- UBAH: Set card jadi flex column dan height 100% supaya full ke bawah --}}
        <div class="card" style="height: 100%; display: flex; flex-direction: column;">
            <div class="card__header">
                <div>
                    <div class="card__title">Status Saat Ini</div>
                    <div class="card__subtitle">Kondisi terkini istri</div>
                </div>
            </div>
            {{-- UBAH: flex: 1 supaya body card mengisi sisa ruang --}}
            <div class="card__body" style="flex: 1; display: flex; flex-direction: column;">
                <div style="display: flex; flex-direction: column; gap: 12px; height: 100%;">
                    <div id="currentResultBox" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: var(--clr-low-bg); border-radius: var(--radius-sm);">
                        <span id="currentResultLabel" style="font-size: 13px; font-weight: 500; color: var(--clr-low-text);">Tidak Beresiko Depresi</span>
                        <span id="currentResultPercent" style="font-size: 20px; font-weight: 700; color: var(--clr-low-text);">25%</span>
                    </div>
                    <div id="currentResultDescription" style="font-size: 12px; color: var(--clr-text-muted); line-height: 1.6;">
                        Kondisi istri dalam batas normal. Tetap pantau secara rutin dan berikan dukungan emosional.
                    </div>
                    {{-- UBAH: margin-top: auto supaya tombol terdorong ke paling bawah --}}
                    <a href="{{ route('father.support') }}" class="btn btn--outline" style="justify-content: center; margin-top: auto;">
                        Lihat Tips Dukungan
                    </a>
                </div>
            </div>
        </div>

    </div>

</div> {{-- Akhir Grid Utama --}}

{{-- Tabel Riwayat --}}
<div style="margin-top: 18px;">
    {{-- Card: Riwayat Hasil Singkat --}}
    <div class="card">
        <div class="card__header" style="margin-bottom: 14px; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
            <div>
                <div class="card__title">Riwayat Hasil Singkat</div>
                <div class="card__subtitle">Pemeriksaan terakhir istri</div>
            </div>
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; flex-wrap: wrap; margin-left: auto;">
                <select class="period-select" id="limitSelect" style="height: 36px; padding: 0 12px; min-width: 100px;">
                    <option value="10">10 data</option>
                    <option value="20">20 data</option>
                    <option value="30">30 data</option>
                </select>
                <div style="display: flex; align-items: center; gap: 8px; padding: 6px 8px; border: 1px solid var(--clr-border-light); border-radius: var(--radius-sm); background: var(--clr-bg);">
                    <input type="date" id="startDateFilter" style="height: 32px; border: 1px solid var(--clr-border); border-radius: var(--radius-sm); padding: 0 10px; font-size: 12px; color: var(--clr-text-heading); background: white;">
                    <span style="font-size: 12px; color: var(--clr-text-muted);">sampai</span>
                    <input type="date" id="endDateFilter" style="height: 32px; border: 1px solid var(--clr-border); border-radius: var(--radius-sm); padding: 0 10px; font-size: 12px; color: var(--clr-text-heading); background: white;">
                </div>
                <button type="button" id="applyFilterBtn" class="btn-viewall" style="height: 36px; border: 0; cursor: pointer; padding: 0 14px;">Filter</button>
                <a href="#" id="resetFilterBtn" class="btn-viewall" style="height: 36px; display: inline-flex; align-items: center; gap: 4px; padding: 0 14px;">
                    Lihat Semua
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div style="overflow: auto; max-height: 430px;">
            <table style="width: 100%; min-width: 760px; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--clr-bg); border-top: 1px solid var(--clr-border-light); border-bottom: 1px solid var(--clr-border-light);">
                        <th style="padding: 9px 22px; font-size: 10.5px; font-weight: 500; color: var(--clr-text-muted); text-align: left; text-transform: uppercase; letter-spacing: 0.06em;">Tanggal Skrining</th>
                        <th style="padding: 9px 22px; font-size: 10.5px; font-weight: 500; color: var(--clr-text-muted); text-align: left; text-transform: uppercase; letter-spacing: 0.06em;">Nama Ibu</th>
                        <th style="padding: 9px 22px; font-size: 10.5px; font-weight: 500; color: var(--clr-text-muted); text-align: left; text-transform: uppercase; letter-spacing: 0.06em;">Hasil Skrining</th>
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

@endsection

@push('scripts')
<script>
let monitoringData = [];
let monitoringMessage = 'Belum ada riwayat skrining.';
let chart;

function formatDateLabel(value) {
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return value || '-';
    }
    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
}

function formatTimeLabel(value) {
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return '';
    }
    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
}

function formatFullDateLabel(value) {
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return '-';
    }
    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
}

function updateChart() {
    loadMonitoringData();
}

function buildChart(data) {
    const ctx = document.getElementById('monitoringChart').getContext('2d');
    if (chart) chart.destroy();
    const maxValue = Math.max(...data.values, 0);
    chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Jumlah skrining',
                data: data.values,
                borderColor: '#A3B18A',
                backgroundColor: 'rgba(163,177,138,0.42)',
                borderWidth: 1,
                borderRadius: 6,
                maxBarThickness: 46
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
                    ticks: {
                        font: { size: 11, family: 'DM Sans' },
                        color: '#9CA3AF',
                        precision: 0,
                        stepSize: 1
                    },
                    min: 0,
                    suggestedMax: Math.max(3, maxValue + 1)
                }
            }
        }
    });
}

function buildChartFromFrequency(chartData) {
    if (!chartData || !Array.isArray(chartData.labels) || !Array.isArray(chartData.values)) {
        buildChart({ labels: ['Belum ada'], values: [0] });
        return;
    }

    buildChart({
        labels: chartData.labels.length ? chartData.labels : ['Belum ada'],
        values: chartData.values.length ? chartData.values : [0],
    });
}

function buildTable() {
    const badgeMap = {
        'Tidak Beresiko Depresi': `<span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:500;background:var(--clr-low-bg);color:var(--clr-low-text);">Tidak Beresiko Depresi</span>`,
        'Beresiko Depresi': `<span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:500;background:var(--clr-high-bg);color:var(--clr-high-text);">Beresiko Depresi</span>`,
    };

    const tbody = document.getElementById('riwayatTable');
    if (monitoringData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" style="padding: 18px 22px; color: var(--clr-text-muted); text-align: center;">
                    ${monitoringMessage}
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = monitoringData.map(item => {
        const tanggal = formatDateLabel(item.created_at);
        const waktu = formatTimeLabel(item.created_at);
        const hasil = item.result || 'Tidak Diketahui';
        const motherName = item.mother_username || '-';
        const anonymousId = item.anonymous_id || '-';

        return `
            <tr style="border-bottom: 1px solid var(--clr-border-light); transition: background 0.12s;" onmouseover="this.style.background='var(--clr-bg)'" onmouseout="this.style.background=''">
                <td style="padding: 13px 22px;">
                    <div style="font-size: 13px; font-weight: 500; color: var(--clr-text-heading);">${tanggal}</div>
                    <div style="font-size: 11px; color: var(--clr-text-muted);">${waktu}</div>
                </td>
                <td style="padding: 13px 22px;">
                    <div style="font-size: 13px; font-weight: 500; color: var(--clr-text-heading);">${motherName}</div>
                    <div style="font-size: 11px; color: var(--clr-text-muted);">${anonymousId}</div>
                </td>
                <td style="padding: 13px 22px;">${badgeMap[hasil] || hasil}</td>
                <td style="padding: 13px 22px;">
                    <button onclick="lihatDetail('${tanggal}')" style="display:inline-flex;align-items:center;gap:4px;font-size:12px;color:var(--clr-primary);font-weight:500;padding:4px 10px;border-radius:var(--radius-sm);border:1px solid var(--clr-primary-light);background:var(--clr-primary-light);cursor:pointer;transition:all 0.15s;" onmouseover="this.style.background='var(--clr-primary)';this.style.color='white'" onmouseout="this.style.background='var(--clr-primary-light)';this.style.color='var(--clr-primary)'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        Detail
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

function updateConnectedMotherLabel(mother) {
    const label = document.getElementById('connectedMotherLabel');
    const nameEl = document.getElementById('connectedMotherName');
    const anonymousEl = document.getElementById('connectedMotherAnonymousId');
    const connectedSinceEl = document.getElementById('connectedSince');

    if (!mother) {
        label.textContent = 'Belum ada koneksi ibu aktif';
        if (nameEl) nameEl.textContent = '-';
        if (anonymousEl) anonymousEl.textContent = '-';
        if (connectedSinceEl) connectedSinceEl.textContent = '-';
        return;
    }

    const username = mother.username ? `${mother.username} - ` : '';
    label.textContent = `Laporan untuk ${username}${mother.anonymous_id}`;
    if (nameEl) nameEl.textContent = mother.username || '-';
    if (anonymousEl) anonymousEl.textContent = mother.anonymous_id || '-';
    if (connectedSinceEl) connectedSinceEl.textContent = formatFullDateLabel(mother.connected_at);
}

function updateSummary(entries, latestResult = null) {
    const statusLabel = document.getElementById('statusLabel');
    const statusDelta = document.getElementById('statusDelta');

    if (!entries.length) {
        statusLabel.textContent = 'Tidak Diketahui';
        statusDelta.textContent = 'Stabil 0%';
        updateCurrentResultCard(latestResult?.result || null);
        return;
    }

    const sorted = [...entries].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    const latest = sorted[0];
    statusLabel.textContent = latest.result || 'Tidak Diketahui';
    statusDelta.textContent = computeTrend(sorted);
    updateCurrentResultCard(latestResult?.result || latest.result);
}

function updateCurrentResultCard(result) {
    const box = document.getElementById('currentResultBox');
    const label = document.getElementById('currentResultLabel');
    const percent = document.getElementById('currentResultPercent');
    const description = document.getElementById('currentResultDescription');

    if (result === 'Beresiko Depresi') {
        box.style.background = 'var(--clr-high-bg)';
        label.style.color = 'var(--clr-high-text)';
        percent.style.color = 'var(--clr-high-text)';
        label.textContent = 'Beresiko Depresi';
        percent.textContent = '85%';
        description.textContent = 'Hasil terakhir menunjukkan kondisi beresiko. Dampingi istri dan pertimbangkan bantuan profesional.';
        return;
    }

    if (result === 'Tidak Beresiko Depresi') {
        box.style.background = 'var(--clr-low-bg)';
        label.style.color = 'var(--clr-low-text)';
        percent.style.color = 'var(--clr-low-text)';
        label.textContent = 'Tidak Beresiko Depresi';
        percent.textContent = '25%';
        description.textContent = 'Kondisi istri dalam batas normal. Tetap pantau secara rutin dan berikan dukungan emosional.';
        return;
    }

    box.style.background = 'var(--clr-bg)';
    label.style.color = 'var(--clr-text-muted)';
    percent.style.color = 'var(--clr-text-muted)';
    label.textContent = 'Tidak Diketahui';
    percent.textContent = '0%';
    description.textContent = 'Belum ada hasil skrining yang bisa ditampilkan.';
}

function computeTrend(entries) {
    if (entries.length < 2) {
        return 'Stabil 0%';
    }

    const score = resultScore(entries[0].result);
    const previousScore = resultScore(entries[1].result);
    if (score > previousScore) {
        return 'Meningkat';
    }
    if (score < previousScore) {
        return 'Menurun';
    }
    return 'Stabil';
}

function resultScore(result) {
    if (result === 'Beresiko Depresi') return 2;
    if (result === 'Tidak Beresiko Depresi') return 1;
    return 0;
}

function lihatDetail(tanggal) {
    alert('Detail pemeriksaan: ' + tanggal);
    // Ganti dengan modal atau redirect ke halaman detail
}

function getApiToken() {
    return localStorage.getItem('token');
}

function getMonitoringFilters() {
    return {
        limit: document.getElementById('limitSelect')?.value || '10',
        startDate: document.getElementById('startDateFilter')?.value || '',
        endDate: document.getElementById('endDateFilter')?.value || '',
    };
}

async function loadMonitoringData() {
    const token = getApiToken();
    if (!token) {
        window.location.href = '/login';
        return;
    }

    try {
        const params = new URLSearchParams();
        params.set('chart_period', document.getElementById('periodSelect').value);
        const filters = getMonitoringFilters();
        if (filters.limit) {
            params.set('limit', filters.limit);
        }
        if (filters.startDate) {
            params.set('start_date', filters.startDate);
        }
        if (filters.endDate) {
            params.set('end_date', filters.endDate);
        }

        const url = params.toString()
            ? `/api/father/monitoring?${params.toString()}`
            : '/api/father/monitoring';

        const res = await fetch(url, {
            headers: {
                Authorization: 'Bearer ' + token,
                Accept: 'application/json'
            }
        });

        if (res.status === 401) {
            localStorage.removeItem('token');
            window.location.href = '/login';
            return;
        }

        const response = await res.json();
        updateConnectedMotherLabel(response.mother || null);

        if (!response.status || !Array.isArray(response.data)) {
            monitoringData = [];
            monitoringMessage = response.message || 'Belum ada riwayat skrining untuk koneksi ini.';
            updateSummary(monitoringData, response.latest_result || null);
            buildChartFromFrequency(response.chart || null);
            buildTable();
            return;
        }

        monitoringData = response.data;
        monitoringMessage = 'Belum ada riwayat skrining untuk koneksi ini.';
        updateSummary(monitoringData, response.latest_result || null);
        buildChartFromFrequency(response.chart || null);
        buildTable();
    } catch (error) {
        console.error('Monitoring fetch error:', error);
        monitoringData = [];
        monitoringMessage = 'Gagal memuat riwayat. Periksa token login atau koneksi server.';
        updateSummary(monitoringData, null);
        buildChartFromFrequency(null);
        buildTable();
    }
}

// ── Init ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    buildChartFromFrequency(null);
    buildTable();

    document.getElementById('periodSelect').addEventListener('change', loadMonitoringData);

    document.getElementById('limitSelect').addEventListener('change', loadMonitoringData);
    document.getElementById('applyFilterBtn').addEventListener('click', loadMonitoringData);
    document.getElementById('resetFilterBtn').addEventListener('click', function (event) {
        event.preventDefault();
        document.getElementById('limitSelect').value = '10';
        document.getElementById('startDateFilter').value = '';
        document.getElementById('endDateFilter').value = '';
        loadMonitoringData();
    });

    loadMonitoringData();
});
</script>
@endpush