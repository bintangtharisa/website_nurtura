@extends('admin.layout')

@section('title', 'Manajemen Kategori - Nurtura Family')

@section('page_title')
    <span style="color: var(--clr-text-muted); font-weight: 400;">Dashboard &nbsp;>&nbsp; Article Hub &nbsp;>&nbsp;</span> Categories
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-header__title">Article Categories</h2>
        <p class="page-header__desc">Kelola kategori artikel untuk mempermudah navigasi konten di portal Nurtura.</p>
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
                <th>ICON</th>
                <th>NAMA KATEGORI</th>
                <th>SLUG</th>
                <th>WARNA</th>
                <th>STATUS</th>
                <th style="text-align: right;">AKSI</th>
            </tr>
        </thead>
        <tbody id="categoryTableBody">
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: var(--clr-text-muted);">Memuat data...</td>
            </tr>
        </tbody>
    </table>
</div>

{{-- POP-UP MODAL --}}
<div id="categoryModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle" style="font-family: var(--font-display); font-weight: 600;">Tambah Kategori</h3>
            <button onclick="closeModal()" style="font-size: 24px; color: #999;">&times;</button>
        </div>
        
        <form id="categoryForm" style="margin-top: 20px;">
            <input type="hidden" id="categoryId">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #666; margin-bottom: 5px;">NAMA KATEGORI</label>
                    <input type="text" id="name" name="name" placeholder="Contoh: Nutrisi Anak" style="width: 100%; padding: 10px; border: 1px solid var(--clr-border); border-radius: 8px;" required>
                </div>
                <div class="form-group">
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #666; margin-bottom: 5px;">SLUG</label>
                    <input type="text" id="slug" name="slug" readonly style="width: 100%; padding: 10px; border: 1px solid var(--clr-border); border-radius: 8px; background: #f5f5f5;">
                </div>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #666; margin-bottom: 5px;">DESKRIPSI (OPSIONAL)</label>
                <textarea id="description" name="description" rows="3" style="width: 100%; padding: 10px; border: 1px solid var(--clr-border); border-radius: 8px; font-family: inherit;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                <div class="form-group">
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #666; margin-bottom: 5px;">ICON (EMOJI/TEXT)</label>
                    <input type="text" id="icon" name="icon" placeholder="Contoh: 🍎" style="width: 100%; padding: 10px; border: 1px solid var(--clr-border); border-radius: 8px;">
                </div>
                <div class="form-group">
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #666; margin-bottom: 5px;">WARNA TEMA (HEX)</label>
                    <input type="text" id="color" name="color" value="#A3B18A" placeholder="#A3B18A" style="width: 100%; padding: 10px; border: 1px solid var(--clr-border); border-radius: 8px;">
                </div>
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
                <button type="submit" class="btn btn--primary">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(2px); }
    .modal-content { background: #fff; width: 100%; max-width: 500px; border-radius: 16px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 15px; }
    .icon-box { width: 35px; height: 35px; background: var(--clr-primary-light); color: var(--clr-primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
</style>
@endsection

@push('scripts')
<script>
    const API_URL = '/api/article-categories'; // Samakan dengan route di Laravel
    const token = localStorage.getItem('token');

    document.addEventListener('DOMContentLoaded', loadData);

    // 1. Ambil Data
    async function loadData() {
        const res = await fetch(API_URL, { headers: { 'Authorization': 'Bearer ' + token } });
        const categories = await res.json();
        const tbody = document.getElementById('categoryTableBody');
        
        tbody.innerHTML = categories.map(c => `
            <tr>
                <td><div class="icon-box">${c.icon || '📂'}</div></td>
                <td><strong>${c.name}</strong></td>
                <td class="td-code">${c.slug}</td>
                <td><span style="display:inline-block; width:20px; height:20px; border-radius:4px; background:${c.color}"></span></td>
                <td><span class="badge ${c.is_active ? 'badge--low' : 'badge--high'}">${c.is_active ? 'AKTIF' : 'NONAKTIF'}</span></td>
                <td style="text-align: right;">
                    <button onclick="openModal('edit', ${c.id})" class="btn btn--outline" style="padding: 4px 10px;">Edit</button>
                </td>
            </tr>
        `).join('');
    }

    // 2. Auto-Slug
    document.getElementById('name').addEventListener('input', function() {
        this.nextElementSibling; 
        const slug = this.value.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
        document.getElementById('slug').value = slug;
    });

    // 3. Modal Control
    function openModal(mode, id = null) {
        document.getElementById('categoryModal').style.display = 'flex';
        document.getElementById('categoryForm').reset();
        if (mode === 'create') {
            document.getElementById('modalTitle').innerText = 'Tambah Kategori';
            document.getElementById('categoryId').value = '';
        } else {
            document.getElementById('modalTitle').innerText = 'Edit Kategori';
            // Ambil data satu kategori
            fetch(API_URL + '/' + id, { headers: { 'Authorization': 'Bearer ' + token } })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('categoryId').value = data.id;
                    document.getElementById('name').value = data.name;
                    document.getElementById('slug').value = data.slug;
                    document.getElementById('description').value = data.description;
                    document.getElementById('icon').value = data.icon;
                    document.getElementById('color').value = data.color;
                    document.getElementById('is_active').checked = !!data.is_active;
                });
        }
    }

    function closeModal() { document.getElementById('categoryModal').style.display = 'none'; }

    // 4. Simpan Data (POST/PUT)
    document.getElementById('categoryForm').onsubmit = async (e) => {
        e.preventDefault();
        const id = document.getElementById('categoryId').value;
        const method = id ? 'PUT' : 'POST';
        const url = id ? `${API_URL}/${id}` : API_URL;

        const payload = {
            name: document.getElementById('name').value,
            slug: document.getElementById('slug').value,
            description: document.getElementById('description').value,
            icon: document.getElementById('icon').value,
            color: document.getElementById('color').value,
            is_active: document.getElementById('is_active').checked ? 1 : 0
        };

        const res = await fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' },
            body: JSON.stringify(payload)
        });

        if (res.ok) { closeModal(); loadData(); } 
        else { alert("Gagal menyimpan. Pastikan slug unik!"); }
    };
</script>
@endpush