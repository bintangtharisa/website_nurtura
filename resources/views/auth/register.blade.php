{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar – Nurtura</title>
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
            position: relative; /* Ditambahkan agar absolute topbar rapi */
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

        /* ── MAIN ── */
        .page-body {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .card-outer {
            width: 100%;
            max-width: 680px; /* Diperlebar dari 580px agar judul sejajar 1 baris */
            background: #e8ddd3;
            border-radius: 24px;
            padding: 8px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        }

        .card-inner {
            background: #f2b5a0;
            border-radius: 18px;
            padding: 32px 48px 36px; /* Disamakan dengan halaman login */
            text-align: center;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 30px; /* Skala disesuaikan */
            color: #2e2e2e;
            margin-bottom: 4px;
            line-height: 1.15;
        }
        .subtitle {
            font-size: 14px;
            color: #5a5a5a;
            margin-bottom: 24px;
        }

        /* ── ROLE SELECTOR ── */
        .role-label {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.12em;
            color: #5a5a5a;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .role-options {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        /* tombol role pakai <label> + hidden radio agar bisa toggle aktif */
        .role-options input[type="radio"] { display: none; }

        .role-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 12px 28px;
            border-radius: 16px;
            background: rgba(255,255,255,0.55);
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.2s, background 0.2s;
            min-width: 110px;
        }
        .role-btn svg {
            width: 20px; height: 20px;
            color: #7a9e6a;
        }
        .role-btn span {
            font-size: 13px;
            font-weight: 600;
            color: #3a3a3a;
        }

        /* state aktif: radio checked → label di-style lewat JS karena CSS sibling selector terbatas */
        .role-btn.active {
            background: #fff;
            border-color: #7a9e6a;
        }

        /* ── FORM FIELDS ── */
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
            padding: 12px 16px 12px 40px; /* Ukuran input disesuaikan */
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
            margin-top: 24px;
            transition: background 0.2s, transform 0.1s;
        }
        .btn-primary:hover  { background: #6a8e5a; }
        .btn-primary:active { transform: scale(0.98); }

        /* ── ALERTS ── */
        .alert {
            padding: 10px 14px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 13px;
            text-align: left;
        }
        .alert-error { background: rgba(220,53,69,0.15); color: #842029; }
    </style>
</head>
<body>

    <div class="topbar">
        <a href="{{ route('login') }}" aria-label="Kembali">&#8592;</a>
        <span>Create Account</span>
    </div>

    <div class="page-body">
        <div class="card-outer">
            <div class="card-inner">

                <h1>Bergabung dengan Nurtura Family</h1>
                <p class="subtitle">Mulailah perjalananmu untuk pulih dan merasa lebih baik lagi</p>

                {{-- ERROR MESSAGES --}}
                @if ($errors->any())
                    <div class="alert alert-error">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                {{-- FORM --}}
                <form action="{{ route('register.post') }}" method="POST" id="registerForm">
                    @csrf

                    {{-- ── PILIH ROLE ── --}}
                    <p class="role-label">Pilih Role Kamu</p>
                    <div class="role-options">

                        {{-- ROLE: AYAH --}}
                        <input type="radio" name="role" id="role_ayah" value="ayah"
                               {{ old('role', 'ayah') === 'ayah' ? 'checked' : '' }}>
                        <label for="role_ayah" class="role-btn {{ old('role', 'ayah') === 'ayah' ? 'active' : '' }}" id="label_ayah">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                            </svg>
                            <span>Ayah</span>
                        </label>

                        {{-- ROLE: ADMIN --}}
                        <input type="radio" name="role" id="role_admin" value="admin"
                               {{ old('role') === 'admin' ? 'checked' : '' }}>
                        <label for="role_admin" class="role-btn {{ old('role') === 'admin' ? 'active' : '' }}" id="label_admin">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="7" width="20" height="14" rx="2"/>
                                <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                                <line x1="12" y1="12" x2="12" y2="16"/>
                                <line x1="10" y1="14" x2="14" y2="14"/>
                            </svg>
                            <span>Admin</span>
                        </label>

                    </div>

                    {{-- ── NAMA LENGKAP ── --}}
                    <div class="field-group">
                        <label for="name">Nama Lengkap</label>
                        <div class="input-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                            </svg>
                            <input type="text" id="name" name="name"
                                   placeholder="Nama Lengkap Kamu"
                                   value="{{ old('name') }}" required autocomplete="name">
                        </div>
                    </div>

                    {{-- ── EMAIL ── --}}
                    <div class="field-group">
                        <label for="email">Alamat Email</label>
                        <div class="input-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                            <input type="email" id="email" name="email"
                                   placeholder="nurturasahabatibu22@email.com"
                                   value="{{ old('email') }}" required autocomplete="email">
                        </div>
                    </div>

                    {{-- ── KATA SANDI ── --}}
                    <div class="field-group">
                        <label for="password">Kata Sandi</label>
                        <div class="input-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <input type="password" id="password" name="password"
                                   placeholder="Buat kata sandi"
                                   required autocomplete="new-password">
                        </div>
                    </div>

                    {{-- ── KONFIRMASI KATA SANDI ── --}}
                    <div class="field-group">
                        <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                        <div class="input-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   placeholder="Ulangi kata sandi"
                                   required autocomplete="new-password">
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">Buat Akun</button>
                </form>

            </div>
        </div>
    </div>

    <script>
        // Highlight role button yang aktif saat diklik
        const radios = document.querySelectorAll('input[type="radio"][name="role"]');
        const labels = { ayah: document.getElementById('label_ayah'), admin: document.getElementById('label_admin') };

        radios.forEach(radio => {
            radio.addEventListener('change', () => {
                // reset semua
                Object.values(labels).forEach(l => l.classList.remove('active'));
                // aktifkan yang dipilih
                labels[radio.value]?.classList.add('active');
            });
        });
    </script>

</body>
</html>