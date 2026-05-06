@extends('father.layout')

@section('title', 'Beranda Bapak')

@section('content')

{{-- Semua style ada di dashboardayah.css — tidak ada inline style --}}

{{-- ── Welcome Card ── --}}
<div class="welcome-card">
    <div class="welcome-card__text">
        <h2 class="welcome-card__heading">
            Halo, Bapak<br><span id="userName">...</span>
        </h2>
        <p class="welcome-card__status">
            Status Risiko Istri:
            <span id="riskBadge" class="risk-badge risk-badge--rendah">
                <span id="statusRisiko">...</span>
                (<span id="persentaseRisiko">0</span>%)
            </span>
        </p>
        <a href="{{ route('father.monitoring') }}" class="btn btn--primary">
            <i class="fa-solid fa-chart-line fa-sm"></i>
            Cek Detail Monitoring
        </a>
    </div>

    <div class="welcome-card__illustration">
        <img src="https://placehold.co/260x180/EDE0D4/A3B18A?text=Nurtura"
             onerror="this.style.display='none'"
             alt="Ilustrasi">
    </div>
</div>

{{-- ── Dashboard Grid ── --}}
<div class="dashboard-grid">

    {{-- Chart Card --}}
    <div class="card">
        <div class="card__header">
            <div>
                <div class="card__title">Grafik Kondisi Terbaru</div>
                <div class="card__subtitle">Tren kesehatan istri 7 hari terakhir</div>
            </div>
            <select id="periodSelect" class="period-select">
                <option value="mingguan">Mingguan</option>
                <option value="bulanan">Bulanan</option>
            </select>
        </div>
        <div class="card__body">
            <div class="chart-wrapper">
                <canvas id="kondisiChart"></canvas>
            </div>
        </div>
    </div>

    {{-- History Card --}}
    <div class="card" style="display:flex; flex-direction:column;">
        <div class="card__header">
            <div>
                <div class="card__title">Riwayat Risiko</div>
                <div class="card__subtitle">Pemeriksaan terakhir</div>
            </div>
        </div>
        <div class="card__body" style="flex:1; display:flex; flex-direction:column;">
            <ul id="riskHistory" class="risk-list"></ul>
            <a href="{{ route('father.monitoring') }}" class="btn-viewall" style="margin-top:auto;">
                Lihat Riwayat Lengkap
            </a>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const token = localStorage.getItem("token");
    if (!token) { window.location.href = "/login"; return; }

    // ── Chart ────────────────────────────────────────────────────
    const ctx = document.getElementById('kondisiChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                data: [],
                borderColor: '#A3B18A',
                backgroundColor: 'rgba(163,177,138,.10)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#A3B18A',
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { enabled: true } },
            scales: {
                x: {
                    grid: { color: '#F0EDE8', drawBorder: false },
                    ticks: { color: '#9CA3AF', font: { size: 11 }, padding: 8 }
                },
                y: { display: false, min: 0 }
            }
        }
    });

    // ── Fetch ────────────────────────────────────────────────────
    fetch("/api/father/dashboard", {
        headers: { Authorization: "Bearer " + token }
    })
    .then(res => {
        if (res.status === 401) {
            localStorage.removeItem("token");
            window.location.href = "/login";
            return;
        }
        return res.json();
    })
    .then(data => {
        if (!data) return;

        const name = data.user?.name ?? "Budi";
        const id   = data.user?.id   ?? "";
        document.getElementById("userName").textContent = name;
        if (typeof setSidebarUser === 'function') setSidebarUser(name, id);

        const status     = data.statusRisiko    || "Rendah";
        const persentase = data.persentaseRisiko ?? 25;
        document.getElementById("statusRisiko").textContent     = status;
        document.getElementById("persentaseRisiko").textContent  = persentase;
        document.getElementById("riskBadge").className =
            "risk-badge risk-badge--" + status.toLowerCase();

        chart.data.datasets[0].data = data.chart?.data ?? [30, 45, 28, 60, 55, 40, 70];
        if (data.chart?.labels) chart.data.labels = data.chart.labels;
        chart.update();

        const historyData = (data.history?.length > 0) ? data.history : [
            { level: 'Tinggi', time: '10:45 AM' },
            { level: 'Sedang', time: '08:20 AM' },
            { level: 'Rendah', time: 'Kemarin'  }
        ];

        document.getElementById("riskHistory").innerHTML = historyData.map(item => `
            <li class="risk-item">
                <span class="risk-dot dot-${item.level}"></span>
                <div class="risk-info">
                    <span class="risk-label">Risiko ${item.level}</span>
                    <span class="risk-time">${item.time}</span>
                </div>
            </li>
        `).join('');
    })
    .catch(err => console.error("Dashboard fetch error:", err));

    // ── Period select ────────────────────────────────────────────
    document.getElementById("periodSelect").addEventListener("change", function () {
        fetch(`/api/father/chart?type=${this.value}`, {
            headers: { Authorization: "Bearer " + token }
        })
        .then(res => res.json())
        .then(data => {
            chart.data.labels           = data.labels;
            chart.data.datasets[0].data = data.data;
            chart.update();
        })
        .catch(err => console.error("Chart fetch error:", err));
    });

});
</script>
@endpush