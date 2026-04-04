{{-- resources/views/auth/forgot-password.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi – Nurtura</title>
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
            justify-content: center; /* Bikin ke tengah vertikal */
            align-items: center; /* Bikin ke tengah horizontal */
            font-family: 'DM Sans', sans-serif;
            position: relative; /* Ditambahkan agar topbar bisa di pojok absolute */
            padding: 20px;
        }

        /* ── TOP BAR ── */
        .topbar {
            position: absolute; /* Dipisah dari aliran supaya nempel di pojok */
            top: 32px;
            left: 32px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .topbar a {
            width: 32px; height: 32px; /* Disamakan dengan login */
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

        /* ── MAIN BODY ── */
        .page-body {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ── ICON DI ATAS (di luar card) ── */
        .top-icon {
            width: 56px; height: 56px; /* Skala diperkecil biar muat selayar */
            border-radius: 50%;
            background: #e8ddd3;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
        }
        .top-icon svg {
            width: 24px; height: 24px;
            color: #f2b5a0;
        }

        /* ── HEADING (di luar card) ── */
        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 30px; /* Skala diperkecil */
            color: #2e2e2e;
            text-align: center;
            margin-bottom: 24px;
        }

        /* ── CARD ── */
        .card-outer {
            width: 100%;
            max-width: 580px; /* Disamakan dengan form login */
            background: #e8ddd3;
            border-radius: 24px;
            padding: 8px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        }

        .card-inner {
            background: #f2b5a0;
            border-radius: 18px;
            padding: 32px 48px 36px; /* Padding disesuaikan */
            text-align: center;
        }

        .card-desc {
            font-size: 14px; /* Sedikit diperkecil */
            color: #5a5a5a;
            line-height: 1.6;
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
            left: 14px; top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            color: #9a9a9a;
        }
        .input-wrap input {
            width: 100%;
            padding: 12px 16px 12px 40px; /* Input dipendekkan sedikit */
            border: none;
            border-radius: 50px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: #3a3a3a;
            background: #fff;
            outline: none;
            transition: box-shadow 0.2s;
        }
        .input-wrap input:focus { box-shadow: 0 0 0 3px rgba(107,140,89,0.25); }
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
            margin-top: 16px;
            transition: background 0.2s, transform 0.1s;
        }
        .btn-primary:hover  { background: #6a8e5a; }
        .btn-primary:active { transform: scale(0.98); }

        /* ── KEMBALI KE LOGIN ── */
        .back-login {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
            font-size: 14px;
            font-weight: 500;
            color: #3a3a3a;
            text-decoration: none;
        }
        .back-login:hover { color: #7a9e6a; }

        /* ── GAMBAR + QUOTE ── */
        .bottom-section {
            margin-top: 24px;
            text-align: center;
        }
        .bottom-section .plant-img {
            width: 160px;
            height: 120px;
            border-radius: 80px;
            object-fit: cover;
            display: block;
            margin: 0 auto 14px;
            background: linear-gradient(135deg, #3a3a2a 0%, #5a7a3a 50%, #8aaa5a 100%);
        }
        .quote {
            font-size: 13px;
            color: #7a7a7a;
            font-style: italic;
        }

        /* ── ALERTS ── */
        .alert {
            padding: 10px 14px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 13px;
            text-align: left;
        }
        .alert-error   { background: rgba(220,53,69,0.15); color: #842029; }
        .alert-success { background: rgba(107,140,89,0.2);  color: #2d5016; }

        /* ========================================================= */
        /* ── TAMBAHAN CSS KHUSUS UNTUK SUCCESS STATE (TIDAK MERUBAH ASLI) ── */
        /* ========================================================= */
        .card-outer-success {
            max-width: 680px; /* Sedikit lebih lebar agar teks rapi */
        }
        .success-message {
            font-size: 17px;
            line-height: 1.5;
            color: #4a4a4a;
            margin-bottom: 28px;
        }
        .btn-email {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: #9cb38f;
            color: #fff;
            padding: 14px 32px;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 28px;
            transition: background 0.2s;
        }
        .btn-email:hover { background: #8aa37d; }
        .sub-links {
            font-size: 13px;
            color: #7a7a7a;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .resend-btn {
            background: none;
            border: none;
            color: #9cb38f;
            font-weight: 500;
            cursor: pointer;
            font-family: inherit;
            font-size: 13px;
            padding: 0;
        }
        .resend-btn:hover { text-decoration: underline; }
        .back-faded {
            color: #a0a0a0;
            text-decoration: none;
        }
        .plant-pill {
            width: 240px;
            height: 70px;
            border-radius: 50px;
            object-fit: cover;
            margin: 20px auto 12px;
            display: block;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .back-login-lg {
            font-size: 16px;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="topbar">
        <a href="{{ route('login') }}" aria-label="Kembali">&#8592;</a>
        <span>Forgot Password</span>
    </div>

    <div class="page-body">

        @if (session('status'))
            {{-- ============================================================ --}}
            {{-- TAMPILAN SUCCESS STATE (SETELAH KLIK "KIRIM TAUTAN")         --}}
            {{-- ============================================================ --}}
            
            <div class="top-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
            </div>

            <h1 class="page-title" style="font-size: 36px;">Periksa Emailmu</h1>

            <div class="card-outer card-outer-success">
                <div class="card-inner">
                    <p class="success-message">
                        Kami telah mengirimkan tautan pengaturan ulang kata sandi ke<br>
                        email Anda. Klik tautan di pesan tersebut untuk mengatur kata<br>
                        sandi baru.
                    </p>

                    <a href="mailto:" class="btn-email">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        Buka Aplikasi Email
                    </a>

                    <div class="sub-links">
                        <div>
                            Tidak menerima email? 
                            <form method="POST" action="{{ route('password.email') }}" style="display: inline;">
                                @csrf
                                <input type="hidden" name="email" value="{{ old('email') }}">
                                <button type="submit" class="resend-btn">Kirim ulang tautan</button>
                            </form>
                        </div>
                        <a href="{{ route('login') }}" class="back-faded">Kembali ke Halaman Masuk</a>
                    </div>
                </div>
            </div>

            <a href="{{ route('login') }}" class="back-login back-login-lg">
                &#8592; Kembali ke Login
            </a>

            <div class="bottom-section">
                <p class="quote">“Tarik nafas dalam dalam, kami disini bersamamu.”</p>
            </div>

        @else
            {{-- ============================================================ --}}
            {{-- TAMPILAN FORM AWAL (KODEMU YANG ASLI, TIDAK DIUBAH SAMA SEKALI) --}}
            {{-- ============================================================ --}}

            <div class="top-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
            </div>

            <h1 class="page-title">Atur Ulang Kata Sandimu</h1>

            <div class="card-outer">
                <div class="card-inner">

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

                    <p class="card-desc">
                        Masukkan email terdaftar Anda dan kami akan mengirimkan
                        tautan pengaturan ulang kata sandi ke email Anda.
                    </p>

                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf

                        <div class="field-group">
                            <label for="email">Alamat Email</label>
                            <div class="input-wrap">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                </svg>
                                <input type="email" id="email" name="email"
                                       placeholder="nurturasahabatibu22@email.com"
                                       value="{{ old('email') }}"
                                       required autocomplete="email">
                            </div>
                        </div>

                        <button type="submit" class="btn-primary">Kirim Tautan</button>
                    </form>

                </div>
            </div>

            <a href="{{ route('login') }}" class="back-login">
                &#8592; Kembali ke Login
            </a>

            <div class="bottom-section">
                <p class="quote">"Tarik nafas dalam dalam, kami disini bersamamu."</p>
            </div>

        @endif

    </div>

</body>
</html>