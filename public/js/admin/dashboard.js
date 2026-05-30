document.addEventListener("DOMContentLoaded", function () {
    const token = localStorage.getItem("token");

    if (!token) {
        window.location.href = "/login";
        return;
    }

    fetch("/api/admin/dashboard", {
        method: "GET",
        headers: {
            Authorization: "Bearer " + token,
            Accept: "application/json",
        },
    })
        .then(async (res) => {
            if (res.status === 401 || res.status === 403) {
                localStorage.removeItem("token");
                window.location.href = "/login";
                return null;
            }

            if (!res.ok) {
                console.error("Server error:", res.status);
                return null;
            }

            return await res.json();
        })
        .then((data) => {
            if (!data || !data.status) return;

            const totalUser = document.getElementById("totalUser");
            if (totalUser) totalUser.innerText = data.totalUser ?? 0;

            const totalPengguna = document.getElementById("totalPengguna");
            if (totalPengguna)
                totalPengguna.innerText = data.totalPengguna ?? 0;

            renderPredictionTrend(data.predictionTrends);

            const screeningTable = document.getElementById("screeningTable");
            if (!screeningTable) return;

            const rows = (data.recentScreenings || []).slice(0, 5);
            if (rows.length === 0) {
                screeningTable.innerHTML =
                    '<tr><td colspan="3">Tidak ada riwayat terbaru.</td></tr>';
                return;
            }

            screeningTable.innerHTML = rows
                .map(
                    (item) => `
            <tr>
                <td><span class="td-code">${item.anonymous_id || "-"}</span></td>
                <td><span class="td-muted">${formatDate(item.created_at)}</span></td>
                <td>${renderBadge(item.risk_category)}</td>
            </tr>
        `,
                )
                .join("");
        })
        .catch((err) => {
            console.error("fetch error:", err);
        });
});

function renderPredictionTrend(trends) {
    const chart = document.getElementById("predictionTrendChart");
    if (!chart) return;

    const labels = trends?.labels || ["Q1", "Q2", "Q3", "Q4"];
    const series = trends?.series || {};
    const yearLabel = document.getElementById("predictionTrendYear");
    const subtitle = document.getElementById("predictionTrendSubtitle");

    if (yearLabel && trends?.year) yearLabel.innerText = `Tahun ${trends.year}`;
    if (subtitle && trends?.year) {
        subtitle.innerText = `Tren hasil prediksi ibu per kuartal pada ${trends.year}`;
    }

    const datasets = [
        {
            key: "high",
            label: "Beresiko",
            color: "#C62828",
            values: normalizeQuarterValues(series.high),
        },
        {
            key: "low",
            label: "Tidak Beresiko",
            color: "#2E7D32",
            values: normalizeQuarterValues(series.low),
        },
        {
            key: "unknown",
            label: "Tidak Diketahui",
            color: "#A07000",
            values: normalizeQuarterValues(series.unknown),
        },
    ];

    const maxValue = Math.max(
        1,
        ...datasets.flatMap((dataset) => dataset.values),
    );
    const width = 720;
    const height = 240;
    const padding = { top: 18, right: 24, bottom: 42, left: 42 };
    const plotWidth = width - padding.left - padding.right;
    const plotHeight = height - padding.top - padding.bottom;
    const stepX = plotWidth / Math.max(labels.length - 1, 1);
    const yTicks = buildYTicks(maxValue);

    const gridLines = yTicks
        .map((tick) => {
            const y = padding.top + plotHeight - (tick / maxValue) * plotHeight;
            return `
                <line class="trend-chart__grid" x1="${padding.left}" y1="${y}" x2="${width - padding.right}" y2="${y}" />
                <text class="trend-chart__tick" x="${padding.left - 12}" y="${y + 4}" text-anchor="end">${tick}</text>
            `;
        })
        .join("");

    const labelNodes = labels
        .map((label, index) => {
            const x = padding.left + index * stepX;
            return `<text class="trend-chart__label" x="${x}" y="${height - 14}" text-anchor="middle">${label}</text>`;
        })
        .join("");

    const lines = datasets
        .map((dataset) => {
            const points = dataset.values.map((value, index) => {
                const x = padding.left + index * stepX;
                const y =
                    padding.top + plotHeight - (value / maxValue) * plotHeight;
                return { x, y, value, label: labels[index] };
            });
            const pointString = points
                .map((point) => `${point.x},${point.y}`)
                .join(" ");
            const circles = points
                .map(
                    (point) => `
                        <g>
                            <circle class="trend-chart__point" cx="${point.x}" cy="${point.y}" r="4.5" fill="${dataset.color}">
                                <title>${dataset.label} ${point.label}: ${point.value}</title>
                            </circle>
                            <text class="trend-chart__value" x="${point.x}" y="${point.y - 10}" text-anchor="middle">${point.value}</text>
                        </g>
                    `,
                )
                .join("");

            return `
                <polyline class="trend-chart__line" points="${pointString}" stroke="${dataset.color}" />
                ${circles}
            `;
        })
        .join("");

    chart.innerHTML = `
        <svg class="trend-chart__svg" viewBox="0 0 ${width} ${height}" role="img" aria-label="Prediction Trends Q1 sampai Q4">
            ${gridLines}
            <line class="trend-chart__axis" x1="${padding.left}" y1="${height - padding.bottom}" x2="${width - padding.right}" y2="${height - padding.bottom}" />
            ${labelNodes}
            ${lines}
        </svg>
    `;
}

function normalizeQuarterValues(values) {
    return Array.from({ length: 4 }, (_, index) => Number(values?.[index] || 0));
}

function buildYTicks(maxValue) {
    const middle = Math.ceil(maxValue / 2);
    return Array.from(new Set([0, middle, maxValue]));
}

function formatDate(dateString) {
    if (!dateString) return "-";
    const parsed = new Date(dateString);
    if (Number.isNaN(parsed.getTime())) return dateString;
    return parsed.toLocaleDateString("id-ID", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
}

function renderBadge(result) {
    const normalized = String(result || "").toLowerCase();
    if (
        normalized.includes("ya") ||
        normalized.includes("beresiko") ||
        normalized.includes("tinggi")
    ) {
        return '<span class="badge badge--high">Beresiko Depresi</span>';
    }
    if (normalized.includes("tidak") || normalized.includes("rendah")) {
        return '<span class="badge badge--low">Tidak Beresiko Depresi</span>';
    }
    return '<span class="badge">Tidak Diketahui</span>';
}
