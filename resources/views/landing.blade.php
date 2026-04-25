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
        }

        .bg-terang { background-color: #fbfaf8; }
        .bg-krem { background-color: #f6f3ef; }

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

        /* --- NAVBAR --- */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(251, 250, 248, 0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #ede9e3;
            padding: 14px 0;
            box-shadow: 0 2px 16px rgba(0,0,0,0.05);
        }
        .navbar .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .navbar-logo img {
            height: 40px;
            width: auto;
        }
        .navbar-links {
            display: flex;
            align-items: center;
            gap: 32px;
        }
        .navbar-links a {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 500;
            transition: color 0.2s;
        }
        .navbar-links a:hover { color: var(--primary); }

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
            box-shadow: 0 20px 60px rgba(141, 164, 126, 0.2), 0 4px 20px rgba(0,0,0,0.06);
            overflow: hidden;
            width: 100%;
        }

        @keyframes floatUp {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        @keyframes floatDown {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(12px); }
        }
        .float-icon-1 { animation: floatUp 3s ease-in-out infinite; }
        .float-icon-2 { animation: floatDown 3.5s ease-in-out infinite; }

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
            box-shadow: 0 4px 24px rgba(0,0,0,0.06), 0 1px 4px rgba(0,0,0,0.03);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.1);
        }
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
            background: #ffffff;
            border-radius: 24px;
            padding: 56px 40px;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #f0ebe1;
            box-shadow: 0 8px 40px rgba(0,0,0,0.07);
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
            box-shadow: 0 4px 24px rgba(0,0,0,0.06), 0 1px 4px rgba(0,0,0,0.03);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .step-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.1);
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
        .feature-card ul { list-style: none; margin-top: 20px; }
        .feature-card li { font-size: 13px; color: var(--text-muted); margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .feature-card li::before { content: '•'; color: var(--primary); font-size: 20px; }

        /* --- MODERN GALLERY (ASIMETRIS) --- */
        .modern-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-auto-rows: 280px;
            gap: 24px;
            margin-top: 40px;
        }
        .gallery-item {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        /* Item pertama ukurannya 2x lebih panjang secara vertikal */
        .gallery-item:nth-child(1) { 
            grid-column: span 1; 
            grid-row: span 2; 
        } 
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .gallery-item:hover img { transform: scale(1.08); }
        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);
            opacity: 0;
            transition: opacity 0.3s;
            display: flex;
            align-items: flex-end;
            padding: 24px;
            color: white;
            pointer-events: none;
        }
        .gallery-item:hover .gallery-overlay { opacity: 1; }

        /* --- CTA SECTION --- */
        .cta-section {
            text-align: center;
            padding: 100px 0 80px 0; /* Padding bawah agar tidak ada gap putih ke footer */
        }
        .cta-box {
            background: #fff;
            padding: 24px;
            border-radius: 16px;
            max-width: 600px;
            margin: 32px auto;
            font-size: 14px;
            color: var(--text-muted);
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
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
            padding: 0 0 24px; 
        }
        .footer-wave {
            background-color: #f6f3ef; 
            line-height: 0;
        }
        .footer-wave svg {
            display: block;
            width: 100%;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
            padding-top: 20px; 
        }
        .footer-logo { font-size: 20px; font-weight: bold; display: flex; align-items: center; gap: 8px; margin-bottom: 16px; color: #333232;}
        .footer-logo img {
            max-height: 48px;
            width: auto;
            margin-right: 8px;
        }
        .footer-col h5 { font-size: 16px; margin-bottom: 20px; font-family: 'DM Sans', sans-serif; color: #333232; }
        .footer-col ul { list-style: none; }
        .footer-col li { margin-bottom: 12px; }
        .footer-col a { color: #555; font-size: 14px; transition: color 0.2s; cursor: pointer; }
        .footer-col a:hover { color: #2e2e2e; }
        .footer-bottom {
            text-align: center;
            padding-top: 24px;
            border-top: 4px solid #efdfdf;
            color: #555;
            font-size: 12px;
        }

        /* --- MODALS --- */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .modal-overlay.active { display: flex; }
        .modal {
            background: #fff;
            border-radius: 24px;
            padding: 40px;
            max-width: 560px;
            width: 100%;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            animation: modalIn 0.25s ease;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        @keyframes modalIn {
            from { opacity:0; transform: translateY(20px); }
            to { opacity:1; transform: translateY(0); }
        }
        .modal-close {
            position: absolute;
            top: 16px; right: 20px;
            background: #f0ebe1;
            border: none;
            width: 32px; height: 32px;
            border-radius: 50%;
            font-size: 16px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted);
        }
        .modal-close:hover { background: #e0dbd0; }
        .modal h3 { font-size: 22px; margin-bottom: 16px; }
        .modal p, .modal li { font-size: 14px; color: var(--text-muted); line-height: 1.8; margin-bottom: 10px; }
        .modal ul { list-style: disc; padding-left: 20px; }
        .modal-tag {
            display: inline-block;
            background: #eef1ec;
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 14px;
        }

        /* --- RESPONSIVE FIX --- */
        @media (max-width: 992px) {
            .hero .container { flex-direction: column; text-align: center; }
            .hero p { margin: 0 auto 32px auto; }
            .hero-buttons { justify-content: center; }
            .grid-3 { grid-template-columns: repeat(2, 1fr); }
            .footer-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .grid-3 { grid-template-columns: 1fr; }
            .cta-buttons { flex-direction: column; align-items: center; }
            .navbar-links { display: none; }
            .modern-grid { grid-template-columns: 1fr; grid-auto-rows: 250px; }
            .gallery-item:nth-child(1) { grid-row: span 1; }
        }
        @media (max-width: 480px) {
            .footer-grid { grid-template-columns: 1fr; text-align: center; }
            .footer-logo { justify-content: center; }
            .footer-col p { margin: 0 auto; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="container">
            <div class="navbar-logo">
                <img src="{{ asset('images/logo_nurtura.png') }}" alt="Nurtura">
            </div>
            <div class="navbar-links">
                <a href="#cara-kerja">Cara Kerja</a>
                <a href="#fitur">Fitur</a>
                <a href="#galeri">Galeri</a>
                <a href="#download-app">Download</a>
            </div>
            <a href="{{ route('login') }}" class="btn btn-primary" style="padding:10px 20px; font-size:13px;">Masuk ke Akun &rarr;</a>
        </div>
    </nav>

    <section class="hero bg-terang">
        <div class="container">
            <div class="hero-content">
                <h1>Temani Perjalanan Emosi Ibu, Bersama Nurtura</h1>
                <p>Bantu ibu memahami kondisi emosinya melalui prediksi risiko depresi pascapersalinan dengan dukungan keluarga</p>
                <div class="hero-buttons">
                    <a href="#download-app" class="btn btn-primary">Mulai Skrining &rarr;</a>
                    <a href="#cara-kerja" class="btn btn-outline">Lihat Cara Kerja</a>
                </div>
            </div>
            <div class="hero-image">
                <div style="display:flex; gap:16px;">
                    <div class="icon-box float-icon-1" style="width:64px; height:64px; border-radius:50%;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </div>
                    <div class="icon-box float-icon-2" style="width:64px; height:64px; border-radius:50%; background:#fff; border:1px solid #eee;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7a7a7a" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
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

    <section id="fitur" class="bg-terang">
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

    <section id="galeri" class="bg-terang">
        <div class="container">
            <div class="section-header">
                <h2>Galeri Nurtura</h2>
                <p>Melihat lebih dekat bagaimana Nurtura mendampingi Ibu.</p>
            </div>
            
            <div class="modern-grid">
                <div class="gallery-item">
                    <img src="{{ asset('images/foto1.png') }}" alt="Pendampingan Emosional">
                    <div class="gallery-overlay">
                        <span>Pendampingan Emosional & Dukungan</span>
                    </div>
                </div>

                <div class="gallery-item">
                    <img src="{{ asset('images/foto2.png') }}" alt="Antarmuka Aplikasi Nurtura">
                    <div class="gallery-overlay">
                        <span>Antarmuka Aplikasi Nurtura</span>
                    </div>
                </div>

                <div class="gallery-item" style="background: #eaddd3; display: flex; align-items: center; justify-content: center; text-align: center; padding: 20px;">
                    <div>
                        <div style="font-size: 36px; margin-bottom: 12px;">✨</div>
                        <p style="color: #6a5e55; font-weight: 600; font-size: 14px;">Lebih banyak momen & visual<br>akan segera hadir.</p>
                    </div>
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
        <div class="footer-wave">
            <svg viewBox="0 0 1440 120" xmlns="http://www.w3.org/2000/svg" style="display: block; width: 100%;">
                <path fill="#aed19e" fill-opacity="1" d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
                <path fill="#c8dcba" fill-opacity="1" d="M0,96L80,85.3C160,75,320,53,480,58.7C640,64,800,96,960,101.3C1120,107,1280,85,1360,74.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
            </svg>
        </div>
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-logo">
                        <img src="{{ asset('images/logo_nurtura.png') }}" alt="Nurtura">
                    </div>
                    <p style="font-size:13px; color:#333232; max-width:200px;">Platform prediksi risiko depresi pascapersalinan untuk mendukung kesehatan mental ibu</p>
                </div>
                <div class="footer-col">
                    <h5>About</h5>
                    <ul>
                        <li><a onclick="openModal('tentang')">Tentang Kami</a></li>
                        <li><a onclick="openModal('tim')">Tim</a></li>
                        <li><a onclick="openModal('blog')">Blog</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h5>Contact</h5>
                    <ul>
                        <li><a onclick="openModal('hubungi')">Hubungi Kami</a></li>
                        <li><a onclick="openModal('support')">Support</a></li>
                        <li><a onclick="openModal('faq')">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h5>Privacy Policy</h5>
                    <ul>
                        <li><a onclick="openModal('privasi')">Kebijakan Privasi</a></li>
                        <li><a onclick="openModal('syarat')">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; 2026 Nurtura. All rights reserved. Made with love for mothers everywhere.
            </div>
        </div>
    </footer>

    <div class="modal-overlay" id="modal-tentang">
        <div class="modal">
            <button class="modal-close" onclick="closeModal('tentang')">✕</button>
            <div class="modal-tag">About</div>
            <h3>Tentang Kami</h3>
            <p>Nurtura adalah platform digital yang dirancang untuk mendukung kesehatan mental ibu pascapersalinan. Kami percaya bahwa setiap ibu berhak mendapatkan pendampingan emosional yang tepat sejak awal.</p>
            <p>Lahir dari kepedulian terhadap tingginya angka depresi pascapersalinan yang kerap tidak terdeteksi, Nurtura menghadirkan solusi berbasis machine learning untuk skrining risiko secara mudah dan terpercaya.</p>
            <p><strong>Visi:</strong> Setiap ibu di Indonesia mendapatkan dukungan kesehatan mental yang layak.</p>
            <p><strong>Misi:</strong> Menyediakan alat deteksi dini yang mudah diakses dan mendampingi ibu melalui perjalanan pascapersalinan.</p>
        </div>
    </div>

    <div class="modal-overlay" id="modal-tim">
    <div class="modal">
        <button class="modal-close" onclick="closeModal('tim')">✕</button>
        <div class="modal-tag">About</div>
        <h3>Tim Nurtura</h3>
        <p>Nurtura dikembangkan oleh tim kolaboratif yang terdiri dari pengembang aplikasi dan pengolah data, dengan fokus menciptakan solusi digital yang nyaman, akurat, dan bermanfaat bagi pengguna.</p>
        
        <ul>
            <li><strong>Bintang Tharisa Syafira</strong> — Front-End Developer (Mobile) & UI Designer, berperan dalam merancang desain antarmuka (UI) serta mengembangkan tampilan aplikasi mobile yang responsif dan user-friendly.</li>
            
            <li><strong>Putri Nazwa Meida Syafeera</strong> — Back-End Developer (Mobile), bertanggung jawab dalam pengelolaan server serta integrasi sistem pada aplikasi mobile.</li>
            
            <li><strong>Anisya Nanda Ayu Kusuma</strong> — Front-End Developer (Web) & UI Designer, fokus pada perancangan desain antarmuka (UI) website serta pengembangan tampilan yang interaktif, modern, dan mudah digunakan.</li>
            
            <li><strong>Dilas Sholeh Masysyuhur</strong> — Data Scientist & Machine Learning Engineer, menangani proses pengolahan data, pembuatan dan pelatihan model, evaluasi performa, serta deployment model.</li>
            
            <li><strong>Mh. Kholil</strong> — Back-End Developer (Mobile), berperan dalam pengembangan dan optimalisasi sistem backend untuk mendukung performa aplikasi mobile serta integrasi sistem pada aplikasi mobile..</li>
            </ul>
         </div>
    </div>

    <div class="modal-overlay" id="modal-blog">
        <div class="modal">
            <button class="modal-close" onclick="closeModal('blog')">✕</button>
            <div class="modal-tag">About</div>
            <h3>Blog Nurtura</h3>
            <p>Blog kami hadir sebagai sumber edukasi seputar kesehatan mental ibu pascapersalinan. Topik yang kami bahas:</p>
            <ul>
                <li>Mengenali tanda-tanda baby blues vs depresi pascapersalinan</li>
                <li>Peran ayah dalam mendukung kesehatan mental ibu</li>
                <li>Tips menjaga keseimbangan emosi di bulan pertama kelahiran</li>
                <li>Kapan harus mencari bantuan profesional?</li>
                <li>Pentingnya support system bagi ibu baru</li>
            </ul>
            <p>Blog lengkap akan segera tersedia. Pantau terus pembaruan kami!</p>
        </div>
    </div>

    <div class="modal-overlay" id="modal-hubungi">
        <div class="modal">
            <button class="modal-close" onclick="closeModal('hubungi')">✕</button>
            <div class="modal-tag">Contact</div>
            <h3>Hubungi Kami</h3>
            <p>📧 <strong>Email:</strong> hellonurtura1@gmail.com</p>
            <p>📱 <strong>WhatsApp:</strong> +62 812-3552-0934</p>
            <p>🏢 <strong>Alamat:</strong> Jember, Jawa Timur, Indonesia</p>
            <p>⏰ <strong>Jam Operasional:</strong> Senin – Jumat, 08.00 – 17.00 WIB</p>
            <p style="margin-top:12px; padding:14px; background:#f6f3ef; border-radius:12px; font-size:13px;">Untuk urusan darurat kesehatan mental, hubungi <strong>119 ext 8</strong> atau tenaga kesehatan terdekat.</p>
        </div>
    </div>

    <div class="modal-overlay" id="modal-support">
        <div class="modal">
            <button class="modal-close" onclick="closeModal('support')">✕</button>
            <div class="modal-tag">Contact</div>
            <h3>Bantuan & Dukungan</h3>
            <p>Butuh bantuan menggunakan Nurtura? Tim kami siap membantu.</p>
            <ul>
                <li>Download aplikasi Nurtura di Google Play / App Store</li>
                <li>Buat akun menggunakan email atau nomor telepon</li>
                <li>Isi profil dan mulai skrining pertama Anda</li>
                <li>Lihat hasil dan rekomendasi di beranda aplikasi</li>
                <li>Undang pasangan untuk terhubung di aplikasi</li>
            </ul>
            <p>Kendala teknis? Email ke <strong>hellonurtura1@gmail.com</strong></p>
        </div>
    </div>

    <div class="modal-overlay" id="modal-faq">
        <div class="modal">
            <button class="modal-close" onclick="closeModal('faq')">✕</button>
            <div class="modal-tag">Contact</div>
            <h3>FAQ</h3>
            <p><strong>Apakah Nurtura gratis?</strong><br>Ya, fitur skrining dasar tersedia gratis untuk semua pengguna.</p>
            <p><strong>Apakah data saya aman?</strong><br>Data dienkripsi dan kami tidak pernah menjual data pribadi Anda.</p>
            <p><strong>Apakah hasil skrining akurat?</strong><br>Model kami telah divalidasi, namun bukan pengganti diagnosis medis. Selalu konsultasikan dengan tenaga kesehatan.</p>
            <p><strong>Berapa lama skrining berlangsung?</strong><br>Sekitar 5–10 menit.</p>
            <p><strong>Bisakah suami mendaftar?</strong><br>Ya! Ayah bisa terhubung dengan akun istri untuk monitoring bersama.</p>
        </div>
    </div>

    <div class="modal-overlay" id="modal-privasi">
        <div class="modal">
            <button class="modal-close" onclick="closeModal('privasi')">✕</button>
            <div class="modal-tag">Legal</div>
            <h3>Kebijakan Privasi</h3>
            <p><strong>Terakhir diperbarui:</strong> Januari 2026</p>
            <p>Nurtura berkomitmen melindungi privasi dan keamanan data pengguna.</p>
            <p><strong>Data yang dikumpulkan:</strong></p>
            <ul>
                <li>Informasi akun (nama, email, nomor telepon)</li>
                <li>Jawaban kuesioner skrining</li>
                <li>Riwayat penggunaan aplikasi</li>
            </ul>
            <p><strong>Penggunaan data:</strong></p>
            <ul>
                <li>Menghasilkan prediksi risiko personal</li>
                <li>Meningkatkan akurasi model (data dianonimkan)</li>
                <li>Mengirimkan notifikasi yang relevan</li>
            </ul>
            <p>Pertanyaan privasi: <strong>privacy@nurtura.id</strong></p>
        </div>
    </div>

    <div class="modal-overlay" id="modal-syarat">
        <div class="modal">
            <button class="modal-close" onclick="closeModal('syarat')">✕</button>
            <div class="modal-tag">Legal</div>
            <h3>Syarat & Ketentuan</h3>
            <p><strong>Terakhir diperbarui:</strong> Januari 2026</p>
            <ul>
                <li>Nurtura adalah alat bantu skrining, <strong>bukan pengganti diagnosis medis</strong>.</li>
                <li>Pengguna bertanggung jawab atas keakuratan informasi yang dimasukkan.</li>
                <li>Dilarang menggunakan platform ini untuk tujuan melanggar hukum.</li>
                <li>Nurtura berhak memperbarui fitur sewaktu-waktu.</li>
                <li>Konten edukatif bersifat informatif dan disusun bersama profesional kesehatan.</li>
            </ul>
            <p>Pertanyaan: <strong>hellonurtura1@gmail.com</strong></p>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById('modal-' + id).classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeModal(id) {
            document.getElementById('modal-' + id).classList.remove('active');
            document.body.style.overflow = '';
        }
        document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.active').forEach(function(modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }
        });
    </script>
</body>
</html>