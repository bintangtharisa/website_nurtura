{{-- views/father/profile.blade.php --}}
@extends('father.layout')

@section('title', 'Profil Bapak')

@section('content')

{{-- PAGE HEADER --}}
<div style="margin-bottom: 20px;">
    <h1 style="font-family: var(--font-display); font-size: 20px; font-weight: 600; color: var(--clr-text-heading); margin: 0; line-height: 1.2;">
        Profil Bapak
    </h1>
    <p style="font-size: 12.5px; color: var(--clr-text-muted); margin-top: 3px;">
        Kelola informasi pribadi dan preferensi notifikasi pendampingan Anda.
    </p>
</div>

{{-- Layout Stack (Vertikal) --}}
<div style="display: flex; flex-direction: column; gap: 18px;">

    {{-- BAGIAN ATAS: DATA DIRI --}}
    <div class="card">
        <div class="card__header">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; background: var(--clr-primary-light); color: var(--clr-primary); border-radius: 6px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </span>
                <div class="card__title">Data Diri</div>
            </div>
        </div>
        <div class="card__body" style="padding: 20px;">
            <div style="display: flex; gap: 24px; align-items: center;">
                {{-- Avatar --}}
                <div style="position: relative; flex-shrink: 0;">
                    <div id="profileAvatar" style="width: 80px; height: 80px; border-radius: 50%; background: var(--clr-bg); border: 2.5px solid var(--clr-border-light); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <span id="avatarInitial" style="font-size: 28px; font-weight: 600; color: var(--clr-primary);">B</span>
                    </div>
                    <button type="button" style="position: absolute; bottom: 0; right: 0; width: 26px; height: 26px; border-radius: 50%; background: var(--clr-primary); color: white; border: 2px solid white; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                </div>

                {{-- Form Fields --}}
                <form id="fatherProfileForm" style="flex: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 11px; color: var(--clr-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Username</label>
                        <input id="profileUsername" name="username" type="text" placeholder="Masukkan username" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid var(--clr-border-light); font-size: 13px; color: var(--clr-text-heading); outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; color: var(--clr-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Email</label>
                        <div id="profileEmail" style="width: 100%; min-height: 42px; padding: 10px 14px; border-radius: 8px; border: 1.5px solid var(--clr-border-light); font-size: 13px; color: var(--clr-text-heading); background: #F8F9FA; display: flex; align-items: center;">
                            Loading...
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; color: var(--clr-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Password Lama</label>
                        <input id="profileOldPassword" name="old_password" type="password" placeholder="••••••••" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid var(--clr-border-light); font-size: 13px; color: var(--clr-text-heading); outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; color: var(--clr-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Password Baru</label>
                        <input id="profileNewPassword" name="new_password" type="password" placeholder="••••••••" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid var(--clr-border-light); font-size: 13px; color: var(--clr-text-heading); outline: none;">
                    </div>
                    <div style="grid-column: span 2; display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                        <button type="button" id="cancelProfile" class="btn btn--outline" style="padding: 8px 20px; font-size: 13px;">Batalkan</button>
                        <button type="submit" class="btn btn--primary" style="padding: 8px 20px; font-size: 13px;">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- BAGIAN BAWAH: STATUS KONEKSI --}}
    <div class="card">
        <div class="card__header" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="card__title">Status Koneksi</div>
            <span id="connectionBadge" style="font-size: 10px; font-weight: 700; background: #F3F4F6; color: #6B7280; padding: 4px 10px; border-radius: 20px;">MEMUAT</span>
        </div>
        <div class="card__body" style="padding: 0 15px 18px;">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap;">
                
                {{-- Profil Istri --}}
                <div style="display: flex; align-items: center; gap: 10px; min-width: 210px;">
                    <div style="width: 44px; height: 44px; border-radius: 50%; background: var(--clr-primary-light); overflow: hidden; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                        <span id="connectedMotherInitial" style="font-weight: 600; color: var(--clr-primary);">-</span>
                    </div>
                    <div>
                        <div id="connectedMotherName" style="font-size: 14px; font-weight: 600; color: var(--clr-text-heading);">Memuat...</div>
                        <div id="connectedMotherMeta" style="font-size: 11px; color: var(--clr-text-muted); margin-top: 2px;">Memeriksa relasi di database</div>
                    </div>
                </div>

                {{-- Indikator Fitur --}}
                <div style="display: flex; gap: 8px; flex: 1; min-width: 220px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 7px; font-size: 11.5px; color: var(--clr-text-heading); background: var(--clr-bg); padding: 7px 11px; border-radius: 6px; border: 1px solid var(--clr-border-light);">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        Monitoring Aktif
                    </div>
                    <div style="display: flex; align-items: center; gap: 7px; font-size: 11.5px; color: var(--clr-text-heading); background: var(--clr-bg); padding: 7px 11px; border-radius: 6px; border: 1px solid var(--clr-border-light);">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                        Relasi Aktif
                    </div>
                </div>

                {{-- Warning Ringkas --}}
                <div style="padding: 8px 11px; background: #FFF4E5; border-radius: 8px; border-left: 3px solid #FF9800; max-width: 260px;">
                    <p style="font-size: 10px; color: #856404; margin: 0; line-height: 1.3;">
                        <b>Peringatan:</b> Update harian akan berhenti jika koneksi diputus.
                    </p>
                </div>

            </div>
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

        const form = document.getElementById('fatherProfileForm');
        const btnCancel = document.getElementById('cancelProfile');

        form.addEventListener('submit', handleSubmit);
        btnCancel.addEventListener('click', function () {
            form.reset();
        });

        fetch('/api/profile', {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.status && data.data) {
                const profile = data.data;
                const username = profile.username || '';
                const email = profile.email || '';
                const photo = profile.photo || null;

                document.getElementById('profileUsername').value = username;
                document.getElementById('profileEmail').textContent = maskEmail(email);
                document.getElementById('avatarInitial').innerText = (username || email || 'B').charAt(0).toUpperCase();
                renderConnectionStatus(profile.connection || null);

                if (photo) {
                    document.getElementById('profileAvatar').innerHTML = `<img src="${photo.startsWith('http') ? photo : '/storage/' + photo}" style="width:100%; height:100%; object-fit:cover;" alt="Avatar">`;
                }
            }
        })
        .catch(err => {
            console.error('Gagal memuat profil:', err);
        });
    });

    function renderConnectionStatus(connection) {
        const badge = document.getElementById('connectionBadge');
        const initial = document.getElementById('connectedMotherInitial');
        const name = document.getElementById('connectedMotherName');
        const meta = document.getElementById('connectedMotherMeta');

        if (!connection || !connection.is_connected || !connection.mother) {
            badge.textContent = 'BELUM TERHUBUNG';
            badge.style.background = '#F3F4F6';
            badge.style.color = '#6B7280';
            initial.textContent = '-';
            name.textContent = 'Belum ada koneksi ibu';
            meta.textContent = 'Hubungkan akun dengan anonymous ID ibu';
            return;
        }

        const motherName = connection.mother.username || 'Ibu';
        const anonymousId = connection.mother.anonymous_id || '-';
        const connectedAt = formatConnectionDate(connection.connected_at);

        badge.textContent = 'TERHUBUNG';
        badge.style.background = '#E6F4EA';
        badge.style.color = '#2E7D32';
        initial.textContent = motherName.charAt(0).toUpperCase();
        name.textContent = motherName;
        meta.textContent = `ID ${anonymousId} - Terhubung sejak ${connectedAt}`;
    }

    function formatConnectionDate(value) {
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) {
            return '-';
        }

        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function maskEmail(email) {
        if (!email || email.indexOf('@') === -1) {
            return email;
        }

        const [local, domain] = email.split('@');
        if (local.length <= 2) {
            return local[0] + '*@' + domain;
        }

        const firstChar = local[0];
        const lastChar = local[local.length - 1];
        const maskedMiddle = '*'.repeat(Math.max(1, local.length - 2));
        return `${firstChar}${maskedMiddle}${lastChar}@${domain}`;
    }

    function handleSubmit(event) {
        event.preventDefault();

        const token = localStorage.getItem('token');
        if (!token) {
            window.location.href = '/login';
            return;
        }

        const oldPassword = document.getElementById('profileOldPassword').value.trim();
        const newPassword = document.getElementById('profileNewPassword').value.trim();

        if (oldPassword && newPassword) {
            changePassword(token);
        } else {
            updateProfile(token);
        }
    }

    function updateProfile(token) {
        const username = document.getElementById('profileUsername').value.trim();
        const oldPassword = document.getElementById('profileOldPassword').value.trim();

        if (!username) {
            alert('Username tidak boleh kosong.');
            return;
        }

        const payload = {
            username
        };

        if (oldPassword) {
            payload.old_password = oldPassword;
        }

        fetch('/api/profile', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok || !data.status) {
                throw new Error(data.message || 'Gagal memperbarui profil.');
            }
            alert('Profil berhasil diperbarui!');
            window.location.reload();
        })
        .catch(error => {
            alert(error.message || 'Terjadi kesalahan saat memperbarui profil.');
        });
    }

    function changePassword(token) {
        const oldPassword = document.getElementById('profileOldPassword').value.trim();
        const newPassword = document.getElementById('profileNewPassword').value.trim();

        if (!oldPassword || !newPassword) {
            alert('Password lama dan password baru harus diisi untuk mengganti password.');
            return;
        }

        fetch('/api/change-password', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                old_password: oldPassword,
                new_password: newPassword
            })
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok || !data.status) {
                throw new Error(data.message || 'Gagal mengganti password.');
            }
            alert('Password berhasil diubah, silakan login ulang.');
            localStorage.removeItem('token');
            window.location.href = '/login';
        })
        .catch(error => {
            alert(error.message || 'Terjadi kesalahan saat mengganti password.');
        });
    }
</script>
@endpush
