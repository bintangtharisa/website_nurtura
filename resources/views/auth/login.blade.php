{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Nurtura</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            background-color: #f0ebe3;
            display: flex;
            flex-direction: column;
            justify-content: center; 
            align-items: center; 
            font-family: 'DM Sans', sans-serif;
            position: relative; /* Ditambahkan agar absolute topbar rapi */
            padding: 20px;
        }

        /* ── TOP BAR ── */
        .topbar {
            position: absolute; /* Dipisah dari aliran supaya bisa ke pojok */
            top: 32px; /* Jarak dari atas layar */
            left: 32px; /* Jarak dari kiri layar (makin kecil makin mepet kiri) */
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .topbar a {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: #3a3a3a;
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none;
            font-size: 14px;
        }
        .topbar span {
            font-size: 14px;
            color: #3a3a3a;
            font-weight: 500;
        }

        /* ── MAIN CARD AREA ── */
        .page-body {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .card-outer {
            width: 100%;
            max-width: 580px;
            background: #e8ddd3;
            border-radius: 24px;
            padding: 8px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        }

        .card-inner {
            background: #f2b5a0;
            border-radius: 18px;
            padding: 32px 48px 36px;
            text-align: center;
        }

        /* ── ICON ── */
        .icon-circle {
            width: 56px; height: 56px;
            border-radius: 50%;
            background: rgba(255,255,255,0.45);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
        }
        .icon-circle svg {
            width: 24px; height: 24px;
        }

        /* ── HEADING ── */
        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 30px;
            color: #2e2e2e;
            margin-bottom: 4px;
            line-height: 1.15;
        }
        .subtitle {
            font-size: 14px;
            color: #5a5a5a;
            margin-bottom: 24px;
        }

        /* ── FORM ── */
        .field-group {
            text-align: left;
            margin-bottom: 14px;
        }
        .field-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #3a3a3a;
            margin-bottom: 6px;
        }
        .input-wrap {
            position: relative;
        }
        .input-wrap svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            color: #9a9a9a;
        }
        .input-wrap input {
            width: 100%;
            padding: 12px 16px 12px 40px;
            border: none;
            border-radius: 50px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: #3a3a3a;
            background: #fff;
            outline: none;
            transition: box-shadow 0.2s;
        }
        .input-wrap input:focus {
            box-shadow: 0 0 0 3px rgba(107,140,89,0.25);
        }
        .input-wrap input::placeholder { color: #b0b0b0; }

        /* ── BUTTON ── */
        .btn-primary {
            display: block;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 50px;
            background: #7a9e6a;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            margin-top: 24px;
            transition: background 0.2s, transform 0.1s;
        }
        .btn-primary:hover  { background: #6a8e5a; }
        .btn-primary:active { transform: scale(0.98); }

        /* ── FOOTER LINK ── */
        .form-footer {
            margin-top: 16px;
            font-size: 13px;
            color: #5a5a5a;
        }
        .form-footer a {
            color: #7a9e6a;
            font-weight: 500;
            text-decoration: none;
        }
        .form-footer a:hover { text-decoration: underline; }

        /* ── ERROR / SUCCESS ALERTS ── */
        .alert {
            padding: 10px 14px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 13px;
            text-align: left;
        }
        .alert-error   { background: rgba(220,53,69,0.15);  color: #842029; }
        .alert-success { background: rgba(107,140,89,0.2);  color: #2d5016; }
    </style>
</head>
<body>

    {{-- TOP BAR --}}
    <div class="topbar">
        <a href="{{ url('/') }}" aria-label="Kembali">&#8592;</a>
        <span>Login</span>
    </div>

    <div class="page-body">
        <div class="card-outer">
            <div class="card-inner">

                {{-- ICON --}}
                <div class="icon-circle">
                    {{-- Ikon daun / tanaman --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke="#7a9e6a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22V12"/>
                        <path d="M12 12C12 12 7 10 5 5c4 0 7 2 7 7z"/>
                        <path d="M12 12c0 0 5-2 7-7-4 0-7 2-7 7z"/>
                    </svg>
                </div>

                <h1>Selamat Datang Kembali</h1>
                <p class="subtitle">Senang melihatmu lagi hari ini</p>

                {{-- ALERT ERROR (dari Laravel session) --}}
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

                {{-- FORM --}}
                {{-- action diarahkan ke route login, method POST --}}
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf {{-- wajib ada di setiap form Laravel --}}

                    <div class="field-group">
                        <label for="email">Alamat Email</label>
                        <div class="input-wrap">
                            {{-- ikon amplop --}}
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="nurturasahabatibu22@email.com"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                            >
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="password">Kata Sandi</label>
                        <div class="input-wrap">
                            {{-- ikon kunci --}}
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="nurtura22"
                                required
                                autocomplete="current-password"
                            >
                        </div>
                        <div style="text-align: right; margin-top: 8px;">
                            <a href="{{ route('password.request') }}" 
                               style="font-size: 13px; color: #7a9e6a; text-decoration: none; font-weight: 500;">
                               Lupa Kata Sandi?
                            </a>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">Login</button>
                </form>

                <p class="form-footer">
                    Baru di komunitas kami? <a href="{{ route('register') }}">Mari bergabung</a>
                </p>

            </div>
        </div>
    </div>

</body>
</html>