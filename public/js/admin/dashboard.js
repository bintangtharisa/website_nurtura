document.addEventListener("DOMContentLoaded", function () {
    const token = localStorage.getItem("token");

    if (!token) {
        window.location.href = "/login";
        return;
    }

    fetch("/api/dashboard", {
        headers: {
            "Authorization": "Bearer " + token,
            "Accept": "application/json"
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

    const totalUser = document.getElementById("totalUser");
    if (totalUser) totalUser.innerText = data.totalUser ?? 0;

    const totalPengguna = document.getElementById("totalPengguna");
    if (totalPengguna) totalPengguna.innerText = data.totalPengguna ?? 0;
})
    .catch(err => {
        console.error("fetch error:", err);
    });
});