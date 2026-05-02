document.addEventListener("DOMContentLoaded", function () {
    const token = localStorage.getItem("token");

    if (!token) {
        window.location.href = "/login";
        return;
    }

    fetch("/api/father/dashboard", {
        method: "GET",
        headers: {
            "Authorization": "Bearer " + token,
            "Accept": "application/json"
        }
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

        // =========================
        // USER NAME
        // =========================
        const userName = document.getElementById("userName");
        if (userName) userName.innerText = data.user?.name ?? "Bapak";

        // =========================
        // STATUS RISIKO
        // =========================
        const statusRisiko = document.getElementById("statusRisiko");
        if (statusRisiko) {
            statusRisiko.innerText = data.statusRisiko ?? "Rendah";
        }

        // =========================
        // PERSENTASE RISIKO
        // =========================
        const persentase = document.getElementById("persentaseRisiko");
        if (persentase) {
            persentase.innerText = (data.persentaseRisiko ?? 0) + "%";
        }

        // =========================
        // UPDATE BADGE CLASS (optional styling)
        // =========================
        const badge = document.getElementById("riskBadge");
        if (badge && data.statusRisiko) {
            badge.className = "risk-badge risk-" + data.statusRisiko.toLowerCase();
        }

        // =========================
        // DATA CHART (jika dari API)
        // =========================
        if (data.chart) {
            chart.data.labels = data.chart.labels;
            chart.data.datasets[0].data = data.chart.data;
            chart.update();
        }
    })
    .catch((err) => {
        console.error("fetch error:", err);
    });
});