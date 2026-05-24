@extends('father.layout') {{-- Sesuaikan ke 'layouts.layout' jika file layoutmu dimasukkan ke dalam folder layouts --}}

@section('title', 'Asisten Ayah — Nurtura Family')

@push('styles')
<style>
    /* ===== CONFIG PALETTE WARNA NURTURA ===== */
    :root {
        --color-sage-green: #A3B18A;
        --color-beige: #EDE0D4;
        --color-dusty-rose: #DDBEA9;
        --color-cream-white: #F8F5F2;
        --color-text-dark: #3A3A3A;
        --color-text-muted: #706A64;
        --font-main: 'DM Sans', sans-serif;
    }

    /* ===== AREA WRAPPER UTAMA ===== */
    .cb-wrapper {
        display: flex;
        gap: 20px;
        height: calc(100vh - 140px); /* Menyesuaikan tinggi ruang kosong di bawah topbar */
        font-family: var(--font-main);
        background-color: var(--color-cream-white);
        padding: 12px;
        box-sizing: border-box;
        color: var(--color-text-dark);
        border-radius: 24px;
    }

    /* ===== PANEL KIRI: RIWAYAT CHAT ===== */
    .cb-sidebar {
        width: 300px;
        background: var(--color-beige);
        border-radius: 20px;
        padding: 20px 14px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        flex-shrink: 0;
    }

    .btn-new-chat {
        background-color: var(--color-sage-green);
        color: #ffffff;
        border: none;
        padding: 12px 16px;
        border-radius: 16px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(163, 177, 138, 0.2);
    }

    .btn-new-chat:hover {
        opacity: 0.95;
        transform: translateY(-1px);
    }
    
    .btn-new-chat:active {
        transform: translateY(0);
    }

    .cb-history-section {
        display: flex;
        flex-direction: column;
        gap: 8px;
        overflow-y: auto;
        flex-grow: 1;
    }

    .cb-history-title {
        font-size: 11px;
        font-weight: 700;
        color: var(--color-text-muted);
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 0 8px;
        margin-bottom: 4px;
    }

    .history-card {
        background: transparent;
        padding: 12px 14px;
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .history-card:hover, .history-card.active {
        background: var(--color-cream-white);
        border-color: rgba(163, 177, 138, 0.15);
        box-shadow: 0 4px 12px rgba(58, 58, 58, 0.03);
    }

    .history-card__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }

    .history-card__title {
        font-size: 13px;
        font-weight: 600;
        color: var(--color-text-dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .history-card__date {
        font-size: 11px;
        color: var(--color-text-muted);
        flex-shrink: 0;
    }

    .history-card__snippet {
        font-size: 12px;
        color: var(--color-text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ===== PANEL KANAN: RUANG CHAT AKTIF ===== */
    .cb-main {
        flex-grow: 1;
        background: #ffffff;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid rgba(237, 224, 212, 0.6);
    }

    .cb-body {
        flex-grow: 1;
        overflow-y: auto;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        background: #ffffff;
    }

    /* Penanda Pembatas Waktu */
    .cb-divider {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 10px 0;
    }

    .cb-divider span {
        font-size: 11px;
        font-weight: 700;
        color: var(--color-text-muted);
        letter-spacing: 1.2px;
        background: var(--color-beige);
        padding: 4px 14px;
        border-radius: 20px;
        text-transform: uppercase;
    }

    /* Bubble Obrolan */
    .msg-row {
        display: flex;
        gap: 12px;
        max-width: 80%;
    }

    .msg-row--bot {
        align-self: flex-start;
    }

    .msg-row--user {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .msg-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .msg-avatar--bot {
        background-color: var(--color-sage-green);
        color: #ffffff;
    }

    .msg-avatar--user {
        background-color: var(--color-dusty-rose);
        color: var(--color-text-dark);
    }

    .msg-content-wrapper {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .msg-bubble {
        padding: 14px 18px;
        font-size: 14px;
        line-height: 1.6;
    }

    .msg-row--bot .msg-bubble {
        background: var(--color-cream-white);
        color: var(--color-text-dark);
        border-radius: 4px 20px 20px 20px;
        border: 1px solid rgba(237, 224, 212, 0.4);
    }

    .msg-row--user .msg-bubble {
        background: var(--color-sage-green);
        color: #ffffff;
        border-radius: 20px 4px 20px 20px;
    }

    .msg-time {
        font-size: 11px;
        color: var(--color-text-muted);
        margin-top: 2px;
    }

    .msg-row--user .msg-time {
        align-self: flex-end;
    }

    /* Box Khusus Komponen Tips Dukungan */
    .tip-box {
        background: #ffffff;
        border-left: 4px solid var(--color-dusty-rose);
        border-radius: 4px 14px 14px 4px;
        padding: 14px;
        margin-top: 12px;
        box-shadow: 0 4px 12px rgba(58, 58, 58, 0.02);
        max-width: 100%;
        border-top: 1px solid rgba(221, 190, 169, 0.3);
        border-right: 1px solid rgba(221, 190, 169, 0.3);
        border-bottom: 1px solid rgba(221, 190, 169, 0.3);
    }

    .tip-header {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 700;
        color: var(--color-text-dark);
        margin-bottom: 6px;
    }

    .tip-header svg {
        color: var(--color-sage-green);
    }

    .tip-text {
        font-size: 13px;
        color: var(--color-text-dark);
        line-height: 1.5;
        margin-bottom: 8px;
    }

    .tip-link {
        font-size: 12px;
        font-weight: 700;
        color: var(--color-text-dark);
        text-decoration: underline;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .tip-link:hover {
        color: var(--color-sage-green);
    }

    /* ===== AREA INPUT FORM CHAT ===== */
    .cb-footer {
        padding: 16px 24px 20px 24px;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        gap: 10px;
        border-top: 1px solid rgba(237, 224, 212, 0.4);
    }

    .input-bar-container {
        display: flex;
        align-items: center;
        background: var(--color-cream-white);
        border-radius: 30px;
        padding: 6px 10px 6px 18px;
        gap: 12px;
        border: 1px solid rgba(237, 224, 212, 0.5);
    }

    .input-actions-left {
        display: flex;
        align-items: center;
        gap: 14px;
        color: var(--color-text-muted);
    }

    .input-btn-icon {
        background: transparent;
        border: none;
        color: inherit;
        cursor: pointer;
        display: flex;
        align-items: center;
        padding: 0;
        transition: color 0.2s;
    }

    .input-btn-icon:hover {
        color: var(--color-sage-green);
    }

    .chat-input-field {
        flex-grow: 1;
        background: transparent;
        border: none;
        outline: none;
        font-family: var(--font-main);
        font-size: 14px;
        color: var(--color-text-dark);
        padding: 8px 0;
    }

    .chat-input-field::placeholder {
        color: var(--color-text-muted);
        opacity: 0.65;
    }

    .btn-send-message {
        background-color: var(--color-sage-green);
        color: white;
        border: none;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s, opacity 0.2s;
        flex-shrink: 0;
    }

    .btn-send-message:hover {
        opacity: 0.95;
    }

    .cb-disclaimer {
        text-align: center;
        font-size: 11px;
        color: var(--color-text-muted);
        opacity: 0.8;
    }
</style>
@endpush

@section('content')
<div class="cb-wrapper">

    {{-- ================= PANEL KIRI: RIWAYAT CHAT ================= --}}
    <aside class="cb-sidebar">
        <button class="btn-new-chat" id="btnNewChat">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Mulai Chat Baru
        </button>

        <div class="cb-history-section">
            <span class="cb-history-title">Riwayat Chat</span>
            
            <div class="history-card active">
                <div class="history-card__header">
                    <span class="history-card__title">Diskusi Pemulihan Ibu</span>
                    <span class="history-card__date">Hari ini</span>
                </div>
                <p class="history-card__snippet">Bagaimana cara terbaik untuk tetap...</p>
            </div>

            <div class="history-card">
                <div class="history-card__header">
                    <span class="history-card__title">Diskusi Kesehatan Ibu</span>
                    <span class="history-card__date">24 Okt</span>
                </div>
                <p class="history-card__snippet">Ayah sudah melakukan hal yang hebat...</p>
            </div>

            <div class="history-card">
                <div class="history-card__header">
                    <span class="history-card__title">Panduan Tidur Bayi</span>
                    <span class="history-card__date">22 Okt</span>
                </div>
                <p class="history-card__snippet">Pola tidur bayi usia 2 bulan memang...</p>
            </div>
        </div>
    </aside>

    {{-- ================= PANEL KANAN: CHAT UTAMA ================= --}}
    <main class="cb-main">
        <div class="cb-body" id="chatBody">
            
            <div class="cb-divider">
                <span>Hari Ini</span>
            </div>

            <div class="msg-row msg-row--bot">
                <div class="msg-avatar msg-avatar--bot">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                </div>
                <div class="msg-content-wrapper">
                    <div class="msg-bubble">
                        Halo, Ayah. Saya Asisten Ayah dari Nurtura Family. Saya di sini untuk memberikan dukungan emosional dan panduan praktis selama masa pemulihan Ibu. Apa yang sedang Ayah pikirkan hari ini?
                    </div>
                    <span class="msg-time">09:41 AM</span>
                </div>
            </div>

            <div class="msg-row msg-row--user">
                <div class="msg-avatar msg-avatar--user">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <div class="msg-content-wrapper">
                    <div class="msg-bubble">
                        Saya merasa sedikit kewalahan membagi waktu antara pekerjaan dan menjaga bayi kami. Bagaimana cara terbaik untuk tetap mendukung istri saya?
                    </div>
                    <span class="msg-time">09:43 AM</span>
                </div>
            </div>

            <div class="msg-row msg-row--bot">
                <div class="msg-avatar msg-avatar--bot">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                </div>
                <div class="msg-content-wrapper">
                    <div class="msg-bubble">
                        Sangat wajar merasa kewalahan, Ayah. Ingatlah bahwa Ayah tidak harus melakukan semuanya sendiri secara sempurna. Berikut adalah beberapa langkah kecil yang bisa membantu:
                        
                        <div class="tip-box">
                            <div class="tip-header">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                Tips Dukungan
                            </div>
                            <div class="tip-text">
                                <strong>"Pendengaran yang Aktif"</strong> — Luangkan 15 menit tanpa distraksi ponsel untuk mendengarkan keluh kesah Ibu tanpa langsung menghakimi atau memotong pembicaraan.
                            </div>
                            <a href="#" class="tip-link">
                                Lihat Panduan Lengkap 
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </a>
                        </div>
                    </div>
                    <span class="msg-time">09:44 AM</span>
                </div>
            </div>

        </div>

        <footer class="cb-footer">
            <form id="formSendMessage" autocomplete="off">
                <div class="input-bar-container">
                    <div class="input-actions-left">
                        <button type="button" class="input-btn-icon" aria-label="Lampirkan File">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
                        </button>
                        <button type="button" class="input-btn-icon" aria-label="Pesan Suara">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v1a7 7 0 0 1-14 0v-1"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
                        </button>
                    </div>
                    
                    <input type="text" id="inputChat" class="chat-input-field" placeholder="Tulis pesan untuk Asisten Ayah...">
                    
                    <button type="submit" class="btn-send-message" aria-label="Kirim Pesan">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </div>
            </form>
            <div class="cb-disclaimer">
                Percakapan ini dienkripsi secara aman &bull; Berdasarkan Protokol Klinis Nurtura
            </div>
        </footer>
    </main>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formSendMessage');
    const input = document.getElementById('inputChat');
    const chatBody = document.getElementById('chatBody');

    // Menjaga scroll otomatis agar selalu berada di pesan paling bawah
    chatBody.scrollTop = chatBody.scrollHeight;

    // Aksi Kirim Pesan (Sisi Frontend Sementara)
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        
        const messageText = input.value.trim();
        if (!messageText) return;

        // Ambil waktu realtime saat ini
        const now = new Date();
        let hours = now.getHours();
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; 
        const timeString = `${String(hours).padStart(2, '0')}:${minutes} ${ampm}`;

        // Masukkan pesan baru user ke layar chat
        const userMessageHTML = `
            <div class="msg-row msg-row--user">
                <div class="msg-avatar msg-avatar--user">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <div class="msg-content-wrapper">
                    <div class="msg-bubble">${messageText}</div>
                    <span class="msg-time">${timeString}</span>
                </div>
            </div>
        `;
        
        chatBody.insertAdjacentHTML('beforeend', userMessageHTML);
        input.value = '';
        chatBody.scrollTop = chatBody.scrollHeight;

        // NOTE UNTUK BACKEND:
        // Di sini teman backend kamu nanti tinggal menggunakan API fetch POST untuk mengirim 
        // nilai variabel `messageText` ke server dan me-render ulang jawaban dinamis dari AI.
    });

    // Pindah antar kartu riwayat obrolan (efek visual aktif)
    const historyCards = document.querySelectorAll('.history-card');
    historyCards.forEach(card => {
        card.addEventListener('click', function() {
            historyCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>
@endpush