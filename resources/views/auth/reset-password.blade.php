<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Nurtura</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #F8F5F2; /* Cream White */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .card {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 24px;
        }
        .logo-container img {
            max-width: 150px;
            height: auto;
        }
        h2 {
            text-align: center;
            color: #3A3A3A;
            margin-bottom: 24px;
            font-size: 24px;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #3A3A3A;
            font-size: 14px;
            font-weight: 500;
        }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 10px 40px 10px 12px;
            border: 1px solid #DDBEA9;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus {
            outline: none;
            border-color: #A3B18A;
            box-shadow: 0 0 0 3px rgba(163, 177, 138, 0.2);
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
            background: none;
            border: none;
            padding: 0;
        }

        .toggle-password svg {
            width: 20px;
            height: 20px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        button[type="submit"] {
            width: 100%;
            background-color: #A3B18A;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 10px;
        }
        button[type="submit"]:hover {
            background-color: #3A3A3A;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="logo-container">
            <img src="{{ asset('images/logo_nurtura.png') }}" alt="Nurtura Logo">
        </div>
        
        <h2>Reset Password</h2>

        <form method="POST" action="/api/password/reset">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label>Password Baru</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password')">
                        <svg class="eye-icon" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password_confirmation" id="password_confirmation" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                        <svg class="eye-icon" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
            </div>

            <button type="submit">Reset Password</button>
        </form>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const btn = input.nextElementSibling;
            const eyeIcon = btn.querySelector('svg');
            
            if (input.type === "password") {
                input.type = "text";
                // Mengubah icon menjadi "Mata Dicoret" saat password terlihat
                eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
            } else {
                input.type = "password";
                // Mengubah kembali menjadi "Mata Normal" saat password disembunyikan
                eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            }
        }
    </script>

</body>
</html>