{{-- resources/views/landing.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurtura - Temani Perjalanan Emosi Ibu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>

        :root {
            --bg-color: #f6f3ef;
            --primary: #8da47e;
            --primary-hover: #7a916b;
            --text-dark: #2e2e2e;
            --text-muted: #7a7a7a;
            --card-bg: #ffffff;
            --card-tint: #fbfaf8;
            --footer-bg: #3a3a3a;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
            /* Background utama dipindah ke class section biar bisa selang-seling */
        }

        /* --- CLASS WARNA BACKGROUND SELANG-SELING --- */
        .bg-terang { background-color: #fbfaf8; } /* Putih tulang / krem sangat terang */
        .bg-krem { background-color: #f6f3ef; } /* Krem hangat / agak gelap */

        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
            color: var(--text-dark);
        }

        a { text-decoration: none; }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* --- BUTTONS --- */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }
        .btn-primary {
            background-color: var(--primary);
            color: #fff;
        }
        .btn-primary:hover { background-color: var(--primary-hover); }
        
        .btn-outline {
            background-color: transparent;
            color: var(--text-dark);
            border: 1px solid #d1d1d1;
        }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }

        /* --- SECTION PADDING --- */
        section { padding: 80px 0; }
        .section-header {
            text-align: center;
            margin-bottom: 48px;
        }
        .section-header h2 {
            font-size: 32px;
            margin-bottom: 12px;
        }
        .section-header p {
            color: var(--text-muted);
            font-size: 15px;
        }

        /* --- HERO SECTION --- */
        .hero {
            padding: 60px 0 100px;
        }
        .hero .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
        }
        .hero-content { flex: 1; }
        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #eef1ec;
            color: var(--primary);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .hero-tag img {
            max-height: 50px;
            width: auto;
            margin-right: 2px;
        }

        .hero h1 {
            font-size: 48px;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        .hero p {
            color: var(--text-muted);
            font-size: 16px;
            margin-bottom: 32px;
            max-width: 450px;
        }
        .hero-buttons {
            display: flex;
            gap: 16px;
        }
        .hero-image {
            flex: 1;
            position: relative;
            background: #eaddd3;
            height: 300px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-float {
            position: absolute;
            bottom: -20px;
            right: -20px;
            background: #fff;
            padding: 16px 24px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            text-align: center;
        }
        .login-float p { margin-bottom: 12px; font-size: 12px; color: var(--text-muted); }

        /* --- GRID CARDS --- */
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }
        .card {
            background: var(--card-bg);
            padding: 32px 24px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            transition: transform 0.2s;
        }
        .card:hover { transform: translateY(-5px); }
        .icon-box {
            width: 48px; height: 48px;
            background: #eef1ec;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: var(--primary);
        }

        /* --- HIGHLIGHT BOX --- */
        .highlight-box {
            background: #ffffff; /* Dibuat putih agar menonjol di atas bg terang */
            border-radius: 24px;
            padding: 56px 40px;
            text-align: center;
            max-width: 800px;
            margin: 0 auto; /* Margin bawah dihapus karena sudah ada padding dari section */
            border: 1px solid #f0ebe1;
            box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        }
        .highlight-box h3 {
            font-size: 24px;
            line-height: 1.5;
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            margin-bottom: 16px;
        }
        .highlight-box p { font-size: 12px; color: var(--text-muted); }

        /* --- CARA KERJA (STEPS) --- */
        .step-card {
            background: var(--card-bg);
            padding: 32px 24px;
            border-radius: 20px;
            text-align: left;
        }
        .step-num {
            width: 40px; height: 40px;
            background: var(--primary);
            color: #fff;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: 18px;
            margin-bottom: 20px;
        }
        .step-card h4 { font-size: 18px; margin-bottom: 8px; }
        .step-card p { font-size: 13px; color: var(--text-muted); }

        /* --- FITUR UNTUK SEMUA --- */
        .feature-card { text-align: left; }
        .feature-card ul {
            list-style: none;
            margin-top: 20px;
        }
        .feature-card li {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .feature-card li::before {
            content: '•';
            color: var(--primary);
            font-size: 20px;
        }

        /* --- CTA SECTION --- */
        .cta-section {
            text-align: center;
            padding: 100px 0;
        }
        .cta-box {
            background: #fff;
            padding: 24px;
            border-radius: 16px;
            max-width: 600px;
            margin: 32px auto;
            font-size: 14px;
            color: var(--text-muted);
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        }
        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        /* --- FOOTER --- */
        footer {
            background: #c8dcba;
            color: #fff;
            padding: 60px 0 24px;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        .footer-logo { font-size: 20px; font-weight: bold; display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }

        .footer-logo img {
            max-height: 48px;
            width: auto;
            margin-right: 8px;
        }

        .footer-col h5 { font-size: 16px; margin-bottom: 20px; font-family: 'DM Sans', sans-serif; color: #333232}
        .footer-col ul { list-style: none; }
        .footer-col li { margin-bottom: 12px; }
        .footer-col a { color: #a0a0a0; font-size: 14px; transition: color 0.2s; }
        .footer-col a:hover { color: #fff; }
        .footer-bottom {
            text-align: center;
            padding-top: 24px;
            border-top: 4px solid #efdfdf;
            color: #a0a0a0;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <section class="hero bg-terang">
        <div class="container">
            <div class="hero-content">
                <div class="hero-tag">
                    <img src="{{ asset('images/logo_nurtura.png') }}">
                </div>
                <h1>Temani Perjalanan Emosi Ibu, Bersama Nurtura</h1>
                <p>Bantu ibu memahami kondisi emosinya melalui prediksi risiko depresi pascapersalinan dengan dukungan keluarga</p>
                <div class="hero-buttons">
                    <a href="#download-app" class="btn btn-primary">Mulai Skrining &rarr;</a>
                    <a href="#cara-kerja" class="btn btn-outline">Lihat Cara Kerja</a>
                </div>
            </div>
            <div class="hero-image">
                <div style="display:flex; gap:16px;">
                    <div class="icon-box" style="width:64px; height:64px; border-radius:50%;"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg></div>
                    <div class="icon-box" style="width:64px; height:64px; border-radius:50%; background:#fff; border:1px solid #eee;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7a7a7a" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></div>
                </div>
                <div class="login-float">
                    <p>Sudah punya akun?</p>
                    <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 8px 16px; font-size:12px;">Masuk ke Akun</a>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-krem">
        <div class="container">
            <div class="section-header">
                <h2>Memahami Tantangan Ibu</h2>
                <p>Perubahan emosi setelah melahirkan adalah hal yang nyata</p>
            </div>
            <div class="grid-3">
                <div class="card">
                    <div class="icon-box">♡</div>
                    <p style="font-size:13px; color:#7a7a7a;">Banyak ibu mengalami depresi pascapersalinan tanpa disadari</p>
                </div>
                <div class="card">
                    <div class="icon-box">📈</div>
                    <p style="font-size:13px; color:#7a7a7a;">Perubahan emosi sering dianggap hal biasa</p>
                </div>
                <div class="card">
                    <div class="icon-box">👥</div>
                    <p style="font-size:13px; color:#7a7a7a;">Dukungan datang terlambat karena kurangnya informasi</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-terang">
        <div class="container">
            <div class="highlight-box">
                <div class="icon-box" style="margin-bottom:24px; background:#eef1ec;">✨</div>
                <h3>Nurtura membantu ibu mengenali kondisi emosinya lebih awal melalui skrining sederhana dan hasil prediksi risiko yang mudah dipahami.</h3>
                <p>Didukung oleh metode machine learning untuk analisis risiko</p>
            </div>
        </div>
    </section>

    <section id="cara-kerja" class="bg-krem">
        <div class="container">
            <div class="section-header">
                <h2>Cara Kerja Nurtura</h2>
                <p>Tiga langkah sederhana untuk mendukung kesehatan mental ibu</p>
            </div>
            <div class="grid-3">
                <div class="step-card">
                    <div class="step-num">1</div>
                    <h4>Isi Skrining Kondisi Emosional</h4>
                    <p>Jawab pertanyaan sederhana tentang perasaan dan kondisi Anda.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">2</div>
                    <h4>Dapatkan Hasil Risiko</h4>
                    <p>Sistem memberikan hasil: rendah, sedang, atau tinggi.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">3</div>
                    <h4>Pantau & Dapatkan Dukungan</h4>
                    <p>Keluarga dan tenaga kesehatan siap mendampingi Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-terang">
        <div class="container">
            <div class="section-header">
                <h2>Fitur untuk Semua</h2>
                <p>Platform lengkap dengan akses untuk ibu, ayah, dan tenaga kesehatan</p>
            </div>
            <div class="grid-3">
                <div class="card feature-card">
                    <div class="icon-box">📱</div>
                    <h4>Ibu</h4>
                    <p style="font-size:12px; color:#7a7a7a;">Mobile App</p>
                    <ul>
                        <li>Isi kuesioner skrining</li>
                        <li>Lihat hasil risiko & rekomendasi</li>
                        <li>Riwayat dan grafik kondisi</li>
                        <li>Edukasi harian</li>
                    </ul>
                </div>
                <div class="card feature-card">
                    <div class="icon-box">👨</div>
                    <h4>Ayah</h4>
                    <p style="font-size:12px; color:#7a7a7a;">Mobile + Web</p>
                    <ul>
                        <li>Monitoring kondisi istri</li>
                        <li>Notifikasi perubahan</li>
                        <li>Panduan mendampingi istri</li>
                    </ul>
                </div>
                <div class="card feature-card">
                    <div class="icon-box">👨‍⚕️</div>
                    <h4>Admin / Tenaga Kesehatan</h4>
                    <p style="font-size:12px; color:#7a7a7a;">Web Dashboard</p>
                    <ul>
                        <li>Dashboard monitoring</li>
                        <li>Data anonim</li>
                        <li>Laporan & analisis</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="download-app" class="cta-section bg-krem">
        <div class="container">
            <h2>Mulai Perjalanan Sehat untuk Ibu Hari Ini</h2>
            <p style="color:#7a7a7a; margin-top:12px;">Bersama Nurtura, ibu dan keluarga dapat menghadapi perjalanan pascapersalinan dengan lebih tenang</p>
            
            <div class="cta-box">
                <strong>Untuk Ibu:</strong> Skrining dilakukan melalui <strong>aplikasi mobile Nurtura</strong>. Download aplikasi di smartphone Anda untuk mulai mengisi skrining kondisi emosional.
            </div>

            <div class="cta-buttons">
                <a href="#" class="btn btn-primary">Download App (Ibu)</a>
                <a href="{{ route('login') }}" class="btn btn-outline">Akses Dashboard (Ayah/Admin)</a>
            </div>
            <p style="font-size:13px; color:#7a7a7a;">Ayah dan Admin dapat mengakses dashboard melalui web</p>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-logo">
                        <img src="{{ asset('images/logo_nurtura.png') }}">
                    </div>
                    <p style="font-size:13px; color:#333232; max-width:200px;">Platform prediksi risiko depresi pascapersalinan untuk mendukung kesehatan mental ibu</p>
                </div>
                <div class="footer-col">
                    <h5>About</h5>
                    <ul>
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Tim</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h5>Contact</h5>
                    <ul>
                        <li><a href="#">Hubungi Kami</a></li>
                        <li><a href="#">Support</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h5>Privacy Policy</h5>
                    <ul>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; 2026 Nurtura. All rights reserved. Made with love for mothers everywhere.
            </div>
        </div>
    </footer>

</body>
</html>