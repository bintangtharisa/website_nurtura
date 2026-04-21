console.log("auth.js loaded");

const loginForm = document.getElementById("loginForm");
const registerForm = document.getElementById("registerForm");

if (loginForm) {
    loginForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        try {
            const res = await fetch("/api/auth/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ email, password })
            });

            const data = await res.json();
            console.log("response:", res.status, data);

            if (res.ok) {
                localStorage.setItem("token", data.token);
                localStorage.setItem("user", JSON.stringify(data.user));
                window.location.href = "/admin/dashboard";
            } else {
                alert(data.message);
            }
        } catch (err) {
            console.error("fetch error:", err);
        }
    });
}

if (registerForm) {
    registerForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        const name = document.getElementById("name").value;
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;
        const password_confirmation = document.getElementById("password_confirmation").value;

        const role = document.querySelector('input[name="role"]:checked')?.value;

        if (!role) {
            alert("Pilih role terlebih dahulu!");
            return;
        }

        if (password !== password_confirmation) {
            alert("Password dan konfirmasi password tidak sama!");
            return;
        }

        try {
            const res = await fetch("/api/auth/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    name,
                    email,
                    password,
                    password_confirmation,
                    role
                })
            });

            const data = await res.json();
            console.log("response:", res.status, data);

            if (res.ok) {
                window.location.href = "/login";
            } else {
                if (data.errors) {
                    alert(Object.values(data.errors).flat().join("\n"));
                } else {
                    alert(data.message);
                }
            }
        } catch (err) {
            console.error("fetch error:", err);
        }
    });
}