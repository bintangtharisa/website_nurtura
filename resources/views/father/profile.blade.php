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
                    <button type="button" id="uploadPhotoButton" aria-label="Upload foto profil" style="position: absolute; bottom: 0; right: 0; width: 26px; height: 26px; border-radius: 50%; background: var(--clr-primary); color: white; border: 2px solid white; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                    <input type="file" id="profilePhotoInput" accept="image/jpeg,image/png,image/webp" style="display: none;">
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

<div id="photoPreviewModal" class="photo-preview" aria-hidden="true">
    <div class="photo-preview__dialog" role="dialog" aria-modal="true" aria-labelledby="photoPreviewTitle">
        <div class="photo-preview__header">
            <div>
                <h2 id="photoPreviewTitle" class="photo-preview__title">Preview Foto Profil</h2>
                <p class="photo-preview__subtitle">Geser dan ubah ukuran kotak crop sebelum diunggah.</p>
            </div>
            <button type="button" id="closePhotoPreview" class="photo-preview__icon-btn" aria-label="Tutup preview">&times;</button>
        </div>

        <div class="photo-preview__body">
            <div id="photoCropStage" class="photo-preview__crop-stage">
                <img id="photoCropImage" alt="Preview foto profil">
                <div id="photoCropBox" class="photo-preview__crop-box">
                    <span class="photo-preview__handle photo-preview__handle--nw" data-handle="nw"></span>
                    <span class="photo-preview__handle photo-preview__handle--n" data-handle="n"></span>
                    <span class="photo-preview__handle photo-preview__handle--ne" data-handle="ne"></span>
                    <span class="photo-preview__handle photo-preview__handle--e" data-handle="e"></span>
                    <span class="photo-preview__handle photo-preview__handle--se" data-handle="se"></span>
                    <span class="photo-preview__handle photo-preview__handle--s" data-handle="s"></span>
                    <span class="photo-preview__handle photo-preview__handle--sw" data-handle="sw"></span>
                    <span class="photo-preview__handle photo-preview__handle--w" data-handle="w"></span>
                </div>
            </div>
        </div>

        <div class="photo-preview__footer">
            <button type="button" id="cancelPhotoUpload" class="btn btn--outline">Batalkan</button>
            <button type="button" id="confirmPhotoUpload" class="btn btn--primary">Upload Foto</button>
        </div>
    </div>
</div>

<div id="profileToast" class="profile-toast" role="status" aria-live="polite"></div>

@endsection

