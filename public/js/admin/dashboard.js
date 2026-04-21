 const token = localStorage.getItem("token");

    if (!token) {
        window.location.href = "/login";
    }

    fetch("/api/dashboard", {
        headers: {
            "Authorization": "Bearer " + token,
            "Accept": "application/json"
        }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById("totalUser").innerText = data.totalUser;
    });

    document.addEventListener("DOMContentLoaded", function () {

    const token = localStorage.getItem("token");

    if (!token) {
        window.location.href = "/login";
        return;
    }

    fetch("/api/screenings", {
        headers: {
            "Authorization": "Bearer " + token,
            "Accept": "application/json"
        }
    })
    .then(res => res.json())
    .then(data => {

        let html = "";

        data.forEach(item => {
            html += `
                <tr>
                    <td>${item.unique_code}</td>
                    <td>${item.created_at}</td>
                    <td>${item.model_version}</td>
                    <td>${item.risk_level}</td>
                    <td>${item.status}</td>
                </tr>
            `;
        });

        document.getElementById("screeningTable").innerHTML = html;
    });

});