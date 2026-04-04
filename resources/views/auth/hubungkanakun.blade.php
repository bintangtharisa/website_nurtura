{{-- resources/views/auth/hubungkan-akun.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubungkan Akun Ibu – Nurtura</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            background-color: #f6f3ef; /* Warna background sedikit lebih terang menyesuaikan gambar */
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: 'DM Sans', sans-serif;
            position: relative;
            padding: 40px 20px;
        }

        /* ── TOP BAR ── */
        .topbar {
            position: absolute;
            top: 32px;
            left: 32px;
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
            font-size: 16px;
        }
        .topbar span {
            font-size: 15px;
            color: #3a3a3a;
            font-weight: 500;
        }

        /* ── HEADER CONTENT ── */
        .header-content {
            text-align: center;
            margin-top: 40px;
            margin-bottom: 30px;
            max-width: 600px;
        }
        .icon-circle {
            width: 64px;
            height: 64px;
            background-color: #eaddd3;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }
        .icon-circle svg {
            width: 28px;
            height: 28px;
            color: #8da47e; /* Warna hijau sage untuk icon hati */
            fill: #8da47e;
        }
        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            color: #2e2e2e;
            margin-bottom: 12px;
            line-height: 1.2;
        }
        .subtitle {
            font-size: 16px;
            color: #7a7a7a;
            line-height: 1.5;
        }

        /* ── MAIN CARD ── */
        .card-outer {
            width: 100%;
            max-width: 760px; /* Lebar card disesuaikan dengan gambar */
            background: #eaddd3;
            border-radius: 24px;
            padding: 16px;
            margin-bottom: 40px;
        }

        .card-inner {
            background: #f1bfab; /* Warna peach sesuai gambar */
            border-radius: 16px;
            padding: 50px 40px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ── OTP INPUTS ── */
        .otp-container {
            display: flex;
            gap: 16px;
            margin-bottom: 30px;
            justify-content: center;
        }
        .otp-input {
            width: 64px;
            height: 80px;
            border-radius: 12px;
            border: none;
            background: #ffffff;
            font-size: 32px;
            font-weight: 700;
            font-family: 'DM Sans', sans-serif;
            text-align: center;
            color: #2e2e2e;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            outline: none;
            transition: box-shadow 0.2s, transform 0.1s;
        }
        .otp-input:focus {
            box-shadow: 0 0 0 3px rgba(141,164,126,0.3);
            transform: translateY(-2px);
        }
        .otp-input::placeholder {
            color: #ccc;
            font-size: 32px;
            transform: translateY(-4px); /* Menggeser titik ke tengah atas sedikit */
        }

        /* ── BUTTON ── */
        .btn-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 40px;
            border: none;
            border-radius: 50px;
            background: #9ab48a; /* Warna tombol hijau sage */
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            min-width: 250px;
        }
        .btn-primary:hover  { background: #87a077; }
        .btn-primary:active { transform: scale(0.98); }
        .btn-primary svg {
            width: 18px; height: 18px;
        }

        /* ── HELP LINK ── */
        .help-link {
            margin-top: 16px;
            font-size: 12px;
            color: #8c7368;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .help-link span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 14px; height: 14px;
            border: 1px solid #8c7368;
            border-radius: 50%;
            font-size: 10px;
        }

        /* ── FOOTER SECTION ── */
        .footer-section {
            text-align: center;
            max-width: 500px;
        }
        .back-login {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 18px;
            font-weight: 600;
            color: #2e2e2e;
            text-decoration: none;
            margin-bottom: 24px;
        }
        .back-login:hover { text-decoration: underline; }
        
        .footer-text {
            font-size: 15px;
            color: #9a9a9a;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <div class="topbar">
        <a href="{{ route('register') }}" aria-label="Kembali">&#8592;</a>
        <span>Hubungkan Akun</span>
    </div>

    <div class="header-content">
        <div class="icon-circle">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
        </div>
        <h1>Masukkan Kode Akun Milik Ibu</h1>
        <p class="subtitle">Masukkan 6 digit kode yang dibagikan dari aplikasi Nurtura<br>milik ibu untuk menyinkronkan perjalanan Anda.</p>
    </div>

    <div class="card-outer">
        <div class="card-inner">
            
            <div class="otp-container" id="otp-inputs">
                <input type="text" maxlength="1" class="otp-input" placeholder="·" autofocus>
                <input type="text" maxlength="1" class="otp-input" placeholder="·">
                <input type="text" maxlength="1" class="otp-input" placeholder="·">
                <input type="text" maxlength="1" class="otp-input" placeholder="·">
                <input type="text" maxlength="1" class="otp-input" placeholder="·">
                <input type="text" maxlength="1" class="otp-input" placeholder="·">
            </div>

            <button class="btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                </svg>
                Hubungkan Akun
            </button>

            <a href="#" class="help-link">Dimana saya bisa menemukan kodenya? <span>?</span></a>

        </div>
    </div>

    <div class="footer-section">
        <a href="{{ route('login') }}" class="back-login">
            &#8592; Kembali ke Login
        </a>
        <p class="footer-text">Menghubungkan akun memungkinkan Anda untuk menerima<br>notifikasi dan mendukung pasangan Anda melalui<br>perjalanannya dengan Nurtura.</p>
    </div>

    <script>
        const inputs = document.querySelectorAll('.otp-input');
        
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                // Jika user mengetik angka, otomatis pindah ke kotak sebelahnya
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                // Jika user tekan backspace dan kotak kosong, mundur ke kotak sebelumnya
                if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
    </script>

</body>
</html>