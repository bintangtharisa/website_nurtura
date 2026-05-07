const loginForm = document.getElementById("loginForm");
const registerForm = document.getElementById("registerForm");
const registerError = document.getElementById("registerError");

function showRegisterError(message) {
    if (registerError) {
        registerError.textContent = message;
        registerError.style.display = "block";
    } else {
        alert(message);
    }
}

function hideRegisterError() {
    if (registerError) {
        registerError.textContent = "";
        registerError.style.display = "none";
    }
}

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
                    Accept: "application/json",
                },
                body: JSON.stringify({ email, password }),
            });

            const data = await res.json();

            if (res.ok) {
                localStorage.setItem("token", data.token);
                localStorage.setItem("user", JSON.stringify(data.user));

                const role = data.user.role;
                if (role === "admin") {
                    window.location.href = "/admin/dashboard";
                } else if (role === "father") {
                    window.location.href = "/father/dashboard";
                } else {
                    alert("Role tidak valid");
                }
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
        const password_confirmation = document.getElementById(
            "password_confirmation",
        ).value;
        const role = document.querySelector(
            'input[name="role"]:checked',
        )?.value;
        const connection_code =
            document.getElementById("connection_code")?.value || "";

        hideRegisterError();

        if (!role) {
            showRegisterError("Pilih role terlebih dahulu!");
            return;
        }

        if (password !== password_confirmation) {
            showRegisterError("Password dan konfirmasi password tidak sama!");
            return;
        }

        if (role === "ayah" && connection_code.length !== 6) {
            showRegisterError(
                "Masukkan kode koneksi ibu 6 karakter (huruf atau angka).",
            );
            return;
        }

        try {
            const payload = {
                name,
                email,
                password,
                password_confirmation,
                role,
            };

            if (role === "ayah") {
                payload.connection_code = connection_code;
            }

            const res = await fetch("/api/auth/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify(payload),
            });

            const data = await res.json();

            if (res.ok) {
                window.location.href = "/login";
            } else {
                if (data.errors) {
                    showRegisterError(
                        Object.values(data.errors).flat().join("\n"),
                    );
                } else {
                    showRegisterError(
                        data.message || "Terjadi kesalahan saat registrasi.",
                    );
                }
            }
        } catch (err) {
            console.error("fetch error:", err);
        }
    });
}
