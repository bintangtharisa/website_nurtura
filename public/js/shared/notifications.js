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
                    return `
                    <button type="button" data-notification-id="${item.id}" class="topbar__notif-item ${unreadClass}" style="width:100%; text-align:left; border:none; background:transparent; padding: 14px 14px; display:flex; flex-direction:column; gap: 6px; cursor:pointer; transition: background 0.15s;">
                        <div style="display:flex; justify-content:space-between; gap: 10px; align-items:flex-start;">
                            <span style="font-weight:700; color:#0F172A; font-size:14px; line-height:1.35;">${item.title}</span>
                            <span style="font-size:11px; color:#64748B; white-space:nowrap;">${createdAt}</span>
                        </div>
                        <span style="font-size:13px; color:#475569; line-height:1.6;">${item.message}</span>
                    </button>
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
                        btn.classList.remove("topbar__notif-item-unread");
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
