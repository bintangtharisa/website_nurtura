document.addEventListener("DOMContentLoaded", function () {
    const token = localStorage.getItem("token");

    if (!token) {
        window.location.href = "/login";
        return;
    }

    fetch("/api/admin/dashboard", {
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

        const totalUser = document.getElementById("totalUser");
        if (totalUser) totalUser.innerText = data.totalUser ?? 0;

        const totalPengguna = document.getElementById("totalPengguna");
        if (totalPengguna) totalPengguna.innerText = data.totalPengguna ?? 0;
    })
    .catch((err) => {
        console.error("fetch error:", err);
    });
});