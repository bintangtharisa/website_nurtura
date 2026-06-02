{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk – Nurtura</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            background-color: #f7f6f2;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'DM Sans', sans-serif;
            padding: 20px;
            color: #333;
        }

        .card-container {
            background: #ffffff;
            width: 100%;
            max-width: 440px;
            border-radius: 24px;
            padding: 40px 32px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.04);
            text-align: center;
        }

        .icon-top {
            width: 56px;
            height: 56px;
            background-color: #f1f5ee;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        .icon-top svg {
            width: 24px;
            height: 24px;
            fill: #9baf8b;
        }

        h1 {
            font-size: 26px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 14px;
            color: #777;
            margin-bottom: 32px;
        }

        .field-group {
            text-align: left;
            margin-bottom: 16px;
        }

        .field-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap svg {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: #666;
        }

        .input-wrap input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            background: #ebdcd0;
            color: #333;
            outline: none;
            transition: box-shadow 0.2s;
        }

        .input-wrap input:focus {
            box-shadow: 0 0 0 2px #9baf8b;
        }

        .input-wrap input::placeholder {
            color: #888;
        }

        .forgot-password {
            display: block;
            text-align: right;
            font-size: 13px;
            color: #9baf8b;
            text-decoration: none;
            margin-top: 8px;
            margin-bottom: 24px;
        }

        .btn-primary {
            display: block;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: #9baf8b;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background: #869977;
        }

        .register-link {
            font-size: 13px;
            color: #777;
            margin: 24px 0;
        }

        .register-link a {
            color: #9baf8b;
            font-weight: 600;
            text-decoration: none;
        }

        .security-note {
            display: flex;
            align-items: center;
            background: #f8faf7;
            padding: 16px;
            border-radius: 12px;
            font-size: 12px;
            color: #666;
            text-align: left;
        }

        .security-note svg {
            width: 20px;
            height: 20px;
            color: #9baf8b;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            text-align: left;
        }

        .alert-error {
            background: #f8d7da;
            color: #842029;
        }

        .alert-success {
            background: #d1e7dd;
            color: #0f5132;
        }

        /* ── BUTTON LOADING SPINNER ── */
        .button-spinner {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid rgba(255,255,255,0.8);
            border-top: 2px solid #ffffff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            vertical-align: middle;
            margin-right: 8px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-box {
            text-align: center;
        }

        .loading-box img {
            width: 90px;
            margin-bottom: 16px;
        }

        .loading-box p {
            font-size: 15px;
            color: #555;
            margin-bottom: 16px;
            font-weight: 500;
        }

        .spinner {
            width: 42px;
            height: 42px;
            border: 4px solid #ddd;
            border-top: 4px solid #9baf8b;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            margin: auto;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>

    <div class="card-container">

        <div class="icon-top">
            <svg viewBox="0 0 24 24">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
        </div>

        <h1>Selamat Datang Kembali</h1>
        <p class="subtitle">Ruang aman untuk kesehatan mental ibu</p>

        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div id="loginError" class="alert alert-error" style="display: none;"></div>

        <form id="loginForm">

            <div class="field-group">
                <label for="email">Email</label>
                <div class="input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                    </svg>

                    <input type="email" id="email" name="email"
                        placeholder="nama@email.com"
                        value="{{ old('email') }}"
                        required autocomplete="email">
                </div>
            </div>

            <div class="field-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>

                    <input type="password" id="password" name="password"
                        placeholder="••••••••"
                        required autocomplete="current-password">
                </div>
            </div>

            <a href="{{ route('password.request') }}" class="forgot-password">
                Lupa password?
            </a>

            <button type="submit" class="btn-primary">Masuk</button>
        </form>

        <div class="register-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar akun baru</a>
        </div>

        <div class="security-note">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            Data Anda aman dan hanya dapat dibagikan dengan persetujuan Anda
        </div>

    </div>

<script src="{{ asset('js/admin/auth.js') }}"></script>
</body>
</html>
