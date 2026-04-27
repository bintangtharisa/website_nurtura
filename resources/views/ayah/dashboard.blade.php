@extends('ayah.layout')

@section('title', 'Beranda Bapak')
@section('page-title', 'Beranda Bapak')

@section('content')

    {{-- ===== HERO WELCOME CARD ===== --}}
    <div class="welcome-card">
        <div class="welcome-text">
            <h2 class="welcome-heading">Halo, Bapak {{ Auth::user()->name }}</h2>
            <p class="welcome-status">
                Status Risiko Istri:
                <span class="risk-badge risk-{{ strtolower($statusRisiko ?? 'rendah') }}">
                    {{ $statusRisiko ?? 'Rendah' }} ({{ $persentaseRisiko ?? '25' }}%)
                </span>
            </p>
            <a href="{{ route('ayah.monitoring') }}" class="btn-primary">
                <i class="fa-solid fa-circle-dot"></i>
                Cek Detail Monitoring
            </a>
        </div>
        <div class="welcome-illustration">
            {{-- Ganti dengan ilustrasi pasangan sesuai desain --}}
            <img src="{{ asset('images/ilustrasi-pasangan.png') }}" alt="Ilustrasi Pasangan" onerror="this.style.display='none'">
            <div class="illustration-placeholder" aria-hidden="true">
                <i class="fa-solid fa-heart-circle-check"></i>
            </div>
        </div>
    </div>

    {{-- ===== BOTTOM GRID ===== --}}
    <div class="dashboard-grid">

        {{-- Grafik Kondisi Terbaru --}}
        <div class="card chart-card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Grafik Kondisi Terbaru</h3>
                    <p class="card-subtitle">Tren kesehatan istri 7 hari terakhir</p>
                </div>
                <div class="period-select-wrapper">
                    <select class="period-select" id="periodSelect">
                        <option value="mingguan">Mingguan</option>
                        <option value="bulanan">Bulanan</option>
                    </select>
                    <i class="fa-solid fa-chevron-down select-arrow"></i>
                </div>
            </div>
            <div class="chart-wrapper">
                <canvas id="kondisiChart"></canvas>
            </div>
        </div>

        {{-- Riwayat Risiko --}}
        <div class="card history-card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Risiko</h3>
            </div>

            <ul class="risk-list">
                <li class="risk-item">
                    <span class="risk-dot dot-high"></span>
                    <div class="risk-info">
                        <span class="risk-label">Resiko Tinggi</span>
                        <span class="risk-time">10:45 AM</span>
                    </div>
                </li>
                <li class="risk-item">
                    <span class="risk-dot dot-medium"></span>
                    <div class="risk-info">
                        <span class="risk-label">Resiko Sedang</span>
                        <span class="risk-time">08:20 AM</span>
                    </div>
                </li>
                <li class="risk-item">
                    <span class="risk-dot dot-low"></span>
                    <div class="risk-info">
                        <span class="risk-label">Resiko Rendah</span>
                        <span class="risk-time">Kemarin</span>
                    </div>
                </li>
            </ul>

            <a href="{{ route('ayah.monitoring') }}" class="btn-outline-full">
                Lihat Riwayat Lengkap
            </a>
        </div>

    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ===== CHART SETUP =====
    const ctx = document.getElementById('kondisiChart').getContext('2d');

    const labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
    const dataMingguan = [30, 45, 38, 60, 72, 65, 55];
    const dataBulanan  = [40, 55, 48, 70, 62, 75, 68];

    const gradient = ctx.createLinearGradient(0, 0, 0, 200);
    gradient.addColorStop(0, 'rgba(130, 160, 100, 0.25)');
    gradient.addColorStop(1, 'rgba(130, 160, 100, 0.00)');

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                data: dataMingguan,
                borderColor: '#7a9a5a',
                borderWidth: 2.5,
                backgroundColor: gradient,
                fill: true,
                tension: 0.45,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: '#7a9a5a',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: {
                backgroundColor: '#fff',
                titleColor: '#3d3d3d',
                bodyColor: '#6b7280',
                borderColor: '#e5e7eb',
                borderWidth: 1,
                padding: 10,
                cornerRadius: 8,
            }},
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { color: '#9ca3af', font: { size: 12, family: 'DM Sans' } }
                },
                y: {
                    display: false,
                    grid: { display: false },
                }
            }
        }
    });

    // Toggle mingguan / bulanan
    document.getElementById('periodSelect').addEventListener('change', function () {
        chart.data.datasets[0].data = this.value === 'bulanan' ? dataBulanan : dataMingguan;
        chart.data.labels = this.value === 'bulanan'
            ? ['Jan','Feb','Mar','Apr','Mei','Jun','Jul']
            : ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
        chart.update();
    });
</script>
@endpush