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
