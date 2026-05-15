@extends('admin.layout') {{-- Sesuaikan dengan nama file layout utama kamu --}}

@section('title', 'Profil Admin - Nurtura Family')
{{-- Dikosongkan agar judulnya muncul di dalam halaman (seperti halaman Data Skrining) --}}
@section('page_title', '') 

@push('styles')
<style>
  /* =========================================================
     CSS KHUSUS HALAMAN PROFIL
     ========================================================= */
     
  /* Header Halaman (Menyamakan dengan Dashboard/Skrining) */
  .page-header-custom {
    margin-bottom: 24px;
  }
  .page-header-custom h2 {
    font-size: 24px;
    font-family: 'Lora', serif;
    font-weight: 600;
    color: #1E293B;
    margin: 0 0 6px 0;
  }
  .page-header-custom p {
    font-size: 14px;
    color: #64748B;
    margin: 0;
  }

  /* Grid Layout */
  .p-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 24px;
    align-items: stretch; /* Memastikan tinggi card kiri & kanan sama */
  }

  /* Card Umum (Disamakan tampilannya) */
  .p-card {
    background: #FFFFFF;
    border-radius: var(--radius-card, 16px);
    padding: 32px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    border: 1px solid #F1F5F9;
    display: flex;
    flex-direction: column;
  }

  .p-card-title {
    font-size: 16px;
    font-weight: 600;
    color: #1E293B;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid #F1F5F9;
    padding-bottom: 16px;
  }

  /* Bagian Kiri: Identitas Profile */
  .p-identity {
    align-items: center;
    justify-content: center;
    text-align: center;
  }
  .p-avatar-large {
    width: 90px;
    height: 90px;
    background: #A3B18A;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    font-weight: 600;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(163, 177, 138, 0.2);
  }
  .p-identity h3 {
    font-size: 20px;
    font-weight: 600;
    color: #1E293B;
    margin: 0 0 6px 0;
  }
  .p-identity p {
    font-size: 14px;
    color: #64748B;
    margin: 0;
  }
  .p-badge {
    background: #F8FAFC;
    color: #475569;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 20px;
    margin-top: 16px;
    border: 1px solid #E2E8F0;
  }

  /* Form Styling */
  .p-form-group {
    margin-bottom: 20px;
  }
  .p-form-label {
    display: block;
    font-size: 13px;
    color: #475569;
    margin-bottom: 8px;
    font-weight: 600;
  }
  .p-form-control {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    font-size: 14px;
    color: #1E293B;
    background: #F8FAFC;
    outline: none;
    transition: all 0.2s;
    box-sizing: border-box;
  }
  .p-form-control:focus {
    border-color: #A3B18A;
    background: #FFFFFF;
    box-shadow: 0 0 0 3px rgba(163, 177, 138, 0.1);
  }
  .p-form-helper {
    font-size: 12px;
    color: #94A3B8;
    margin-top: 6px;
    display: block;
  }

  /* Input Group untuk Toggle Password */
  .p-input-password-wrapper {
    position: relative;
    display: flex;
    align-items: center;
  }
  .p-input-password-wrapper input {
    padding-right: 45px; /* Memberi ruang agar teks tidak tertutup icon */
  }
  .btn-toggle-password {
    position: absolute;
    right: 12px;
    background: transparent;
    border: none;
    color: #94A3B8;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    transition: color 0.2s;
  }
  .btn-toggle-password:hover {
    color: #475569;
  }

  /* Tombol Simpan */
  .btn-save {
    background: #A3B18A;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }
  .btn-save:hover {
    background: #8E9C76;
  }

  /* Responsif */
  @media (max-width: 768px) {
    .p-grid { grid-template-columns: 1fr; }
  }
</style>
@endpush

@section('content')

{{-- ── Header Halaman ── --}}
<div class="page-header-custom">
  <h2>Pengaturan Profil</h2>
  <p>Kelola informasi personal dan pengaturan keamanan akun admin Anda.</p>
</div>

{{-- ── Grid Utama Profil ── --}}
<div class="p-grid">

  {{-- KOLOM KIRI (Ringkasan Profil yang disamakan dengan kanan) --}}
  <div class="p-card p-identity">
    <div class="p-avatar-large" id="largeAvatar">U</div>
    <h3 id="cardUsername">Memuat...</h3>
    <p id="cardEmail">Memuat...</p>
    <div class="p-badge" id="cardId">ID: -</div>
  </div>

  {{-- KOLOM KANAN (Form Edit Profil) --}}
  <div class="p-card">
    <div class="p-card-title">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      Informasi Personal & Keamanan
    </div>

    {{-- Form Edit JWT --}}
    <form id="formProfile" onsubmit="handleSubmit(event)">
      
      <div class="p-form-group">
        <label class="p-form-label" for="profileUsername">Username Admin</label>
        <input type="text" id="profileUsername" class="p-form-control" required placeholder="Masukkan username">
      </div>

      <div class="p-form-group">
        <label class="p-form-label" for="profileEmail">Alamat Email</label>
        <div id="profileEmail" class="p-form-control" style="background: #F8FAFC; cursor: not-allowed;">Memuat...</div>
      </div>

      {{-- PASSWORD LAMA (Dengan Toggle) --}}
      <div class="p-form-group">
        <label class="p-form-label" for="profilePasswordLama">Password Lama</label>
        <div class="p-input-password-wrapper">
          <input type="password" id="profilePasswordLama" class="p-form-control" placeholder="••••••••">
          <button type="button" class="btn-toggle-password" onclick="togglePassword('profilePasswordLama', this)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-eye">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
          </button>
        </div>
        <span class="p-form-helper">Wajib diisi jika Anda ingin mengubah password. Biarkan kosong jika tidak mengubah password.</span>
      </div>

      {{-- PASSWORD BARU (Dengan Toggle) --}}
      <div class="p-form-group">
        <label class="p-form-label" for="profilePasswordBaru">Password Baru</label>
        <div class="p-input-password-wrapper">
          <input type="password" id="profilePasswordBaru" class="p-form-control" placeholder="••••••••">
          <button type="button" class="btn-toggle-password" onclick="togglePassword('profilePasswordBaru', this)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-eye">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
          </button>
        </div>
      </div>

      <div class="p-form-group" style="margin-top: 32px; margin-bottom: 0;">
        <button type="submit" class="btn-save" id="btnSubmit">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <polyline points="17 21 17 13 7 13 7 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <polyline points="7 3 7 8 15 8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span>Simpan Perubahan</span>
        </button>
      </div>

    </form>
  </div>

</div>
@endsection

@push('scripts')
<script>
// --- FUNGSI TOGGLE SHOW/HIDE PASSWORD ---
function togglePassword(inputId, btnElement) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
        // Ubah icon jadi mata tercoret (eye-off)
        btnElement.innerHTML = `
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
            <line x1="1" y1="1" x2="23" y2="23"></line>
          </svg>`;
    } else {
        input.type = "password";
        // Ubah kembali jadi mata terbuka (eye)
        btnElement.innerHTML = `
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
          </svg>`;
    }
}

