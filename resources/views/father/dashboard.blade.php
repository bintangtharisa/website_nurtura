@extends('father.layout')

@section('title', 'Beranda Bapak')

@section('content')

{{-- Semua style ada di dashboardayah.css — tidak ada inline style --}}

{{-- ── Welcome Card ── --}}
<div class="welcome-card">
    <div class="welcome-card__text">
        <h2 class="welcome-card__heading">
            Welcome, Bapak <span id="userName">...</span>
        </h2>
        <p class="welcome-card__status">
            Status terkini risiko istri anda:
            <span id="riskBadge" class="risk-badge risk-badge--rendah">
                <span id="statusRisiko">...</span>
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
                <div class="card__title">Frekuensi Skrining Hari Ini</div>
                <div class="card__subtitle">Seberapa sering ibu melakukan skrining hari ini</div>
            </div>
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
    if (!token) {
        window.location.href = "/login";
        return;
    }

    const ctx = document.getElementById('kondisiChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['00-03', '03-06', '06-09', '09-12', '12-15', '15-18', '18-21', '21-24'],
            datasets: [{
                label: 'Jumlah skrining',
                data: [],
                borderColor: '#A3B18A',
                backgroundColor: 'rgba(163,177,138,.42)',
                borderWidth: 1,
                borderRadius: 6,
                maxBarThickness: 42
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
                y: {
                    min: 0,
                    ticks: { precision: 0, stepSize: 1, color: '#9CA3AF', font: { size: 11 } },
                    grid: { color: '#F0EDE8', drawBorder: false }
                }
            }
        }
    });

    fetch("/api/father/dashboard", {
        headers: {
            Authorization: "Bearer " + token,
            Accept: "application/json"
        }
    })
    .then(res => {
        if (res.status === 401) {
            localStorage.removeItem("token");
            window.location.href = "/login";
            return null;
        }
        return res.json();
    })
    .then(data => {
        if (!data) return;

        const name = data.user?.username || data.user?.name || "Bapak";
        const id = data.user?.id || "";
        document.getElementById("userName").textContent = name;
        if (typeof setSidebarUser === 'function') setSidebarUser(name, id);

        const latestStatus = data.statusRisiko || "Belum Ada Data";
        document.getElementById("statusRisiko").textContent = latestStatus;
        document.getElementById("riskBadge").className = latestStatus === "Beresiko Depresi"
            ? "risk-badge risk-badge--tinggi"
            : "risk-badge risk-badge--rendah";

        chart.data.labels = data.chart?.labels || chart.data.labels;
        chart.data.datasets[0].data = data.chart?.data || [0, 0, 0, 0, 0, 0, 0, 0];
        chart.update();

        const historyData = Array.isArray(data.history) ? data.history : [];
        document.getElementById("riskHistory").innerHTML = historyData.length
            ? historyData.map(item => `
                <li class="risk-item">
                    <span class="risk-dot ${item.result === 'Beresiko Depresi' ? 'dot-Tinggi' : 'dot-Rendah'}"></span>
                    <div class="risk-info">
                        <span class="risk-label">${item.result || 'Belum Ada Data'}</span>
                        <span class="risk-time">${item.time || '-'}</span>
                    </div>
                </li>
            `).join('')
            : '<li class="risk-item"><div class="risk-info"><span class="risk-label">Belum ada riwayat skrining</span></div></li>';
    })
    .catch(err => console.error("Dashboard fetch error:", err));
});
</script>
@endpush
