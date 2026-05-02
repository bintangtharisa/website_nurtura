@extends('father.layout')

@section('title', 'Father Dashboard')
@section('page-title', 'Father Dashboard')

@section('content')

<style>
    .dashboard-wrapper {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #333;
    }

    .welcome-card {
        background-color: #ECEFE5;
        border-radius: 20px;
        padding: 30px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }

    .welcome-text {
        max-width: 60%;
    }

    .welcome-heading {
        font-size: 32px;
        font-weight: 800;
        color: #333;
        margin: 0 0 12px 0;
        line-height: 1.2;
    }

    .welcome-status {
        font-size: 16px;
        color: #555;
        margin: 0 0 24px 0;
        font-weight: 500;
    }

    .risk-badge {
        font-weight: 700;
    }
    
    .risk-rendah { color: #9AB084; }
    .risk-sedang { color: #D5B4A1; }
    .risk-tinggi { color: #D9534F; }

    .btn-primary {
        display: inline-block;
        background-color: #A3B18F;
        color: #fff;
        text-decoration: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        transition: background 0.3s;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background-color: #8F9E7B;
    }

    .welcome-illustration {
        position: relative;
    }

    .welcome-illustration img {
        width: 260px;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        display: block;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    .card {
        background: #fff;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.02);
        border: 1px solid #F2F2F2;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
    }

    .card-title {
        font-size: 18px;
        font-weight: 800;
        margin: 0 0 6px 0;
        color: #333;
    }

    .card-subtitle {
        font-size: 13px;
        color: #8898AA;
        margin: 0;
        font-weight: 500;
    }

    select#periodSelect {
        background-color: #F8F9FA;
        border: none;
        padding: 8px 32px 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #444;
        cursor: pointer;
        outline: none;
        appearance: none;
        background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="6" viewBox="0 0 10 6"><path fill="%23666" d="M5 6L0 0h10z"/></svg>');
        background-repeat: no-repeat;
        background-position: right 12px center;
    }

    .chart-wrapper {
        position: relative;
        height: 280px;
        width: 100%;
    }

    .history-card {
        display: flex;
        flex-direction: column;
    }
    
    .history-card h3 {
        font-size: 18px;
        font-weight: 800;
        margin: 0 0 24px 0;
        color: #333;
    }

    .risk-list {
        list-style: none;
        padding: 0;
        margin: 0;
        flex-grow: 1;
    }

    .risk-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 24px;
    }

    .risk-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-top: 6px;
        margin-right: 16px;
    }

    .dot-Tinggi { background-color: #9AB084; }
    .dot-Sedang { background-color: #DABFAC; }
    .dot-Rendah { background-color: #9AB084; }

    .risk-info {
        display: flex;
        flex-direction: column;
    }

    .risk-label {
        font-size: 14px;
        font-weight: 700;
        color: #444;
    }

    .risk-time {
        font-size: 10px;
        font-weight: 700;
        color: #8898AA;
        margin-top: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-full {
        display: block;
        text-align: center;
        background-color: #F8F6F4;
        color: #333;
        text-decoration: none;
        padding: 14px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        margin-top: auto;
        transition: background 0.3s;
    }

    .btn-full:hover {
        background-color: #EBE9E7;
    }
</style>

<div class="dashboard-wrapper">
    <div class="welcome-card">
        <div class="welcome-text">
            <h2 class="welcome-heading">
                Halo, Bapak<br><span id="userName">...</span>
            </h2>

            <p class="welcome-status">
                Status Risiko Istri: 
                <span id="riskBadge" class="risk-badge risk-rendah">
                    <span id="statusRisiko">...</span> 
                    (<span id="persentaseRisiko">0</span>%)
                </span>
            </p>

            <a href="{{ route('father.monitoring') }}" class="btn-primary">
                Cek Detail Monitoring
            </a>
        </div>

        <div class="welcome-illustration">
            <img src="https://placehold.co/260x180/FAD7C3/333333?text=Ilustrasi+Pasangan" 
                 onerror="this.style.display='none'" alt="Ilustrasi Pasangan">
        </div>
    </div>

    <div class="dashboard-grid">

        <div class="card chart-card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Grafik Kondisi Terbaru</h3>
                    <p class="card-subtitle">Tren kesehatan istri 7 hari terakhir</p>
                </div>

                <select id="periodSelect">
                    <option value="mingguan">Mingguan</option>
                    <option value="bulanan">Bulanan</option>
                </select>
            </div>

            <div class="chart-wrapper">
                <canvas id="kondisiChart"></canvas>
            </div>
        </div>

        <div class="card history-card">
            <h3>Riwayat Resiko</h3>

            <ul id="riskHistory" class="risk-list">
            </ul>

            <a href="#" class="btn-full">Lihat Riwayat Lengkap</a>
        </div>

    </div>
</div>

@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const token = localStorage.getItem("token");

    if (!token) {
        window.location.href = "/login";
        return;
    }

    const ctx = document.getElementById('kondisiChart').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                data: [],
                borderColor: '#A3B18F',
                backgroundColor: 'transparent',
                borderWidth: 6,
                fill: false,
                tension: 0.4,
                pointRadius: 0, 
                pointHoverRadius: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            },
            scales: {
                x: {
                    grid: {
                        display: true,
                        drawBorder: false,
                        color: '#F0F4F8',
                        lineWidth: 1
                    },
                    ticks: {
                        color: '#8D9BA8',
                        font: { size: 11, weight: 'bold' },
                        padding: 10
                    }
                },
                y: {
                    display: false, 
                    min: 0
                }
            }
        }
    });

    fetch("/api/father/dashboard", {
        headers: {
            Authorization: "Bearer " + token
        }
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

        document.getElementById("userName").innerText = data.user?.name ?? "Budi";

        document.getElementById("statusRisiko").innerText = data.statusRisiko || "Rendah";
        document.getElementById("persentaseRisiko").innerText = data.persentaseRisiko || "25";

        const badge = document.getElementById("riskBadge");
        badge.className = "risk-badge risk-" + (data.statusRisiko ? data.statusRisiko.toLowerCase() : 'rendah');

        if(data.chart) {
            chart.data.labels = data.chart.labels;
            chart.data.datasets[0].data = data.chart.data;
            chart.update();
        }

        const history = document.getElementById("riskHistory");
        history.innerHTML = "";
        
        const historyData = data.history && data.history.length > 0 ? data.history : [
            { level: 'Tinggi', time: '10:45 AM' },
            { level: 'Sedang', time: '08:20 AM' },
            { level: 'Rendah', time: 'KEMARIN' }
        ];

        historyData.forEach(item => {
            history.innerHTML += `
                <li class="risk-item">
                    <span class="risk-dot dot-${item.level}"></span>
                    <div class="risk-info">
                        <span class="risk-label">Resiko ${item.level}</span>
                        <span class="risk-time">${item.time}</span>
                    </div>
                </li>
            `;
        });

    })
    .catch(err => console.error(err));

    document.getElementById("periodSelect").addEventListener("change", function () {

        fetch(`/api/father/chart?type=${this.value}`, {
            headers: { Authorization: "Bearer " + token }
        })
        .then(res => res.json())
        .then(data => {
            chart.data.labels = data.labels;
            chart.data.datasets[0].data = data.data;
            chart.update();
        });

    });

});
</script>