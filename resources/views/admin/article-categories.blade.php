@extends('admin.layout')

@section('title', 'Manajemen Kategori - Nurtura Family')

{{-- Tambahkan baris ini untuk menghilangkan tulisan default --}}
@section('page_title', '') 

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-header__title">Article Categories</h2>
        <p class="page-header__desc">Kelola kategori artikel untuk mempermudah navigasi konten Nurtura.</p>
    </div>
    <div class="page-header__actions">
        <button class="btn btn--primary" onclick="openModal('create')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 5px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Buat Kategori Baru
        </button>
    </div>
</div>

<div class="table-section" style="margin-top: 20px;">
    <div class="table-section__header">
        <h3 class="table-section__title">DAFTAR KATEGORI</h3>
    </div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 50px;">NO</th>
                <th>NAMA KATEGORI</th>
                <th>SLUG</th>
                <th>STATUS</th>
                <th style="text-align: right;">AKSI</th>
            </tr>
        </thead>
        <tbody id="categoryTableBody">
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px; color: var(--clr-text-muted);">Memuat data...</td>
            </tr>
        </tbody>
    </table>
</div>

{{-- POP-UP MODAL --}}
<div id="categoryModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle" style="font-family: var(--font-display); font-weight: 600;">Tambah Kategori</h3>
            <button onclick="closeModal()" style="font-size: 24px; color: #999; border:none; background:none; cursor:pointer;">&times;</button>
        </div>
        
        <form id="categoryForm" style="margin-top: 20px;">
            <input type="hidden" id="categoryId">
            
            <div class="form-group">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #666; margin-bottom: 5px;">NAMA KATEGORI</label>
                <input type="text" id="name" name="name" placeholder="Contoh: Nutrisi Anak" style="width: 100%; padding: 10px; border: 1px solid var(--clr-border); border-radius: 8px;" required>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #666; margin-bottom: 5px;">SLUG (OTOMATIS)</label>
                <input type="text" id="slug" name="slug" readonly style="width: 100%; padding: 10px; border: 1px solid var(--clr-border); border-radius: 8px; background: #f5f5f5; color: #888;">
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #666; margin-bottom: 5px;">DESKRIPSI</label>
                <textarea id="description" name="description" rows="3" style="width: 100%; padding: 10px; border: 1px solid var(--clr-border); border-radius: 8px; font-family: inherit;"></textarea>
            </div>

            <div style="margin-top: 20px; padding: 15px; background: #f9f9f9; border-radius: 12px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-weight: 600; font-size: 13px; display: block;">Aktifkan Kategori</span>
                    <small style="color: #888;">Munculkan kategori ini di halaman publik.</small>
                </div>
                <input type="checkbox" id="is_active" checked style="width: 20px; height: 20px; accent-color: var(--clr-primary);">
            </div>

            <div style="margin-top: 25px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal()" class="btn btn--outline">Batal</button>
                <button type="submit" id="btnSubmit" class="btn btn--primary">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(2px); }
    .modal-content { background: #fff; width: 100%; max-width: 450px; border-radius: 16px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 15px; }
    .btn--danger { background: #fee2e2; color: #ef4444; border: 1px solid #fecaca; }
    .btn--danger:hover { background: #ef4444; color: #fff; }
</style>
@endsection

@push('scripts')
<script>
    const API_URL = '/api/article-categories'; 
    const token = localStorage.getItem('token');
    let categoryIdToDelete = null; 

    document.addEventListener('DOMContentLoaded', () => {
        initSlugListener();
        if (token) loadData();

        // Handler klik area luar untuk menutup modal kustom delete
        const deleteModal = document.getElementById('deleteConfirmModal');
        if (deleteModal) {
            deleteModal.addEventListener('click', function (event) {
                if (event.target === deleteModal) closeDeleteModal();
            });
        }

        // Handler tombol Escape untuk menutup semua modal kustom
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                if (deleteModal && deleteModal.style.display === 'flex') closeDeleteModal();
            }
        });
    });

    function initSlugListener() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        if (nameInput && slugInput) {
            nameInput.addEventListener('input', function() {
                let slug = this.value.toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .trim()
                    .replace(/ +/g, '-');
                slugInput.value = slug;
            });
        }
    }

    async function loadData() {
        const tbody = document.getElementById('categoryTableBody');
        try {
            const res = await fetch(API_URL, { 
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' } 
            });
            const categories = await res.json();
            
            // PENTING: Deteksi apakah ID ada di '_id' or 'id'
            tbody.innerHTML = categories.map((c, index) => {
                // MongoDB sering ganti-ganti antara _id atau id saat dikirim ke JSON
                const realId = c._id || c.id; 
                
                return `
                    <tr>
                        <td style="color: #999;">${index + 1}</td>
                        <td><strong>${c.name}</strong></td>
                        <td style="font-family:monospace; color:#666;">${c.slug}</td>
                        <td><span class="badge ${c.is_active ? 'badge--low' : 'badge--high'}">AKTIF</span></td>
                        <td style="text-align: right; display: flex; gap: 5px; justify-content: flex-end;">
                            <button onclick="openModal('edit', '${realId}')" class="btn btn--outline" style="padding: 4px 10px;">Edit</button>
                            <button onclick="deleteCategory('${realId}')" class="btn btn--danger" style="padding: 4px 10px;">Hapus</button>
                        </td>
                    </tr>
                `;
            }).join('');
        } catch (err) {
            console.error("Gagal muat tabel:", err);
        }
    }

    async function openModal(mode, id = null) {
        const modal = document.getElementById('categoryModal');
        const form = document.getElementById('categoryForm');
        
        modal.style.display = 'flex';
        form.reset();
        
        // Proteksi: Jika id adalah string "undefined", jangan masukkan ke input
        const cleanId = (id === 'undefined' || !id) ? '' : id;
        document.getElementById('categoryId').value = cleanId;
        document.getElementById('modalTitle').innerText = mode === 'create' ? 'Tambah Kategori' : 'Edit Kategori';

        if (mode === 'edit' && cleanId) {
            try {
                const res = await fetch(`${API_URL}/${cleanId}`, { 
                    headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' } 
                });

                if (!res.ok) throw new Error("Data tidak ditemukan di server");

                const response = await res.json();
                const data = response.data || response;

                if (data) {
                    document.getElementById('name').value = data.name || '';
                    document.getElementById('slug').value = data.slug || '';
                    document.getElementById('description').value = data.description || '';
                    document.getElementById('is_active').checked = !!data.is_active;
                }
            } catch (err) {
                console.error("Detail Error:", err);
                alert("Gagal mengambil data: " + err.message);
            }
        }
    }

    function closeModal() { document.getElementById('categoryModal').style.display = 'none'; }

    document.getElementById('categoryForm').onsubmit = async (e) => {
        e.preventDefault();
        const id = document.getElementById('categoryId').value;
        const method = id ? 'PUT' : 'POST';
        const url = id ? `${API_URL}/${id}` : API_URL;

        const payload = {
            name: document.getElementById('name').value,
            slug: document.getElementById('slug').value,
            description: document.getElementById('description').value,
            is_active: document.getElementById('is_active').checked,
            icon: "folder", 
            color: "#4e73df"
        };

        const res = await fetch(url, {
            method: method,
            headers: { 
                'Content-Type': 'application/json', 
                'Authorization': 'Bearer ' + token, 
                'Accept': 'application/json' 
            },
            body: JSON.stringify(payload)
        });

        if (res.ok) { 
            closeModal(); 
            loadData(); 
        } else { 
            alert("Gagal menyimpan data."); 
        }
    };

    // Fungsi Pengendali Modal Peringatan Hapus Kategori Kustom
    function deleteCategory(id) {
        if (!id || id === 'undefined') return alert("ID tidak valid!");
        categoryIdToDelete = id;
        
        const modal = document.getElementById('deleteConfirmModal');
        if (modal) {
            modal.style.setProperty('display', 'flex', 'important');
        }
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteConfirmModal');
        if (modal) {
            modal.style.setProperty('display', 'none', 'important');
        }
        categoryIdToDelete = null;
    }

    async function executeDeleteCategory() {
        if (!categoryIdToDelete) return;
        
        const res = await fetch(`${API_URL}/${categoryIdToDelete}`, {
            method: 'DELETE',
            headers: { 'Authorization': 'Bearer ' + token }
        });
        
        if (res.ok) {
            closeDeleteModal();
            loadData();
        } else {
            alert("Gagal menghapus kategori.");
        }
    }
