@extends('father.layout')

@section('title', 'Asisten Ayah - Nurtura Family')

@push('styles')
<style>
    .cb-wrapper {
        display: flex;
        gap: 20px;
        height: calc(100vh - 140px);
        min-height: 560px;
        color: var(--clr-text-body);
    }

    .cb-sidebar {
        width: 270px;
        background: var(--clr-surface);
        border: 1px solid var(--clr-border);
        border-radius: var(--radius-card);
        box-shadow: var(--shadow-card);
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        flex-shrink: 0;
        min-width: 0;
    }

    .btn-new-chat {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        min-height: 40px;
        border-radius: var(--radius-sm);
        background: var(--clr-primary);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        transition: background .15s, opacity .15s;
    }

    .btn-new-chat:hover {
        background: var(--clr-primary-dark);
    }

    .cb-history-section {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-height: 0;
        flex: 1;
    }

    .cb-history-title {
        font-size: 11px;
        font-weight: 700;
        color: var(--clr-text-muted);
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: 0 2px;
    }

    #historyList {
        display: flex;
        flex-direction: column;
        gap: 8px;
        overflow-y: auto;
        padding-right: 2px;
    }

    .history-card {
        padding: 12px;
        border-radius: var(--radius-sm);
        border: 1px solid transparent;
        cursor: pointer;
        transition: background .15s, border-color .15s;
    }

    .history-card:hover,
    .history-card.active {
        background: var(--clr-bg);
        border-color: var(--clr-border);
    }

    .history-card__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 5px;
    }

    .history-card__title {
        font-size: 13px;
        font-weight: 700;
        color: var(--clr-text-heading);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .history-card__date {
        font-size: 11px;
        color: var(--clr-text-muted);
        flex-shrink: 0;
    }

    .history-card__meta {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    .history-card__delete {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--clr-text-muted);
        transition: background .15s, color .15s;
    }

    .history-card__delete:hover {
        background: var(--clr-high-bg);
        color: var(--clr-high-text);
    }

    .history-card__snippet {
        font-size: 12px;
        color: var(--clr-text-muted);
        line-height: 1.45;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .cb-empty-state,
    .cb-status {
        font-size: 12px;
        color: var(--clr-text-muted);
        line-height: 1.55;
        padding: 10px 4px;
    }

    .cb-status {
        text-align: center;
    }

    .cb-main {
        flex: 1;
        min-width: 0;
        background: var(--clr-surface);
        border: 1px solid var(--clr-border);
        border-radius: var(--radius-card);
        box-shadow: var(--shadow-card);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .cb-body {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        background: #fff;
    }

    .cb-divider {
        display: flex;
        justify-content: center;
        margin: 2px 0 8px;
    }

    .cb-divider span {
        font-size: 11px;
        font-weight: 700;
        color: var(--clr-text-muted);
        background: var(--clr-bg);
        padding: 5px 12px;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .msg-row {
        display: flex;
        gap: 11px;
        max-width: 82%;
    }

    .msg-row--bot {
        align-self: flex-start;
    }

    .msg-row--user {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .msg-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .msg-avatar--bot {
        background: var(--clr-primary);
        color: #fff;
    }

    .msg-avatar--user {
        background: var(--clr-accent-sand);
        color: var(--clr-text-heading);
    }

    .msg-content-wrapper {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 0;
    }

    .msg-bubble {
        padding: 13px 16px;
        font-size: 13.5px;
        line-height: 1.65;
        word-wrap: break-word;
        overflow-wrap: anywhere;
    }

    .msg-row--bot .msg-bubble {
        background: var(--clr-bg);
        color: var(--clr-text-body);
        border: 1px solid var(--clr-border-light);
        border-radius: 4px 18px 18px 18px;
    }

    .msg-row--user .msg-bubble {
        background: var(--clr-primary);
        color: #fff;
        border-radius: 18px 4px 18px 18px;
    }

    .msg-time {
        font-size: 11px;
        color: var(--clr-text-muted);
    }

    .msg-row--user .msg-time {
        align-self: flex-end;
    }

    .msg-priority {
        display: inline-flex;
        width: fit-content;
        margin-bottom: 8px;
        border-radius: 999px;
        padding: 3px 9px;
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        background: var(--clr-primary-light);
        color: var(--clr-primary-dark);
    }

    .msg-priority--perhatian {
        background: var(--clr-med-bg);
        color: var(--clr-med-text);
    }

    .msg-priority--darurat {
        background: var(--clr-high-bg);
        color: var(--clr-high-text);
    }

    .msg-actions {
        margin-top: 10px;
        padding-left: 18px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .msg-actions li {
        list-style: disc;
        font-size: 13px;
        line-height: 1.5;
    }

    .cb-footer {
        padding: 16px 22px 18px;
        border-top: 1px solid var(--clr-border-light);
        display: flex;
        flex-direction: column;
        gap: 9px;
        background: #fff;
    }

    .input-bar-container {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--clr-bg);
        border: 1px solid var(--clr-border);
        border-radius: 999px;
        padding: 6px 8px 6px 16px;
    }

    .chat-input-field {
        flex: 1;
        min-width: 0;
        border: 0;
        outline: 0;
        background: transparent;
        color: var(--clr-text-body);
        font-size: 13.5px;
        padding: 8px 0;
    }

    .btn-send-message {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--clr-primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .btn-send-message:hover {
        background: var(--clr-primary-dark);
    }

    .btn-send-message:disabled,
    .chat-input-field:disabled {
        opacity: .6;
        cursor: not-allowed;
    }

    .cb-disclaimer {
        text-align: center;
        font-size: 11px;
        color: var(--clr-text-muted);
    }

    .cb-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .38);
        z-index: 500;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .cb-modal-backdrop.is-open {
        display: flex;
    }

    .cb-modal {
        width: min(420px, 100%);
        background: #fff;
        border-radius: var(--radius-card);
        box-shadow: 0 24px 80px rgba(15, 23, 42, .2);
        border: 1px solid var(--clr-border);
        padding: 22px;
    }

    .cb-modal__title {
        font-family: var(--font-display);
        font-size: 17px;
        font-weight: 700;
        color: var(--clr-text-heading);
        margin-bottom: 8px;
    }

    .cb-modal__text {
        font-size: 13px;
        color: var(--clr-text-label);
        line-height: 1.6;
        margin-bottom: 18px;
    }

    .cb-modal__actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .cb-modal__btn {
        min-height: 38px;
        padding: 0 14px;
        border-radius: var(--radius-sm);
        font-size: 13px;
        font-weight: 700;
    }

    .cb-modal__btn--cancel {
        background: var(--clr-bg);
        color: var(--clr-text-body);
        border: 1px solid var(--clr-border);
    }

    .cb-modal__btn--delete {
        background: var(--clr-high-text);
        color: #fff;
    }

    .cb-modal__btn--delete:disabled {
        opacity: .7;
        cursor: not-allowed;
    }

    @media (max-width: 920px) {
        .cb-wrapper {
            height: auto;
            flex-direction: column;
        }

        .cb-sidebar {
            width: 100%;
            max-height: 230px;
        }

        .cb-main {
            min-height: 560px;
        }

        .msg-row {
            max-width: 94%;
        }
    }
</style>
@endpush

@section('content')
<div class="cb-wrapper">
    <aside class="cb-sidebar">
        <button class="btn-new-chat" id="btnNewChat" type="button">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Mulai Chat Baru
        </button>

        <div class="cb-history-section">
            <span class="cb-history-title">Riwayat Chat</span>
            <div id="historyList">
                <div class="cb-empty-state">Memuat riwayat chat...</div>
            </div>
        </div>
    </aside>

    <main class="cb-main">
        <div class="cb-body" id="chatBody"></div>

        <footer class="cb-footer">
            <form id="formSendMessage" autocomplete="off">
                <div class="input-bar-container">
                    <input type="text" id="inputChat" class="chat-input-field" maxlength="{{ config('services.chatbot.max_message_length', 1000) }}" placeholder="Tulis pesan untuk Asisten Ayah...">
                    <button type="submit" class="btn-send-message" aria-label="Kirim Pesan">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </div>
            </form>
            <div class="cb-disclaimer">
                Asisten ini bersifat pendamping awal dan bukan pengganti tenaga kesehatan profesional.
            </div>
        </footer>
    </main>
</div>

<div class="cb-modal-backdrop" id="deleteChatModal" aria-hidden="true">
    <div class="cb-modal" role="dialog" aria-modal="true" aria-labelledby="deleteChatTitle">
        <h2 class="cb-modal__title" id="deleteChatTitle">Hapus riwayat chat?</h2>
        <p class="cb-modal__text">
            Sesi chat ini akan dihapus, apakah anda yakin?
        </p>
        <div class="cb-modal__actions">
            <button type="button" class="cb-modal__btn cb-modal__btn--cancel" id="cancelDeleteChat">Batal</button>
            <button type="button" class="cb-modal__btn cb-modal__btn--delete" id="confirmDeleteChat">Hapus Chat</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = '/login';
        return;
    }

    const form = document.getElementById('formSendMessage');
    const input = document.getElementById('inputChat');
    const chatBody = document.getElementById('chatBody');
    const historyList = document.getElementById('historyList');
    const btnNewChat = document.getElementById('btnNewChat');
    const sendButton = form.querySelector('.btn-send-message');
    const deleteChatModal = document.getElementById('deleteChatModal');
    const cancelDeleteChat = document.getElementById('cancelDeleteChat');
    const confirmDeleteChat = document.getElementById('confirmDeleteChat');
    let activeSessionId = null;
    let cachedSessions = [];
    let isSending = false;
    let pendingDeleteSessionId = null;

    function authHeaders(extra = {}) {
        return {
            Authorization: 'Bearer ' + token,
            Accept: 'application/json',
            ...extra
        };
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function formatTime(value = null) {
        const date = value ? new Date(value) : new Date();
        if (Number.isNaN(date.getTime())) return '';
        return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }

    function formatSessionDate(value) {
        if (!value) return '';
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return '';
        const today = new Date();
        return date.toDateString() === today.toDateString()
            ? 'Hari ini'
            : date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
    }

    function scrollToBottom() {
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function greetingHtml() {
        return `
            <div class="cb-divider"><span>Hari Ini</span></div>
            <div class="msg-row msg-row--bot">
                <div class="msg-avatar msg-avatar--bot">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                </div>
                <div class="msg-content-wrapper">
                    <div class="msg-bubble">
                        Halo, Ayah. Saya Asisten Ayah dari Nurtura Family. Saya bisa membantu Ayah memahami kondisi Ibu dan mencari langkah dukungan yang praktis. Apa yang sedang Ayah pikirkan hari ini?
                    </div>
                    <span class="msg-time">${formatTime()}</span>
                </div>
            </div>
        `;
    }

    function renderEmptyChat() {
        chatBody.innerHTML = greetingHtml();
        scrollToBottom();
    }

    function priorityBadge(metadata = {}) {
        const priority = metadata.priority;
        if (!priority) return '';
        const className = priority === 'darurat'
            ? 'msg-priority msg-priority--darurat'
            : priority === 'perhatian'
                ? 'msg-priority msg-priority--perhatian'
                : 'msg-priority';
        return `<span class="${className}">${escapeHtml(priority)}</span>`;
    }

    function suggestedActions(metadata = {}) {
        const actions = Array.isArray(metadata.suggested_actions) ? metadata.suggested_actions : [];
        if (!actions.length) return '';
        return `<ul class="msg-actions">${actions.map(action => `<li>${escapeHtml(action)}</li>`).join('')}</ul>`;
    }

    function messageHtml(role, message, time = null, metadata = {}) {
        const isUser = role === 'user';
        const avatar = isUser
            ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>'
            : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>';

        return `
            <div class="msg-row ${isUser ? 'msg-row--user' : 'msg-row--bot'}">
                <div class="msg-avatar ${isUser ? 'msg-avatar--user' : 'msg-avatar--bot'}">${avatar}</div>
                <div class="msg-content-wrapper">
                    <div class="msg-bubble">
                        ${!isUser ? priorityBadge(metadata) : ''}
                        ${escapeHtml(message).replace(/\n/g, '<br>')}
                        ${!isUser ? suggestedActions(metadata) : ''}
                    </div>
                    <span class="msg-time">${formatTime(time)}</span>
                </div>
            </div>
        `;
    }

    function setSending(state) {
        isSending = state;
        input.disabled = state;
        sendButton.disabled = state;
    }

    function openDeleteModal(sessionId) {
        pendingDeleteSessionId = sessionId;
        deleteChatModal.classList.add('is-open');
        deleteChatModal.setAttribute('aria-hidden', 'false');
        confirmDeleteChat.disabled = false;
        confirmDeleteChat.focus();
    }

    function closeDeleteModal() {
        pendingDeleteSessionId = null;
        deleteChatModal.classList.remove('is-open');
        deleteChatModal.setAttribute('aria-hidden', 'true');
        confirmDeleteChat.disabled = false;
    }

    function renderTyping() {
        chatBody.insertAdjacentHTML('beforeend', '<div class="cb-status" id="typingStatus">Asisten sedang menyiapkan jawaban...</div>');
        scrollToBottom();
    }

    function removeTyping() {
        document.getElementById('typingStatus')?.remove();
    }

    function renderSessions(sessions = cachedSessions) {
        cachedSessions = sessions;
        if (!sessions.length) {
            historyList.innerHTML = '<div class="cb-empty-state">Belum ada riwayat. Mulai percakapan baru dengan Asisten Ayah.</div>';
            return;
        }

        historyList.innerHTML = sessions.map(session => `
            <div class="history-card ${session.id === activeSessionId ? 'active' : ''}" data-session-id="${escapeHtml(session.id)}">
                <div class="history-card__header">
                    <span class="history-card__title">${escapeHtml(session.title || 'Chat Baru')}</span>
                    <span class="history-card__meta">
                        <span class="history-card__date">${escapeHtml(formatSessionDate(session.updated_at || session.created_at))}</span>
                        <button class="history-card__delete" type="button" data-delete-session-id="${escapeHtml(session.id)}" aria-label="Hapus chat">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        </button>
                    </span>
                </div>
                <p class="history-card__snippet">${escapeHtml(session.last_message || 'Belum ada pesan')}</p>
            </div>
        `).join('');

        historyList.querySelectorAll('.history-card').forEach(card => {
            card.addEventListener('click', () => loadMessages(card.dataset.sessionId));
        });

        historyList.querySelectorAll('.history-card__delete').forEach(button => {
            button.addEventListener('click', event => {
                event.preventDefault();
                event.stopPropagation();
                openDeleteModal(button.dataset.deleteSessionId);
            });
        });
    }

    async function loadSessions() {
        try {
            const res = await fetch('/api/chatbot/sessions', { headers: authHeaders() });
            if (res.status === 401) {
                localStorage.removeItem('token');
                window.location.href = '/login';
                return;
            }
            const data = await res.json();
            renderSessions(Array.isArray(data.data) ? data.data : []);
        } catch (error) {
            historyList.innerHTML = '<div class="cb-empty-state">Riwayat chat belum bisa dimuat.</div>';
            console.error(error);
        }
    }

    async function loadMessages(sessionId) {
        if (!sessionId) return;
        activeSessionId = sessionId;
        renderSessions();

        try {
            const res = await fetch(`/api/chatbot/sessions/${encodeURIComponent(sessionId)}/messages`, {
                headers: authHeaders()
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Gagal memuat pesan.');

            const messages = Array.isArray(data.data) ? data.data : [];
            if (!messages.length) {
                renderEmptyChat();
                return;
            }

            chatBody.innerHTML = `
                <div class="cb-divider"><span>Riwayat Chat</span></div>
                ${messages.map(item => messageHtml(item.role, item.message, item.created_at, item.metadata || {})).join('')}
            `;
            scrollToBottom();
            await loadSessions();
        } catch (error) {
            chatBody.insertAdjacentHTML('beforeend', `<div class="cb-status">${escapeHtml(error.message)}</div>`);
            console.error(error);
        }
    }

    async function sendMessage(messageText) {
        setSending(true);
        chatBody.insertAdjacentHTML('beforeend', messageHtml('user', messageText));
        input.value = '';
        renderTyping();

        try {
            const payload = { message: messageText };
            if (activeSessionId) payload.session_id = activeSessionId;

            const res = await fetch('/api/chatbot/message', {
                method: 'POST',
                headers: authHeaders({ 'Content-Type': 'application/json' }),
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (!res.ok || !data.status) {
                throw new Error(data.message || data.error || 'Gagal mengirim pesan.');
            }

            activeSessionId = data.session?.id || activeSessionId;
            removeTyping();
            chatBody.insertAdjacentHTML(
                'beforeend',
                messageHtml('assistant', data.message?.message || data.chatbot?.reply || '', null, data.message?.metadata || data.chatbot || {})
            );
            scrollToBottom();
            await loadSessions();
        } catch (error) {
            removeTyping();
            chatBody.insertAdjacentHTML('beforeend', messageHtml('assistant', 'Maaf, pesan belum bisa dikirim. Coba beberapa saat lagi.', null, { priority: 'perhatian' }));
            console.error(error);
        } finally {
            setSending(false);
            input.focus();
        }
    }

    async function deleteSession(sessionId) {
        if (!sessionId) return;
        confirmDeleteChat.disabled = true;

        try {
            const res = await fetch(`/api/chatbot/sessions/${encodeURIComponent(sessionId)}`, {
                method: 'DELETE',
                headers: authHeaders()
            });
            const data = await res.json();
            if (!res.ok || !data.status) {
                throw new Error(data.message || 'Gagal menghapus session.');
            }

            if (activeSessionId === sessionId) {
                activeSessionId = null;
                renderEmptyChat();
            }

            cachedSessions = cachedSessions.filter(session => session.id !== sessionId);
            renderSessions(cachedSessions);
            closeDeleteModal();
            await loadSessions();
        } catch (error) {
            confirmDeleteChat.disabled = false;
            alert(error.message || 'Riwayat chat belum bisa dihapus.');
            console.error(error);
        }
    }

    cancelDeleteChat.addEventListener('click', closeDeleteModal);
    deleteChatModal.addEventListener('click', function (event) {
        if (event.target === deleteChatModal) closeDeleteModal();
    });
    confirmDeleteChat.addEventListener('click', function () {
        deleteSession(pendingDeleteSessionId);
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && deleteChatModal.classList.contains('is-open')) {
            closeDeleteModal();
        }
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (isSending) return;

        const messageText = input.value.trim();
        if (!messageText) return;
        sendMessage(messageText);
    });

    btnNewChat.addEventListener('click', function () {
        activeSessionId = null;
        renderEmptyChat();
        renderSessions();
        input.focus();
    });

    renderEmptyChat();
    loadSessions();
});
</script>
@endpush