@push('styles')
<style>
    .photo-preview {
        position: fixed;
        inset: 0;
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 18px;
        background: rgba(17, 24, 39, 0.48);
    }

    .photo-preview.is-open {
        display: flex;
    }

    .photo-preview__dialog {
        width: min(680px, 100%);
        max-height: calc(100vh - 36px);
        overflow: auto;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.22);
    }

    .photo-preview__header,
    .photo-preview__footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 18px;
        border-bottom: 1px solid var(--clr-border-light);
    }

    .photo-preview__footer {
        justify-content: flex-end;
        border-top: 1px solid var(--clr-border-light);
        border-bottom: 0;
    }

    .photo-preview__title {
        margin: 0;
        color: var(--clr-text-heading);
        font-family: var(--font-display);
        font-size: 17px;
        font-weight: 600;
    }

    .photo-preview__subtitle {
        margin: 3px 0 0;
        color: var(--clr-text-muted);
        font-size: 12px;
    }

    .photo-preview__icon-btn {
        width: 32px;
        height: 32px;
        border: 0;
        border-radius: 50%;
        background: var(--clr-bg);
        color: var(--clr-text-heading);
        cursor: pointer;
        font-size: 24px;
        line-height: 1;
    }

    .photo-preview__body {
        padding: 18px;
    }

    .photo-preview__crop-stage {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 360px;
        border: 1px solid var(--clr-border-light);
        border-radius: 8px;
        background: #111827;
        overflow: hidden;
        touch-action: none;
        user-select: none;
    }

    #photoCropImage {
        display: block;
        max-width: 100%;
        max-height: 520px;
        object-fit: contain;
        pointer-events: none;
    }

    .photo-preview__crop-box {
        position: absolute;
        width: 220px;
        height: 220px;
        border: 2px solid #fff;
        box-shadow: 0 0 0 9999px rgba(17, 24, 39, 0.52);
        cursor: move;
        touch-action: none;
    }

    .photo-preview__crop-box::before,
    .photo-preview__crop-box::after {
        content: "";
        position: absolute;
        inset: 33.333%;
        border-color: rgba(255, 255, 255, 0.72);
        border-style: solid;
        pointer-events: none;
    }

    .photo-preview__crop-box::before {
        border-width: 0 1px;
        inset-block: 0;
    }

    .photo-preview__crop-box::after {
        border-width: 1px 0;
        inset-inline: 0;
    }

    .photo-preview__handle {
        position: absolute;
        width: 14px;
        height: 14px;
        border: 2px solid var(--clr-primary);
        border-radius: 3px;
        background: #fff;
        box-shadow: 0 1px 5px rgba(15, 23, 42, 0.25);
        z-index: 2;
    }

    .photo-preview__handle--nw { top: -8px; left: -8px; cursor: nwse-resize; }
    .photo-preview__handle--n { top: -8px; left: 50%; transform: translateX(-50%); cursor: ns-resize; }
    .photo-preview__handle--ne { top: -8px; right: -8px; cursor: nesw-resize; }
    .photo-preview__handle--e { top: 50%; right: -8px; transform: translateY(-50%); cursor: ew-resize; }
    .photo-preview__handle--se { right: -8px; bottom: -8px; cursor: nwse-resize; }
    .photo-preview__handle--s { bottom: -8px; left: 50%; transform: translateX(-50%); cursor: ns-resize; }
    .photo-preview__handle--sw { bottom: -8px; left: -8px; cursor: nesw-resize; }
    .photo-preview__handle--w { top: 50%; left: -8px; transform: translateY(-50%); cursor: ew-resize; }

    .profile-toast {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 1100;
        display: none;
        max-width: min(360px, calc(100vw - 40px));
        padding: 12px 14px;
        border-radius: 8px;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.35;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.2);
        transform: translateY(10px);
        opacity: 0;
        transition: opacity 0.2s ease, transform 0.2s ease;
    }

    .profile-toast.is-visible {
        display: block;
        transform: translateY(0);
        opacity: 1;
    }

    .profile-toast--success {
        background: #2E7D32;
    }

    .profile-toast--error {
        background: #B42318;
    }

    @media (max-width: 680px) {
        .photo-preview__crop-stage {
            min-height: 300px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    const photoUploadState = {
        token: null,
        file: null,
        image: null,
        objectUrl: null,
        crop: { x: 0, y: 0, width: 0, height: 0 },
        imageBounds: { x: 0, y: 0, width: 0, height: 0 },
        drag: null,
        toastTimer: null,
        isUploading: false
    };

    document.addEventListener('DOMContentLoaded', function () {
        const token = localStorage.getItem('token');
        if (!token) {
            window.location.href = '/login';
            return;
        }

        photoUploadState.token = token;

        const form = document.getElementById('fatherProfileForm');
        const btnCancel = document.getElementById('cancelProfile');
        const uploadPhotoButton = document.getElementById('uploadPhotoButton');
        const profilePhotoInput = document.getElementById('profilePhotoInput');
        const closePhotoPreview = document.getElementById('closePhotoPreview');
        const cancelPhotoUpload = document.getElementById('cancelPhotoUpload');
        const confirmPhotoUpload = document.getElementById('confirmPhotoUpload');
        const cropStage = document.getElementById('photoCropStage');
        const cropBox = document.getElementById('photoCropBox');

        form.addEventListener('submit', handleSubmit);
        uploadPhotoButton.addEventListener('click', function () {
            profilePhotoInput.click();
        });
        profilePhotoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                openPhotoPreview(this.files[0]);
            }
        });
        closePhotoPreview.addEventListener('click', closePhotoPreviewModal);
        cancelPhotoUpload.addEventListener('click', closePhotoPreviewModal);
        confirmPhotoUpload.addEventListener('click', uploadPreviewedPhoto);
        cropStage.addEventListener('pointerdown', startPhotoCropInteraction);
        cropStage.addEventListener('pointermove', movePhotoCropInteraction);
        cropStage.addEventListener('pointerup', endPhotoCropInteraction);
        cropStage.addEventListener('pointercancel', endPhotoCropInteraction);
        cropBox.addEventListener('dragstart', function (event) {
            event.preventDefault();
        });
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
                    renderProfileAvatar(photo, username, email);
                }
            }
        })
        .catch(err => {
            console.error('Gagal memuat profil:', err);
        });
    });

    function renderProfileAvatar(photo, username = '', email = '') {
        const avatar = document.getElementById('profileAvatar');

        if (photo) {
            const src = photo.startsWith('http') ? photo : '/storage/' + photo;
            avatar.innerHTML = `<img src="${src}" style="width:100%; height:100%; object-fit:cover;" alt="Avatar">`;
            return;
        }

        avatar.innerHTML = `<span id="avatarInitial" style="font-size: 28px; font-weight: 600; color: var(--clr-primary);">${(username || email || 'B').charAt(0).toUpperCase()}</span>`;
    }

    function openPhotoPreview(file) {
        if (!validatePhotoFile(file)) {
            resetPhotoInput();
            return;
        }

        const image = new Image();
        const objectUrl = URL.createObjectURL(file);

        image.onload = function () {
            clearPhotoObjectUrl();
            photoUploadState.file = file;
            photoUploadState.image = image;
            photoUploadState.objectUrl = objectUrl;

            document.getElementById('photoPreviewModal').classList.add('is-open');
            document.getElementById('photoPreviewModal').setAttribute('aria-hidden', 'false');
            document.getElementById('photoCropImage').src = objectUrl;
            requestAnimationFrame(initializePhotoCrop);
        };

        image.onerror = function () {
            URL.revokeObjectURL(objectUrl);
            resetPhotoInput();
            showProfileToast('Foto profil tidak bisa dibaca.', 'error');
        };

        image.src = objectUrl;
    }

    function validatePhotoFile(file) {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        const maxSize = 2 * 1024 * 1024;

        if (!allowedTypes.includes(file.type)) {
            showProfileToast('Foto profil harus berupa JPG, PNG, atau WEBP.', 'error');
            return false;
        }

        if (file.size > maxSize) {
            showProfileToast('Ukuran foto profil maksimal 2MB.', 'error');
            return false;
        }

        return true;
    }

    function initializePhotoCrop() {
        const stage = document.getElementById('photoCropStage');
        const image = document.getElementById('photoCropImage');
        const stageRect = stage.getBoundingClientRect();
        const imageRect = image.getBoundingClientRect();
        const size = Math.min(imageRect.width, imageRect.height) * 0.72;

        photoUploadState.imageBounds = {
            x: imageRect.left - stageRect.left,
            y: imageRect.top - stageRect.top,
            width: imageRect.width,
            height: imageRect.height
        };

        photoUploadState.crop = {
            x: photoUploadState.imageBounds.x + (photoUploadState.imageBounds.width - size) / 2,
            y: photoUploadState.imageBounds.y + (photoUploadState.imageBounds.height - size) / 2,
            width: size,
            height: size
        };

        renderPhotoCropBox();
    }

    function renderPhotoCropBox() {
        const cropBox = document.getElementById('photoCropBox');
        const crop = photoUploadState.crop;

        cropBox.style.left = crop.x + 'px';
        cropBox.style.top = crop.y + 'px';
        cropBox.style.width = crop.width + 'px';
        cropBox.style.height = crop.height + 'px';
    }

    function startPhotoCropInteraction(event) {
        if (!photoUploadState.image) {
            return;
        }

        const handle = event.target.dataset.handle || null;
        const cropBox = document.getElementById('photoCropBox');

        if (!handle && event.target !== cropBox) {
            return;
        }

        event.preventDefault();
        event.currentTarget.setPointerCapture(event.pointerId);

        photoUploadState.drag = {
            handle: handle || 'move',
            pointerId: event.pointerId,
            startX: event.clientX,
            startY: event.clientY,
            crop: { ...photoUploadState.crop }
        };
    }

    function movePhotoCropInteraction(event) {
        const drag = photoUploadState.drag;

        if (!drag || drag.pointerId !== event.pointerId) {
            return;
        }

        const deltaX = event.clientX - drag.startX;
        const deltaY = event.clientY - drag.startY;

        photoUploadState.crop = drag.handle === 'move'
            ? movePhotoCrop(drag.crop, deltaX, deltaY)
            : resizePhotoCrop(drag.crop, drag.handle, deltaX, deltaY);

        renderPhotoCropBox();
    }

    function endPhotoCropInteraction(event) {
        if (!photoUploadState.drag || photoUploadState.drag.pointerId !== event.pointerId) {
            return;
        }

        photoUploadState.drag = null;
        event.currentTarget.releasePointerCapture(event.pointerId);
    }

    function movePhotoCrop(crop, deltaX, deltaY) {
        const bounds = photoUploadState.imageBounds;

        return {
            ...crop,
            x: clamp(crop.x + deltaX, bounds.x, bounds.x + bounds.width - crop.width),
            y: clamp(crop.y + deltaY, bounds.y, bounds.y + bounds.height - crop.height)
        };
    }

    function resizePhotoCrop(crop, handle, deltaX, deltaY) {
        const bounds = photoUploadState.imageBounds;
        const minSize = 80;
        let left = crop.x;
        let top = crop.y;
        let right = crop.x + crop.width;
        let bottom = crop.y + crop.height;

        if (handle.includes('w')) {
            left = clamp(crop.x + deltaX, bounds.x, right - minSize);
        }

        if (handle.includes('e')) {
            right = clamp(crop.x + crop.width + deltaX, left + minSize, bounds.x + bounds.width);
        }

        if (handle.includes('n')) {
            top = clamp(crop.y + deltaY, bounds.y, bottom - minSize);
        }

        if (handle.includes('s')) {
            bottom = clamp(crop.y + crop.height + deltaY, top + minSize, bounds.y + bounds.height);
        }

        return {
            x: left,
            y: top,
            width: right - left,
            height: bottom - top
        };
    }

    function clamp(value, min, max) {
        return Math.min(max, Math.max(min, value));
    }

    function uploadPreviewedPhoto() {
        if (photoUploadState.isUploading || !photoUploadState.image) {
            return;
        }

        const confirmButton = document.getElementById('confirmPhotoUpload');
        const uploadButton = document.getElementById('uploadPhotoButton');

        setPhotoUploading(true, confirmButton, uploadButton);

        createCroppedPhotoBlob(function (blob) {
            if (!blob) {
                setPhotoUploading(false, confirmButton, uploadButton);
                closePhotoPreviewModal(true);
                showProfileToast('Gagal membuat crop foto.', 'error');
                return;
            }

            const resizedFile = new File([blob], 'profile-photo.jpg', { type: 'image/jpeg' });
            uploadProfilePhoto(photoUploadState.token, resizedFile)
                .then(function () {
                    closePhotoPreviewModal(true);
                    showProfileToast('Foto profil berhasil diperbarui.', 'success');
                })
                .catch(function (error) {
                    closePhotoPreviewModal(true);
                    showProfileToast(error.message || 'Terjadi kesalahan saat mengunggah foto profil.', 'error');
                })
                .finally(function () {
                    setPhotoUploading(false, confirmButton, uploadButton);
                });
        });
    }

    function createCroppedPhotoBlob(callback) {
        const image = photoUploadState.image;
        const crop = photoUploadState.crop;
        const bounds = photoUploadState.imageBounds;
        const scaleX = image.naturalWidth / bounds.width;
        const scaleY = image.naturalHeight / bounds.height;
        const sourceX = (crop.x - bounds.x) * scaleX;
        const sourceY = (crop.y - bounds.y) * scaleY;
        const sourceWidth = crop.width * scaleX;
        const sourceHeight = crop.height * scaleY;
        const maxOutputSize = 1024;
        const outputScale = Math.min(1, maxOutputSize / Math.max(sourceWidth, sourceHeight));
        const outputWidth = Math.max(128, Math.round(sourceWidth * outputScale));
        const outputHeight = Math.max(128, Math.round(sourceHeight * outputScale));
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        canvas.width = outputWidth;
        canvas.height = outputHeight;
        context.drawImage(image, sourceX, sourceY, sourceWidth, sourceHeight, 0, 0, outputWidth, outputHeight);
        canvas.toBlob(callback, 'image/jpeg', 0.9);
    }

    function uploadProfilePhoto(token, file) {
        const formData = new FormData();
        formData.append('photo', file);

        return fetch('/api/profile/photo', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok || !data.status) {
                const firstError = data.errors ? Object.values(data.errors).flat()[0] : null;
                throw new Error(firstError || data.message || 'Gagal memperbarui foto profil.');
            }

            const profile = data.data || {};
            renderProfileAvatar(profile.photo || null, profile.username || '', profile.email || '');
        });
    }

    function setPhotoUploading(isUploading, confirmButton, uploadButton) {
        photoUploadState.isUploading = isUploading;
        confirmButton.disabled = isUploading;
        uploadButton.disabled = isUploading;
        confirmButton.textContent = isUploading ? 'Mengunggah...' : 'Upload Foto';
        uploadButton.style.opacity = isUploading ? '0.65' : '1';
    }

    function closePhotoPreviewModal(force = false) {
        if (photoUploadState.isUploading && !force) {
            return;
        }

        photoUploadState.isUploading = false;
        document.getElementById('photoPreviewModal').classList.remove('is-open');
        document.getElementById('photoPreviewModal').setAttribute('aria-hidden', 'true');
        photoUploadState.file = null;
        photoUploadState.image = null;
        clearPhotoObjectUrl();
        resetPhotoInput();
    }

    function clearPhotoObjectUrl() {
        if (photoUploadState.objectUrl) {
            URL.revokeObjectURL(photoUploadState.objectUrl);
            photoUploadState.objectUrl = null;
        }
    }

    function resetPhotoInput() {
        document.getElementById('profilePhotoInput').value = '';
    }

    function showProfileToast(message, type = 'success') {
        const toast = document.getElementById('profileToast');

        if (photoUploadState.toastTimer) {
            clearTimeout(photoUploadState.toastTimer);
        }

        toast.textContent = message;
        toast.className = 'profile-toast profile-toast--' + type;
        toast.style.display = 'block';

        requestAnimationFrame(function () {
            toast.classList.add('is-visible');
        });

        photoUploadState.toastTimer = setTimeout(function () {
            toast.classList.remove('is-visible');

            setTimeout(function () {
                toast.style.display = 'none';
            }, 220);
        }, 2200);
    }

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

    // --- FUNGSI BARU: MEMBUAT DAN MENAMPILKAN CUSTOM ERROR MODAL DI TENGAH LAYAR ---
    function triggerCustomErrorModal(message) {
        let errorModal = document.getElementById('customPasswordErrorModal');
        
        // Jika modal belum ada di halaman, kita generate strukturnya secara dinamis
        if (!errorModal) {
            errorModal = document.createElement('div');
            errorModal.id = 'customPasswordErrorModal';
            // Pengaturan styling dasar agar letaknya melayang sempurna di tengah layar
            errorModal.style = "position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999; opacity: 0; pointer-events: none; transition: opacity 0.2s ease;";
            
            errorModal.innerHTML = `
                <div style="background: #fff; padding: 24px; border-radius: 16px; width: 90%; max-width: 400px; text-align: center; box-shadow: 0 4px 24px rgba(0,0,0,0.2);">
                    <div style="width: 56px; height: 56px; background: #FCE8E6; color: #C5221F; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 26px; font-weight: bold;">!</div>
                    <h3 style="margin: 0 0 8px; font-size: 18px; color: #202124; font-weight: 600;">Pembaruan Gagal</h3>
                    <p id="customErrorModalMessage" style="margin: 0 0 20px; font-size: 14px; color: #5f6368; line-height: 1.5;"></p>
                    <button id="customErrorModalCloseBtn" style="background: #C5221F; color: #fff; border: none; padding: 10px 24px; font-size: 14px; font-weight: 500; border-radius: 8px; cursor: pointer; width: 100%; transition: background 0.2s;">OKE</button>
                </div>
            `;
            document.body.appendChild(errorModal);
            
            // Event listener klik tombol OKE untuk menutup modal error
            document.getElementById('customErrorModalCloseBtn').addEventListener('click', function() {
                errorModal.style.opacity = '0';
                errorModal.style.pointerEvents = 'none';
            });
        }
        
        // Isi pesan error ke dalam modal dan tampilkan di tengah
        document.getElementById('customErrorModalMessage').textContent = message;
        errorModal.style.opacity = '1';
        errorModal.style.pointerEvents = 'auto';
    }

    function handleSubmit(event) {
        event.preventDefault();

        const token = localStorage.getItem('token');
        if (!token) {
            window.location.href = '/login';
            return;
        }

        const username = document.getElementById('profileUsername').value.trim();
        const oldPassword = document.getElementById('profileOldPassword').value.trim();
        const newPassword = document.getElementById('profileNewPassword').value.trim();

        if (!username) {
            triggerCustomErrorModal('Username tidak boleh kosong.');
            return;
        }

        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn ? submitBtn.textContent : 'Simpan';

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
        }

        // KONDISI 1: User mengganti password (dan simpan username)
        if (oldPassword && newPassword) {
            fetch('/api/profile', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ username })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.status) {
                    throw new Error(data.message || 'Gagal memperbarui profil.');
                }
                return fetch('/api/change-password', {
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
                });
            })
            .then(response => response.json())
            .then(data => {
                if (!data.status) {
                    // Memicu catch block apabila respons API menyatakan password salah
                    throw new Error(data.message || 'Password lama salah atau gagal ganti password.');
                }
                
                // NOTIF SUKSES: Menggunakan fungsi bawaan modal sukses kamu
                if (typeof openSuccessModal === 'function') {
                    const successTextEl = document.querySelector('#successModal .modal-text, #successModal p');
                    if (successTextEl) successTextEl.textContent = 'Profil dan password berhasil diubah, silakan login ulang.';
                    
                    openSuccessModal();
                    
                    const successOkBtn = document.getElementById('successModalOkBtn') || document.querySelector('#successModal button');
                    if (successOkBtn) {
                        successOkBtn.addEventListener('click', function() {
                            localStorage.removeItem('token');
                            window.location.href = '/login';
                        });
                    } else {
                        setTimeout(() => {
                            localStorage.removeItem('token');
                            window.location.href = '/login';
                        }, 2000);
                    }
                } else {
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                }
            })
            .catch(error => {
                // NOTIF ERROR: Jika password salah atau gagal, alihkan ke modal tengah dengan tombol OKE
                triggerCustomErrorModal(error.message);

                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
            });
        } 
        // KONDISI 2: Salah satu field password kosong saat mau update
        else if (oldPassword || newPassword) {
            triggerCustomErrorModal('Password lama dan baru harus diisi keduanya!');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
            }
        } 
        // KONDISI 3: Hanya ubah username biasa tanpa ubah password
        else {
            fetch('/api/profile', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ username })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.status) {
                    throw new Error(data.message || 'Gagal memperbarui profil.');
                }
                
                if (typeof openSuccessModal === 'function') {
                    openSuccessModal();
                    const successOkBtn = document.getElementById('successModalOkBtn') || document.querySelector('#successModal button');
                    if (successOkBtn) {
                        successOkBtn.addEventListener('click', () => window.location.reload());
                    } else {
                        setTimeout(() => { window.location.reload(); }, 1500);
                    }
                } else {
                    window.location.reload();
                }
            })
            .catch(error => {
                // NOTIF ERROR: Jika update username gagal, tampilkan modal tengah
                triggerCustomErrorModal(error.message);
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
            });
        }
    }
</script>
@endpush