let adminOriginalUsername = '';

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

document.addEventListener("DOMContentLoaded", function () {
    const token = localStorage.getItem("token");

    if (!token) return;

    // 1. AMBIL DATA UNTUK MENGISI FORM
    fetch("/api/profile", {
        headers: {
            "Authorization": "Bearer " + token,
            "Accept": "application/json"
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data && data.data) {
            const username = data.data.username || '';
            const email = data.data.email || '';

            adminOriginalUsername = username;

            document.getElementById("largeAvatar").innerText = username.charAt(0).toUpperCase();
            document.getElementById("cardUsername").innerText = username;
            document.getElementById("cardEmail").innerText = maskEmail(email);
            document.getElementById("cardId").innerText = "ID: " + data.data.id;
            document.getElementById("profileEmail").textContent = maskEmail(email);
            document.getElementById("profileUsername").value = username;
        }
    })
    .catch(err => console.error("Gagal mengambil data profil:", err));
});

function handleSubmit(e) {
    e.preventDefault();

    const passwordLama = document.getElementById("profilePasswordLama").value.trim();
    const passwordBaru = document.getElementById("profilePasswordBaru").value.trim();

    if (passwordLama && passwordBaru) {
        changePassword(e);
    } else {
        updateProfile(e);
    }
}

// 2. FUNGSI UNTUK MENYIMPAN PERUBAHAN
function updateProfile(e) {
    e.preventDefault(); 
    
    const token = localStorage.getItem("token");
    const btn = document.getElementById("btnSubmit");
    
    const username = document.getElementById("profileUsername").value.trim();
    const passwordLama = document.getElementById("profilePasswordLama").value.trim();

    if (!username) {
        alert("Username tidak boleh kosong.");
        return;
    }

    const payload = {
        username: username
    };

    if (username !== adminOriginalUsername && passwordLama === "") {
        alert("Password lama wajib diisi untuk mengubah username.");
        return;
    }

    if (passwordLama !== "") {
        payload.old_password = passwordLama;
    }

    btn.disabled = true;
    btn.innerText = "Menyimpan...";

    fetch("/api/profile", {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token,
            "Accept": "application/json"
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status) {
            alert("Profil berhasil diperbarui!");
            location.reload();
        } else {
            alert(data.message || "Gagal memperbarui profil.");
        }
    })
    .catch(() => alert("Terjadi kesalahan"))
    .finally(() => {
        btn.disabled = false;
        btn.innerText = "Simpan Perubahan";
    });
}

function changePassword(e) {
    e.preventDefault();

    const token = localStorage.getItem("token");

    const oldPassword = document.getElementById("profilePasswordLama").value;
    const newPassword = document.getElementById("profilePasswordBaru").value;

    if (!oldPassword || !newPassword) {
        alert("Password lama dan password baru wajib diisi");
        return;
    }

    const payload = {
        old_password: oldPassword,
        new_password: newPassword
    };

    fetch("/api/change-password", {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token,
            "Accept": "application/json"
        },
        body: JSON.stringify(payload)
    })
    .then(async (res) => {
        const data = await res.json();

        if (res.ok) {
            alert("Password berhasil diubah, silakan login ulang");
            localStorage.removeItem("token");
            window.location.href = "/login";
        } else {
            alert(data.message || "Gagal mengubah password");
        }
    })
    .catch(err => {
        console.error(err);
        alert("Terjadi kesalahan jaringan");
    });
}
</script>
@endpush