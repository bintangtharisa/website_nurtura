document.addEventListener("DOMContentLoaded", function () {
    const token = localStorage.getItem("token");
    if (!token) {
        return;
    }

    const panel = document.getElementById("notificationPanel");
    const button = document.getElementById("notificationButton");
    const countBadge = document.getElementById("notificationCount");
    const listContainer = document.getElementById("notificationList");
    const markAllBtn = document.getElementById("markAllReadBtn");
    const panelPlaceholder = document.getElementById("notificationPlaceholder");

    function injectNotificationStyles() {
        if (document.getElementById("notificationDeleteStyles")) return;

        const style = document.createElement("style");
        style.id = "notificationDeleteStyles";
        style.textContent = `
            .topbar__notif-row {
                position: relative;
                display: flex;
                align-items: stretch;
                background: transparent;
                transition: background 0.15s;
            }
            .topbar__notif-row:hover,
            .topbar__notif-row:focus-within {
                background: rgba(15, 23, 42, 0.04);
            }
            .topbar__notif-content {
                flex: 1;
                min-width: 0;
            }
            .topbar__notif-delete {
                position: absolute;
                top: 10px;
                right: 10px;
                width: 28px;
                height: 28px;
                border: none;
                border-radius: 999px;
                background: rgba(248, 250, 252, 0.96);
                color: #94A3B8;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                opacity: 0;
                transform: translateY(-2px);
                transition: opacity 0.15s, transform 0.15s, color 0.15s, background 0.15s;
                box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
            }
            .topbar__notif-row:hover .topbar__notif-delete,
            .topbar__notif-row:focus-within .topbar__notif-delete {
                opacity: 1;
                transform: translateY(0);
            }
            .topbar__notif-delete:hover {
                background: #FEE2E2;
                color: #DC2626;
            }
        `;
        document.head.appendChild(style);
    }

    function escapeHtml(value) {
        return String(value ?? "")
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#39;");
    }

    function buildHeaders() {
        return {
            Authorization: "Bearer " + token,
            Accept: "application/json",
            "Content-Type": "application/json",
        };
    }

    function formatRelativeDate(timestamp) {
        if (!timestamp) {
            return "";
        }
        const date = new Date(timestamp);
        if (Number.isNaN(date.getTime())) {
            return "";
        }
        const diff = Math.floor((Date.now() - date.getTime()) / 1000);
        if (diff < 60) {
            return "Baru saja";
        }
        if (diff < 3600) {
            return `${Math.floor(diff / 60)} mnt lalu`;
        }
        if (diff < 86400) {
            return `${Math.floor(diff / 3600)} jam lalu`;
        }
        return `${Math.floor(diff / 86400)} hari lalu`;
    }

    function renderEmpty() {
        if (!listContainer) return;
        listContainer.innerHTML = `
            <div class="topbar__notif-empty" style="padding: 18px 14px; text-align: center; color: #475569; font-size: 13px; line-height: 1.5;">
                Belum ada notifikasi baru.
            </div>
        `;
    }

    function renderLoading() {
        if (!listContainer) return;
        listContainer.innerHTML = `
            <div class="topbar__notif-loading" style="padding: 18px 14px; text-align: center; color: #475569; font-size: 13px;">
                Memuat notifikasi...
            </div>
        `;
    }

    function updateBadge(count) {
        if (!countBadge) return;
        if (!count || Number(count) <= 0) {
            countBadge.style.display = "none";
            countBadge.textContent = "";
            return;
        }
        countBadge.textContent = count > 99 ? "99+" : String(count);
        countBadge.style.display = "flex";
    }

    async function fetchUnreadCount() {
        try {
            const response = await fetch("/api/notifications/unread-count", {
                headers: buildHeaders(),
            });
            if (!response.ok) throw new Error("Unread count fetch failed");
            const result = await response.json();
            if (
                result &&
                result.data &&
                typeof result.data.count !== "undefined"
            ) {
                updateBadge(result.data.count);
            }
        } catch (err) {
            console.warn("Notification unread count error:", err);
        }
    }

    async function fetchNotifications() {
        if (!listContainer) return;
        injectNotificationStyles();
        renderLoading();

        try {
            const response = await fetch("/api/notifications?limit=6", {
                headers: buildHeaders(),
            });
            if (!response.ok) {
                throw new Error("Notification list fetch failed");
            }
            const result = await response.json();
            const items = result?.data?.items || [];
            if (!Array.isArray(items) || items.length === 0) {
                renderEmpty();
                return;
            }
            listContainer.innerHTML = items
                .map((item) => {
                    const unreadClass = item.is_read
                        ? ""
                        : "topbar__notif-item-unread";
                    const createdAt = formatRelativeDate(item.created_at);
                    const title = escapeHtml(item.title);
                    const message = escapeHtml(item.message);
                    return `
                    <div class="topbar__notif-row ${unreadClass}" data-notification-row="${item.id}">
                        <button type="button" data-notification-id="${item.id}" class="topbar__notif-item topbar__notif-content" style="width:100%; text-align:left; border:none; background:transparent; padding: 14px 48px 14px 14px; display:flex; flex-direction:column; gap: 6px; cursor:pointer; transition: background 0.15s;">
                            <div style="display:flex; justify-content:space-between; gap: 10px; align-items:flex-start;">
                                <span style="font-weight:700; color:#0F172A; font-size:14px; line-height:1.35;">${title}</span>
                                <span style="font-size:11px; color:#64748B; white-space:nowrap;">${createdAt}</span>
                            </div>
                            <span style="font-size:13px; color:#475569; line-height:1.6;">${message}</span>
                        </button>
                        <button type="button" data-notification-delete-id="${item.id}" class="topbar__notif-delete" aria-label="Hapus notifikasi">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M3 6h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M8 6V4.5A1.5 1.5 0 019.5 3h5A1.5 1.5 0 0116 4.5V6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10 11v6M14 11v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                `;
                })
                .join("");

            listContainer
                .querySelectorAll("button[data-notification-id]")
                .forEach((btn) => {
                    btn.addEventListener("click", async () => {
                        const notificationId = btn.getAttribute(
                            "data-notification-id",
                        );
                        if (!notificationId) return;
                        await markAsRead(notificationId);
                        btn.closest(".topbar__notif-row")?.classList.remove(
                            "topbar__notif-item-unread",
                        );
                    });
                });

            listContainer
                .querySelectorAll("button[data-notification-delete-id]")
                .forEach((btn) => {
                    btn.addEventListener("click", async (event) => {
                        event.stopPropagation();
                        const notificationId = btn.getAttribute(
                            "data-notification-delete-id",
                        );
                        if (!notificationId) return;
                        await deleteNotification(notificationId);
                    });
                });
        } catch (err) {
            listContainer.innerHTML = `
                <div class="topbar__notif-empty" style="padding: 18px 14px; text-align: center; color: #EF4444; font-size: 13px;">
                    Gagal memuat notifikasi.
                </div>
            `;
            console.warn(err);
        }
    }

    async function deleteNotification(notificationId) {
        try {
            const response = await fetch(`/api/notifications/${notificationId}`, {
                method: "DELETE",
                headers: buildHeaders(),
            });
            if (!response.ok) {
                throw new Error("Delete notification failed");
            }
            await fetchNotifications();
            await fetchUnreadCount();
        } catch (err) {
            console.warn("Notification delete error:", err);
        }
    }

    async function markAsRead(notificationId) {
        try {
            const response = await fetch(
                `/api/notifications/${notificationId}/read`,
                {
                    method: "PATCH",
                    headers: buildHeaders(),
                    body: JSON.stringify({ id: notificationId }),
                },
            );
            if (!response.ok) {
                throw new Error("Mark as read failed");
            }
            await fetchUnreadCount();
        } catch (err) {
            console.warn("Notification mark as read error:", err);
        }
    }

    async function markAllAsRead() {
        if (!markAllBtn) return;
        markAllBtn.disabled = true;
        markAllBtn.textContent = "Sedang menandai...";
        try {
            const response = await fetch("/api/notifications/read-all", {
                method: "PATCH",
                headers: buildHeaders(),
            });
            if (!response.ok) {
                throw new Error("Mark all read failed");
            }
            await fetchNotifications();
            await fetchUnreadCount();
        } catch (err) {
            console.warn("Notification mark all read error:", err);
        } finally {
            markAllBtn.disabled = false;
            markAllBtn.textContent = "Tandai semua";
        }
    }

    function togglePanel(event) {
        if (!panel) return;
        event.stopPropagation();
        panel.hidden = !panel.hidden;
        if (!panel.hidden) {
            fetchNotifications();
        }
    }

    function closePanel(event) {
        if (!panel || !button) return;
        if (panel.hidden) return;
        if (!panel.contains(event.target) && !button.contains(event.target)) {
            panel.hidden = true;
        }
    }

    if (button) {
        button.addEventListener("click", togglePanel);
    }
    if (markAllBtn) {
        markAllBtn.addEventListener("click", markAllAsRead);
    }
    document.addEventListener("click", closePanel);
    fetchUnreadCount();
});