</script>
@endpush

{{-- ===== MODAL KONFIRMASI DELETE KATEGORI ===== --}}
<div id="deleteConfirmModal" style="display: none !important; position: fixed !important; top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important; background: rgba(15, 23, 42, 0.6) !important; backdrop-filter: blur(4px) !important; -webkit-backdrop-filter: blur(4px) !important; align-items: center !important; justify-content: center !important; z-index: 99999 !important;">
    <div role="dialog" aria-modal="true" aria-labelledby="deleteConfirmTitle" style="background: #ffffff !important; padding: 32px !important; border-radius: 16px !important; max-width: 400px !important; width: 90% !important; text-align: center !important; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important; z-index: 100000 !important; position: relative !important;">
        <div aria-hidden="true" style="width: 48px !important; height: 48px !important; background: #FEF2F2 !important; color: #EF4444 !important; border-radius: 999px !important; display: flex !important; align-items: center !important; justify-content: center !important; margin: 0 auto 16px !important;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
            </svg>
        </div>
        <h2 id="deleteConfirmTitle" style="font-size: 18px !important; font-weight: 700 !important; color: #0F172A !important; margin: 0 0 8px 0 !important; font-family: 'DM Sans', sans-serif !important;">Hapus Kategori</h2>
        <p style="font-size: 14px !important; color: #64748B !important; margin: 0 0 24px 0 !important; font-family: 'DM Sans', sans-serif !important; line-height: 1.5 !important;">Anda yakin ingin menghapus kategori ini?</p>
        <div style="display: flex !important; gap: 12px !important; justify-content: center !important;">
            <button type="button" onclick="closeDeleteModal()" style="flex: 1 !important; padding: 10px 16px !important; border-radius: 8px !important; border: 1px solid #E2E8F0 !important; background: #ffffff !important; color: #475569 !important; font-weight: 500 !important; cursor: pointer !important; font-family: 'DM Sans', sans-serif !important;">Batal</button>
            <button type="button" onclick="executeDeleteCategory()" style="flex: 1 !important; padding: 10px 16px !important; border-radius: 8px !important; border: none !important; background: #EF4444 !important; color: #ffffff !important; font-weight: 500 !important; cursor: pointer !important; font-family: 'DM Sans', sans-serif !important;">Hapus</button>
        </div>
    </div>
</div>