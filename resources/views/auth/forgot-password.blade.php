{{-- resources/views/auth/forgot-password.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }

        body {
            min-height: 100vh;
            /* Background luar sesuai screenshot (sedikit krem/abu sangat terang) */
            background-color: #f7f6f2; 
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Nunito', sans-serif;
            padding: 20px;
        }

        /* ── CARD UTAMA ── */
        .auth-card {
            background: #ffffff;
            width: 100%;
            max-width: 400px;
            border-radius: 24px;
            padding: 40px 32px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.04);
            text-align: center;
        }

        /* ── ICON ATAS (Hati & Centang) ── */
        .icon-wrapper {
            width: 56px;
            height: 56px;
            background-color: #f2f5f0; /* Hijau sangat pudar */
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .icon-wrapper svg {
            width: 24px;
            height: 24px;
            color: #8da47e; /* Warna hijau sage */
        }

        /* ── TYPOGRAPHY ── */
        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 8px;
        }

        .page-subtitle {
            font-size: 14px;
            color: #666666;
            margin-bottom: 28px;
            line-height: 1.5;
        }

        /* ── ALERTS (Error & Status) ── */
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            text-align: left;
        }
        .alert-error   { background: rgba(220,53,69,0.1); color: #dc3545; }
        .alert-success { background: rgba(141, 164, 126, 0.15); color: #5c704f; }

        /* ── FORM ELEMENTS ── */
        .form-group {
            text-align: left;
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper svg {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: #777777;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 44px; /* Padding kiri lebih besar untuk icon */
            background-color: #eee6d8; /* Warna krem input */
            border: 1px solid transparent;
            border-radius: 12px;
            font-family: inherit;
            font-size: 14px;
            color: #333333;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #8da47e;
            background-color: #fff;
        }

        .form-control::placeholder {
            color: #888888;
        }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex;
            width: 100%;
            justify-content: center;
            align-items: center;
            padding: 14px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-primary {
            background-color: #a4b595; /* Hijau sage */
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #8da47e;
        }

        /* ── LINKS ── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
            color: #8da47e;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .back-link:hover {
            color: #728764;
        }

        .back-link svg {
            width: 16px;
            height: 16px;
        }

        /* ── SUCCESS STATE SPECIFIC ── */
        .info-box {
            background-color: #f8faf6;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            font-size: 14px;
            color: #555555;
            line-height: 1.5;
        }
        
        .email-highlight {
            font-weight: 600;
            color: #333333;
        }

    </style>
</head>
<body>

    <div class="auth-card">

        @if (session('status'))
            {{-- ============================================================ --}}
            {{-- TAMPILAN SUCCESS STATE (EMAIL TERKIRIM)                      --}}
            {{-- ============================================================ --}}
            
            <div class="icon-wrapper">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>

            <h1 class="page-title">Email Terkirim!</h1>
            
            <p class="page-subtitle">
                Kami telah mengirim link reset password ke email Anda.
            </p>

            <div class="info-box">
                Silakan cek inbox email Anda dan klik link untuk reset password. Link berlaku selama 24 jam.
            </div>

            <a href="{{ route('login') }}" class="btn btn-primary">
                Kembali ke Login
            </a>

        @else
            {{-- ============================================================ --}}
            {{-- TAMPILAN FORM AWAL (LUPA PASSWORD)                           --}}
            {{-- ============================================================ --}}

            <div class="icon-wrapper">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            </div>

            <h1 class="page-title">Lupa Password</h1>
            <p class="page-subtitle">Masukkan email Anda untuk reset password</p>

            {{-- Pesan Error Validasi --}}
            @if ($errors->any())
                <div class="alert alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-wrapper">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                        </svg>
                        <input type="email" id="email" name="email" class="form-control" 
                               placeholder="nama@email.com" 
                               value="{{ old('email') }}" 
                               required autocomplete="email" autofocus>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Kirim Link Reset</button>
            </form>

            <a href="{{ route('login') }}" class="back-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali ke Login
            </a>

        @endif

    </div>

</body>
</html>