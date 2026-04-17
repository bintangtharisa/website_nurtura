{{-- resources/views/auth/hubungkan-akun.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubungkan Akun Ibu – Nurtura</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            background-color: #f6f3ef; /* Warna background utama */
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
            width: 36px; height: 36px;
            border-radius: 50%;
            background: #ffffff; 
            color: #3a3a3a;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none;
            font-size: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .topbar a:hover {
            transform: scale(1.05);
        }
        .topbar span {
            font-size: 15px;
            color: #3a3a3a;
            font-weight: 500;
        }

        /* ── HEADER CONTENT ── */
        .header-content {
            text-align: center;
            margin-top: 60px;
            margin-bottom: 30px;
            max-width: 600px;
        }
        .icon-circle {
            width: 56px;
            height: 56px;
            background-color: #eef1ec; /* Hijau sangat muda */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .icon-circle svg {
            width: 24px;
            height: 24px;
            color: #8da47e; 
            fill: #8da47e;
        }
        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #2e2e2e;
            margin-bottom: 12px;
            line-height: 1.2;
        }
        .subtitle {
            font-size: 15px;
            color: #7a7a7a;
            line-height: 1.5;
        }

        /* ── MAIN CARD ── */
        .card-inner {
            width: 100%;
            max-width: 600px;
            background: #fbfaf8; /* Warna off-white terang */
            border-radius: 24px;
            padding: 40px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        }

        /* ── OTP INPUTS ── */
        .otp-container {
            display: flex;
            gap: 12px;
            margin-bottom: 30px;
            justify-content: center;
        }
        .otp-input {
            width: 60px;
            height: 75px;
            border-radius: 12px;
            border: 1px solid transparent;
            background: #ffffff;
            font-size: 32px;
            font-weight: 500; 
            font-family: 'DM Sans', sans-serif;
            text-align: center;
            color: #2e2e2e;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            outline: none;
            transition: all 0.2s ease;
        }
        .otp-input:focus {
            border: 2px solid #8da47e; /* Efek fokus hijau sage */
            background: #ffffff;
            transform: translateY(-2px);
        }
        .otp-input::placeholder {
            color: #ddd;
            font-size: 32px;
        }

        /* ── BUTTON ── */
        .btn-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 40px;
            border: none;
            border-radius: 12px; 
            background: #bdcbb4; 
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%; 
            max-width: 400px;
        }
        .btn-primary:hover  { 
            background: #8da47e; 
        }
        .btn-primary:active {
            transform: scale(0.98);
        }
        .btn-primary svg {
            width: 16px; height: 16px;
        }

        /* ── HELP LINK ── */
        .help-link {
            margin-top: 20px;
            font-size: 13px;
            color: #5a4b44;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s;
        }
        .help-link:hover {
            color: #3a2e2a;
        }
        .help-link span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 16px; height: 16px;
            border: 1px solid #5a4b44;
            border-radius: 50%;
            font-size: 11px;
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
            font-size: 16px;
            font-weight: 600;
            color: #2e2e2e;
            text-decoration: none;
            margin-bottom: 24px;
        }
        .back-login:hover { text-decoration: underline; }
        
        .footer-text {
            font-size: 13px;
            color: #9a9a9a;
            line-height: 1.6;
        }
    </style>
</head>
<body>

    <div class="topbar">
        <a href="{{ route('login') }}" aria-label="Kembali">&#8592;</a>
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

    <div class="card-inner">
        <form action="#" method="POST" style="display: flex; flex-direction: column; align-items: center; width: 100%;">
            @csrf
            
            <div class="otp-container" id="otp-inputs">
                <input type="text" name="otp[]" maxlength="1" class="otp-input" placeholder="·" autofocus required>
                <input type="text" name="otp[]" maxlength="1" class="otp-input" placeholder="·" required>
                <input type="text" name="otp[]" maxlength="1" class="otp-input" placeholder="·" required>
                <input type="text" name="otp[]" maxlength="1" class="otp-input" placeholder="·" required>
                <input type="text" name="otp[]" maxlength="1" class="otp-input" placeholder="·" required>
                <input type="text" name="otp[]" maxlength="1" class="otp-input" placeholder="·" required>
            </div>

            <button type="submit" class="btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
                Hubungkan Akun
            </button>
        </form>

        <a href="#" class="help-link">Dimana saya bisa menemukan kodenya? <span>?</span></a>
    </div>

    <div class="footer-section">
        <a href="{{ route('login') }}" class="back-login">
            &#8592; Kembali ke Login
        </a>
        <p class="footer-text">Menghubungkan akun memungkinkan Anda untuk menerima notifikasi<br>dan mendukung pasangan Anda melalui perjalanannya dengan Nurtura.</p>
    </div>

    <script>
        const inputs = document.querySelectorAll('.otp-input');
        
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                // Memastikan yang diinput hanya angka (opsional tapi bagus untuk UX)
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                
                // Kalau ada isinya, pindah ke input selanjutnya
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                // Kalau pencet backspace dan kotaknya kosong, mundur ke kotak sebelumnya
                if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
    </script>

</body>
</html>