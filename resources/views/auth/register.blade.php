{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar – Nurtura</title>
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
            padding: 40px 20px;
            color: #333;
        }

        .card-container {
            background: #ffffff;
            width: 100%;
            max-width: 480px;
            border-radius: 24px;
            padding: 40px 32px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.04);
            text-align: center;
        }

        /* ── ICON HEART ── */
        .icon-top {
            width: 56px; height: 56px;
            background-color: #f1f5ee;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
        }
        .icon-top svg {
            width: 24px; height: 24px;
            fill: #9baf8b;
        }

        /* ── TYPOGRAPHY ── */
        h1 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .subtitle {
            font-size: 13px;
            color: #777;
            margin-bottom: 32px;
        }

        /* ── ROLE SELECTOR CARDS ── */
        .role-section {
            text-align: left;
            margin-bottom: 24px;
        }
        .section-label {
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin-bottom: 12px;
            display: block;
        }
        .role-cards {
            display: flex;
            gap: 16px;
        }
        .role-card {
            flex: 1;
            border: 1.5px solid #eaeaea;
            border-radius: 16px;
            padding: 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #fff;
        }
        .role-card input[type="radio"] { display: none; }
        
        .role-card.active {
            border-color: #9baf8b;
            background-color: #fdfaf7;
            box-shadow: 0 4px 12px rgba(155, 175, 139, 0.1);
        }
        .role-card svg {
            width: 32px; height: 32px;
            stroke: #d0b8a8;
            stroke-width: 1.5;
            margin-bottom: 12px;
            fill: none;
            transition: stroke 0.2s;
        }
        .role-card.active svg { stroke: #9baf8b; }
        .role-title {
            display: block;
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin-bottom: 4px;
        }
        .role-desc {
            display: block;
            font-size: 11px;
            color: #888;
        }

        /* ── DIVIDER ── */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 24px 0;
            color: #999;
            font-size: 12px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #f0f0f0;
        }
        .divider:not(:empty)::before { margin-right: 1em; }
        .divider:not(:empty)::after { margin-left: 1em; }

        /* ── FORM FIELDS ── */
        .field-group {
            text-align: left;
            margin-bottom: 16px;
        }
        .field-group label {
            display: block;
            font-size: 12px;
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
            width: 16px; height: 16px;
            color: #666;
        }
        .input-wrap input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            background: #ebdcd0;
            color: #333;
            outline: none;
            transition: box-shadow 0.2s;
        }
        .input-wrap input:focus {
            box-shadow: 0 0 0 2px #9baf8b;
        }
        .input-wrap input::placeholder { color: #888; }

        /* ── OTP FIELD (muncul saat role ayah) ── */
        #otp-section {
            display: none;
        }
        #otp-section.visible {
            display: block;
        }
        .otp-container {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        .otp-input {
            width: 44px;
            height: 52px;
            border-radius: 10px;
            border: 1.5px solid #eaeaea;
            background: #ebdcd0;
            font-size: 22px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            text-align: center;
            color: #333;
            outline: none;
            transition: all 0.2s ease;
        }
        .otp-input:focus {
            border-color: #9baf8b;
            box-shadow: 0 0 0 2px rgba(155,175,139,0.2);
        }

        /* ── BUTTON ── */
        .btn-primary {
            display: block;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: #9baf8b;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            margin-top: 24px;
            margin-bottom: 16px;
            transition: background 0.2s;
        }
        .btn-primary:hover { background: #869977; }

        /* ── LINKS ── */
        .login-link {
            font-size: 13px;
            color: #777;
            margin-bottom: 32px;
        }
        .login-link a {
            color: #9baf8b;
            font-weight: 600;
            text-decoration: none;
        }

        /* ── SECURITY NOTE BOX ── */
        .security-box {
            background: #fdfbf9;
            border: 1px solid #f2e9e1;
            border-radius: 12px;
            padding: 16px 20px;
            text-align: left;
        }
        .sec-header {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 13px;
            color: #444;
            margin-bottom: 10px;
        }
        .sec-header svg {
            width: 16px; height: 16px;
            margin-right: 8px;
            color: #d0b8a8;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .sec-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sec-list li {
            font-size: 11px;
            color: #666;
            margin-bottom: 6px;
            position: relative;
            padding-left: 12px;
        }
        .sec-list li:last-child { margin-bottom: 0; }
        .sec-list li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: #a0a0a0;
        }

        /* ── ALERTS ── */
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            text-align: left;
        }
        .alert-error { background: #f8d7da; color: #842029; }

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
    </style>
</head>
<body>

    <div class="card-container">
        
        {{-- Heart Icon --}}
        <div class="icon-top">
            <svg viewBox="0 0 24 24">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
        </div>

        <h1>Buat Akun Baru</h1>
        <p class="subtitle">Pilih peran dan lengkapi data diri Anda</p>

        {{-- Menampilkan Error Validasi --}}
        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div id="registerError" class="alert alert-error" style="display: none;"></div>

        <form id="registerForm">

            {{-- ── ROLE SELECTOR ── --}}
            <div class="role-section">
                <span class="section-label">Pilih Peran</span>
                <div class="role-cards">
                    
                    {{-- Role: Ayah --}}
                    <label class="role-card {{ old('role', 'ayah') === 'ayah' ? 'active' : '' }}" id="card_ayah">
                        <input type="radio" name="role" value="ayah" {{ old('role', 'ayah') === 'ayah' ? 'checked' : '' }}>
                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span class="role-title">Ayah</span>
                        <span class="role-desc">Mendampingi istri</span>
                    </label>

                    {{-- Role: Admin --}}
                    <label class="role-card {{ old('role') === 'admin' ? 'active' : '' }}" id="card_admin">
                        <input type="radio" name="role" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }}>
                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        <span class="role-title">Admin</span>
                        <span class="role-desc">Mengelola sistem</span>
                    </label>

                </div>
            </div>

            <div class="divider">Data Diri</div>

            {{-- ── FORM INPUTS ── --}}
            <div class="field-group">
                <label for="name">Nama Lengkap</label>
                <div class="input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <input type="text" id="name" name="name" placeholder="Nama lengkap Anda" value="{{ old('name') }}" required autocomplete="name">
                </div>
            </div>

            <div class="field-group">
                <label for="email">Email</label>
                <div class="input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                    </svg>
                    <input type="email" id="email" name="email" placeholder="nama@email.com" value="{{ old('email') }}" required autocomplete="email">
                </div>
            </div>

            <div class="field-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required autocomplete="new-password">
                </div>
            </div>

            <div class="field-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <div class="input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required autocomplete="new-password">
                </div>
            </div>

            {{-- ── OTP SECTION (muncul saat role ayah) ── --}}
            <div id="otp-section" class="{{ old('role', 'ayah') === 'ayah' ? 'visible' : '' }}">
    <div class="divider">Kode Koneksi Ibu</div>

    <div class="field-group" style="text-align: center;">

        <label style="text-align: center;">
            Masukkan 6 karakter kode (huruf atau angka) dari Akun Ibu
        </label>

        <div class="otp-container" id="otp-inputs">

            <input type="text" maxlength="1" inputmode="text" pattern="[A-Za-z0-9]*" class="otp-input" placeholder="*">
            <input type="text" maxlength="1" inputmode="text" pattern="[A-Za-z0-9]*" class="otp-input" placeholder="*">
            <input type="text" maxlength="1" inputmode="text" pattern="[A-Za-z0-9]*" class="otp-input" placeholder="*">
            <input type="text" maxlength="1" inputmode="text" pattern="[A-Za-z0-9]*" class="otp-input" placeholder="*">
            <input type="text" maxlength="1" inputmode="text" pattern="[A-Za-z0-9]*" class="otp-input" placeholder="*">
            <input type="text" maxlength="1" inputmode="text" pattern="[A-Za-z0-9]*" class="otp-input" placeholder="*">

        </div>

        <input
            type="hidden"
            name="connection_code"
            id="connection_code"
            value="{{ old('connection_code') }}"
        >

        @error('connection_code')
            <small style="color: red;">
                {{ $message }}
            </small>
        @enderror

    </div>
</div>

            <button type="submit" class="btn-primary">Daftar</button>
        </form>

        <div class="login-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
        </div>

        {{-- ── SECURITY NOTE ── --}}
        <div class="security-box">
            <div class="sec-header">
                <svg viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                Kontrol penuh di tangan Anda
            </div>
            <ul class="sec-list">
                <li>Ibu bisa memutus koneksi kapan saja</li>
                <li>Data tidak dibagikan tanpa izin</li>
                <li>Privasi dan keamanan terjamin</li>
            </ul>
        </div>

    </div>

    <script>
        const radios = document.querySelectorAll('input[type="radio"][name="role"]');
        const cards = {
            ayah: document.getElementById('card_ayah'),
            admin: document.getElementById('card_admin')
        };
        const otpSection = document.getElementById('otp-section');
        const otpInputs = document.querySelectorAll('.otp-input');
        const hiddenInput = document.getElementById('connection_code');

        function updateConnectionCode() {
            let code = '';
            otpInputs.forEach(input => {
                code += input.value;
            });
            hiddenInput.value = code;
        }

        function toggleOtp(role) {
            if (role === 'ayah') {
                otpSection.classList.add('visible');
                otpInputs.forEach(i => i.setAttribute('required', true));
            } else {
                otpSection.classList.remove('visible');
                otpInputs.forEach(i => { i.removeAttribute('required'); i.value = ''; });
                updateConnectionCode();
            }
        }

        radios.forEach(radio => {
            radio.addEventListener('change', () => {
                Object.values(cards).forEach(c => c.classList.remove('active'));
                if (cards[radio.value]) cards[radio.value].classList.add('active');
                toggleOtp(radio.value);
            });
        });

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9a-zA-Z]/g, '').toUpperCase().slice(0, 1);
                updateConnectionCode();
                if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        toggleOtp(document.querySelector('input[name="role"]:checked')?.value);
    </script>
<script src="{{ asset('js/admin/auth.js') }}"></script>
</body>
</html>