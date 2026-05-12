@extends('admin.layout')

@section('title', 'Article Builder - Nurtura Family')

{{-- Menghilangkan judul default agar tampilan lebih clean --}}
@section('page_title', '') 

@section('topbar_actions')
    <button class="btn-new-article" onclick="openEditor('create')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        New Article
    </button>
@endsection

@section('content')
<div class="article-builder-page">
    
    {{-- Stats Cards --}}
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-info">
                <small>TOTAL ARTICLES</small>
                <h3 id="statTotal">0</h3>
            </div>
            <span class="stat-trend up">Live</span>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <small>PUBLISHED</small>
                <h3 id="statPublished">0</h3>
            </div>
            <span class="stat-trend" style="background:#E0F2FE; color:#0369A1;">Active</span>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <small>DRAFTS</small>
                <h3 id="statDraft">0</h3>
            </div>
            <span class="stat-trend" style="background:#F1F5F9; color:#475569;">Saved</span>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="article-filters">
        <button class="filter-chip active" onclick="filterByStatus('all', this)">All Content</button>
        <button class="filter-chip" onclick="filterByStatus('published', this)">Published</button>
        <button class="filter-chip" onclick="filterByStatus('draft', this)">Drafts</button>
    </div>

    {{-- Article Grid --}}
    <div id="articlesGrid" class="articles-grid">
        {{-- Data akan diisi oleh JavaScript --}}
    </div>
</div>

{{-- MODAL EDITOR --}}
<div id="editorOverlay" class="editor-overlay" style="display: none;">
    <div class="editor-modal-container">
        <header class="editor-header">
            <div class="header-left">
                <button onclick="closeEditor()" class="btn-close-modal">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
                <span id="saveStatus" class="modal-label">New Article</span>
            </div>
            <div class="header-right" style="display: flex; align-items: center; gap: 12px;">
                <button id="btnDelete" class="btn-delete-outline" style="display:none;" onclick="deleteArticle()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    Delete Article
                </button>
                <button onclick="saveArticle()" id="btnSave" class="btn-publish-main">
                    Save Article
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-left:8px"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </div>
        </header>

        <div class="editor-content-grid">
            {{-- SISI KIRI: INPUT DATA --}}
            <div class="editor-scroller">
                <form id="articleForm" class="premium-form">
                    <input type="hidden" id="articleId">
                    
                    <div class="form-group">
                        <label>ARTICLE TITLE</label>
                        <input type="text" id="articleTitle" class="input-premium-title" placeholder="Enter article title..." required>
                    </div>

                    <div class="form-group" style="margin-top:-20px; margin-bottom: 25px;">
                        <div class="slug-display-mini">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                            <span>nurtura.family/articles/</span>
                            <input type="text" id="articleSlug" placeholder="url-slug-otomatis" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>CATEGORY</label>
                            <select id="category_id" class="select-premium-field" onchange="updatePreview()" required>
                                <option value="">Select Category</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>TAGS</label>
                            <input type="text" id="articleTags" class="input-premium-field" placeholder="Clinical, Parenting..." oninput="updatePreview()">
                        </div>
                    </div>

                    {{-- PERBAIKAN: THUMBNAIL INPUT TANPA POPUP --}}
                    <div class="form-group">
                        <label>THUMBNAIL IMAGE</label>
                        <input type="hidden" id="thumbnailUrl">
                        <div class="split-upload-container">
                            <div class="upload-side">
                                <span class="side-label">PASTE IMAGE URL</span>
                                <input type="text" id="inputUrlField" class="mini-input-url" 
                                    placeholder="https://example.com/image.jpg" 
                                    oninput="handleUrlInput(this.value)">
                            </div>
                            
                            <div class="side-divider">OR</div>

                            <div class="upload-side">
                                <span class="side-label">UPLOAD FROM DEVICE</span>
                                <input type="file" id="thumbnailFile" style="display:none" accept="image/*" onchange="previewLocalFile(this)">
                                <button type="button" class="btn-mini-upload" onclick="document.getElementById('thumbnailFile').click()">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                                    Choose File
                                </button>
                            </div>
                        </div>
                        
                        <div id="thumbPreviewArea" class="thumb-preview-mini-box">
                             <p>No thumbnail selected</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>ARTICLE CONTENT</label>
                        <textarea id="articleDescription" class="textarea-premium" placeholder="Start writing your medical article here..." oninput="updatePreview()" required></textarea>
                    </div>

                    <div class="form-group">
                        <div class="toggle-container">
                            <div class="toggle-text">
                                <strong>Publish to Site</strong>
                                <p>Make this article visible to the public</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" id="is_published" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            {{-- SISI KANAN: LIVE PREVIEW --}}
            <div class="editor-preview-pane">
                <div class="preview-header">
                    <span>LIVE READER PREVIEW</span>
                    <div class="preview-dots"><span></span><span></span><span></span></div>
                </div>
                
                <div class="live-card-mockup">
                    <div class="card-img-wrapper">
                        <img id="prevImg" src="https://via.placeholder.com/600x350?text=No+Image" alt="Preview">
                        <span class="read-time-badge">5 MIN READ</span>
                    </div>
                    <div class="card-content">
                        <div class="card-meta">
                            <span id="prevCatName" class="meta-cat">CATEGORY</span>
                            <span class="meta-date">• May 2026</span>
                        </div>
                        <h2 id="prevTitle">Article Title Preview</h2>
                        <p id="prevDesc">Brief summary will appear here as you type...</p>
                        
                        <div class="author-box">
                            <img src="https://ui-avatars.com/api/?name=Admin+Nurtura&background=4B5E40&color=fff" alt="Avatar">
                            <div>
                                <strong>Admin Nurtura</strong>
                                <span>Health Expert • Clinical Editor</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* 1. Dashboard Styles */
    .btn-new-article { background: #4B5E40; color: white; border: none; padding: 10px 18px; border-radius: 10px; font-weight: 600; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: 0.3s; }
    .btn-new-article:hover { background: #3d4d34; transform: translateY(-1px); }
    
    .stats-container { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; padding: 20px; border-radius: 16px; border: 1px solid rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
    .stat-info small { color: #64748b; font-weight: 700; font-size: 10px; text-transform: uppercase; }
    .stat-info h3 { font-size: 24px; font-weight: 700; color: #334155; margin: 5px 0 0 0; }
    .stat-trend { font-size: 11px; font-weight: 700; padding: 4px 8px; border-radius: 6px; background: #DCFCE7; color: #166534; }

    .article-filters { display: flex; gap: 10px; margin-bottom: 25px; }
    .filter-chip { padding: 8px 16px; border-radius: 20px; border: 1px solid #E2E8F0; background: white; font-size: 13px; cursor: pointer; color: #64748b; transition: 0.2s; }
    .filter-chip.active { background: #4B5E40; color: white; border-color: #4B5E40; }

    .articles-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; }
    .article-item { background: white; border-radius: 16px; overflow: hidden; border: 1px solid #E2E8F0; cursor: pointer; transition: 0.3s; }
    .article-item:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
    .article-item img { width: 100%; height: 180px; object-fit: cover; }
    .article-item-body { padding: 20px; }
    .badge { font-size: 10px; font-weight: 800; padding: 4px 8px; border-radius: 4px; text-transform: uppercase; margin-bottom: 10px; display: inline-block; }
    .badge-published { background: #DCFCE7; color: #166534; }
    .badge-draft { background: #F1F5F9; color: #475569; }

    /* 2. Premium Editor Layout */
    .editor-overlay { position: fixed; inset: 0; z-index: 9999; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; padding: 20px; }
    .editor-modal-container { background: #FDFCFB; width: 100%; max-width: 1150px; height: 90vh; border-radius: 24px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    .editor-header { padding: 16px 32px; background: #fff; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center; }
    .modal-label { font-weight: 700; color: #334155; font-size: 16px; }
    .editor-content-grid { display: grid; grid-template-columns: 1fr 420px; flex: 1; overflow: hidden; }
    .editor-scroller { padding: 40px; overflow-y: auto; background: white; }
    .editor-preview-pane { padding: 40px; background: #F8F9F8; border-left: 1px solid #E2E8F0; overflow-y: auto; }

    /* 3. Perbaikan Thumbnail Styles (Split View) */
    .split-upload-container { display: flex; align-items: center; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 15px; gap: 15px; margin-bottom: 12px; }
    .upload-side { flex: 1; display: flex; flex-direction: column; gap: 8px; }
    .side-label { font-size: 9px; font-weight: 800; color: #94A3B8; }
    .side-divider { font-weight: 800; color: #CBD5E1; font-size: 11px; }
    .mini-input-url { width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #E2E8F0; font-size: 12px; outline: none; }
    .mini-input-url:focus { border-color: #4B5E40; }
    .btn-mini-upload { background: white; border: 1.5px solid #4B5E40; color: #4B5E40; padding: 8px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: 0.2s; }
    .btn-mini-upload:hover { background: #F0F4EF; }
    .thumb-preview-mini-box { width: 100%; height: 120px; border-radius: 12px; background: #F1F5F9; border: 1px solid #E2E8F0; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 25px; }
    .thumb-preview-mini-box img { width: 100%; height: 100%; object-fit: cover; }
    .thumb-preview-mini-box p { font-size: 12px; color: #94A3B8; }

    /* Input lainnya */
    .premium-form label { display: block; font-size: 11px; font-weight: 800; color: #4B5E40; margin-bottom: 8px; letter-spacing: 0.5px; }
    .input-premium-title { width: 100%; border: none; border-bottom: 1px solid #E2E8F0; font-size: 28px; font-weight: 700; color: #1E293B; margin-bottom: 25px; outline: none; padding-bottom: 10px; transition: 0.3s; }
    .input-premium-title:focus { border-color: #4B5E40; }
    .slug-display-mini { display: flex; align-items: center; gap: 6px; color: #94A3B8; font-size: 12px; }
    .slug-display-mini input { border: none; background: transparent; color: #64748B; font-weight: 600; outline: none; width: 100%; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
    .select-premium-field, .input-premium-field { width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #E2E8F0; background: #F8FAFC; outline: none; transition: 0.2s; }
    .textarea-premium { width: 100%; height: 200px; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; resize: none; background: #F8FAFC; outline: none; font-size: 14px; line-height: 1.6; }

    /* Toggle Switch */
    .toggle-container { display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #F8FAFC; border-radius: 12px; border: 1px solid #E2E8F0; }
    .toggle-text strong { display: block; font-size: 14px; color: #1E293B; }
    .toggle-text p { font-size: 12px; color: #64748B; margin: 0; }
    .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; inset: 0; background: #CBD5E1; border-radius: 34px; transition: .4s; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: .4s; }
    input:checked + .slider { background: #4B5E40; }
    input:checked + .slider:before { transform: translateX(20px); }

    /* Live Preview Styling */
    .preview-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .preview-header span { font-size: 12px; font-weight: 700; color: #94A3B8; letter-spacing: 1px; }
    .preview-dots { display: flex; gap: 6px; }
    .preview-dots span { width: 10px; height: 10px; border-radius: 50%; background: #E2E8F0; }
    .preview-dots span:nth-child(1) { background: #FF5F56; }
    .preview-dots span:nth-child(2) { background: #FFBD2E; }
    .preview-dots span:nth-child(3) { background: #27C93F; }

    .live-card-mockup { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
    .card-img-wrapper { position: relative; height: 180px; background: #E2E8F0; }
    .card-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    .read-time-badge { position: absolute; top: 12px; left: 12px; background: rgba(0,0,0,0.6); color: white; font-size: 10px; font-weight: 700; padding: 4px 8px; border-radius: 6px; backdrop-filter: blur(4px); }
    .card-content { padding: 24px; }
    .meta-cat { color: #4B5E40; font-weight: 800; font-size: 11px; text-transform: uppercase; }
    .meta-date { font-size: 11px; color: #94A3B8; }
    .card-content h2 { font-size: 18px; font-weight: 700; margin: 10px 0; line-height: 1.4; color: #1E293B; }
    .card-content p { font-size: 13px; color: #64748B; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .author-box { display: flex; align-items: center; gap: 12px; margin-top: 20px; padding-top: 15px; border-top: 1px solid #F1F5F9; }
    .author-box img { width: 36px; height: 36px; border-radius: 50%; }
    .author-box strong { display: block; font-size: 12px; color: #1E293B; }
    .author-box span { font-size: 11px; color: #94A3B8; }

    /* Buttons */
    .btn-publish-main { background: #0F172A; color: white; border: none; padding: 10px 20px; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; transition: 0.2s; }
    .btn-publish-main:hover { background: #1e293b; }
    .btn-delete-outline { background: white; color: #EF4444; border: 1.5px solid #FEE2E2; padding: 10px 18px; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; transition: 0.2s; }
    .btn-delete-outline:hover { background: #FEF2F2; border-color: #EF4444; }
    .btn-close-modal { background: #F1F5F9; border: none; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; margin-right: 15px; color: #64748B; }
</style>
@endsection

@push('scripts')
<script>
    const API_URL = '/api/articles';
    const CAT_URL = '/api/article-categories';
    const token = localStorage.getItem('token');
    let currentStatusFilter = 'all';

    document.addEventListener('DOMContentLoaded', () => {
    loadCategories();
    loadArticles();

    // Listener untuk slug otomatis
    document.getElementById('articleTitle').addEventListener('input', function(e) {
        const slug = e.target.value.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
        document.getElementById('articleSlug').value = slug;
        updatePreview();
    });

    // LOGIKA BARU: Jika toggle di-klik, langsung jalankan saveArticle
    document.getElementById('is_published').addEventListener('change', function() {
        // Hanya auto-save jika minimal Judul sudah diisi
        if (document.getElementById('articleTitle').value.trim() !== "") {
            saveArticle();
        }
    });
});

    async function loadCategories() {
    try {
        const res = await fetch(CAT_URL, { headers: { 'Authorization': `Bearer ${token}` }});
        const cats = await res.json();
        const select = document.getElementById('category_id');
        
        select.innerHTML = '<option value="">Select Category</option>';
        
        cats.forEach(c => {
            // PERBAIKAN FATAL: Pakai c._id || c.id supaya pasti dapet 24 karakternya
            const catId = c._id || c.id; 
            select.innerHTML += `<option value="${catId}">${c.name}</option>`; 
        });
        
        return true; 
    } catch (e) {
        console.error("Gagal load kategori:", e);
        return false;
    }
}

    async function loadArticles() {
    const grid = document.getElementById('articlesGrid');
    grid.innerHTML = '<p>Loading articles...</p>';
    
    try {
        const res = await fetch(API_URL, { headers: { 'Authorization': `Bearer ${token}` }});
        const allArticles = await res.json();
        
        // Update Stats
        document.getElementById('statTotal').innerText = allArticles.length;
        document.getElementById('statPublished').innerText = allArticles.filter(a => a.status === 'published').length;
        document.getElementById('statDraft').innerText = allArticles.filter(a => a.status === 'draft').length;

        // Filter sesuai tab
        let displayData = allArticles;
        if (currentStatusFilter !== 'all') {
            displayData = allArticles.filter(a => a.status === currentStatusFilter);
        }

        grid.innerHTML = displayData.map(a => {
            // Pastikan ID tersedia, MongoDB biasanya pakai _id
            const articleId = a._id || a.id; 
            
            return `
                <div class="article-item" onclick="openEditor('edit', '${articleId}')">
                    <img src="${a.thumbnail}" onerror="this.src='https://ui-avatars.com/api/?name=Article&background=E2E8F0&color=94A3B8&size=400'">
                    <div class="article-item-body">
                        <span class="badge badge-${a.status}">${a.status}</span>
                        <h4 style="font-size:17px; margin:0 0 10px 0; color:#1E293B;">${a.title}</h4>
                        <small style="color:#94A3B8;">${a.category?.name || 'Uncategorized'} • ${a.views_count || 0} views</small>
                    </div>
                </div>
            `;
        }).join('');
    } catch (e) {
        grid.innerHTML = '<p>Failed to load articles.</p>';
    }
}

    function filterByStatus(status, el) {
    currentStatusFilter = status; // Simpan status yang dipilih ('all', 'published', atau 'draft')
    document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    loadArticles(); // Jalankan fungsi load yang baru
}

    async function openEditor(mode, id = null) {
    console.log("Opening editor mode:", mode, "ID:", id);

    const overlay = document.getElementById('editorOverlay');
    const form = document.getElementById('articleForm');
    const btnDelete = document.getElementById('btnDelete');
    const saveStatus = document.getElementById('saveStatus');
    
    overlay.style.display = 'flex';
    
    // 1. BERSIHKAN SEMUA (Reset Form & Preview)
    form.reset(); 
    document.getElementById('articleId').value = '';
    document.getElementById('inputUrlField').value = '';
    document.getElementById('thumbnailUrl').value = '';
    document.getElementById('thumbPreviewArea').innerHTML = `<p>No thumbnail selected</p>`;
    
    // Reset Tampilan Preview di Sisi Kanan
    document.getElementById('prevTitle').innerText = 'Article Title Preview';
    document.getElementById('prevDesc').innerText = 'Brief summary will appear here as you type...';
    document.getElementById('prevCatName').innerText = 'CATEGORY';
    document.getElementById('prevImg').src = 'https://placehold.co/600x400?text=No+Image';

    // WAJIB: Tunggu kategori selesai dimuat dulu sebelum lanjut
    await loadCategories();

    if (mode === 'edit' && id && id !== 'null') {
        saveStatus.innerText = 'Edit Article';
        btnDelete.style.display = 'block';

        try {
            const res = await fetch(`${API_URL}/${id}`, { 
                headers: { 
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
            const a = await res.json();
            
            console.log("Data diterima untuk Edit:", a);
            
            // 2. ISI FIELD FORM (Sekarang aman karena kategori sudah siap)
            document.getElementById('articleId').value = a._id || a.id;
            document.getElementById('articleTitle').value = a.title || '';
            document.getElementById('articleSlug').value = a.slug || '';
            document.getElementById('articleDescription').value = a.description || '';
            
            // Set kategori (pasti muncul karena sudah di-await tadi)
            document.getElementById('category_id').value = a.category_id || '';
            
            document.getElementById('thumbnailUrl').value = a.thumbnail || '';
            document.getElementById('inputUrlField').value = a.thumbnail || '';
            document.getElementById('is_published').checked = (a.status === 'published');
            
            if(a.thumbnail) {
                document.getElementById('thumbPreviewArea').innerHTML = `<img src="${a.thumbnail}">`;
            }

            // Update preview kanan
            updatePreview(); 
        } catch (err) {
            console.error("Gagal load data edit:", err);
        }
    } else {
        saveStatus.innerText = 'New Article';
        btnDelete.style.display = 'none';
    }
}

    // --- LOGIKA BARU UNTUK THUMBNAIL ---

    // Handle Input URL (Sisi Kiri)
    function handleUrlInput(url) {
        if (url.trim() !== "") {
            document.getElementById('thumbnailUrl').value = url;
            document.getElementById('thumbPreviewArea').innerHTML = `<img src="${url}">`;
            // Kosongkan input file jika ada
            document.getElementById('thumbnailFile').value = "";
        } else {
            document.getElementById('thumbnailUrl').value = "";
            document.getElementById('thumbPreviewArea').innerHTML = `<p>No thumbnail selected</p>`;
        }
        updatePreview();
    }

    // Handle Upload File (Sisi Kanan)
    function previewLocalFile(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const base64Image = e.target.result;
                document.getElementById('thumbnailUrl').value = base64Image;
                document.getElementById('thumbPreviewArea').innerHTML = `<img src="${base64Image}">`;
                // Kosongkan input URL teks karena user pilih file
                document.getElementById('inputUrlField').value = "";
                updatePreview();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updatePreview() {
    const title = document.getElementById('articleTitle').value;
    const desc = document.getElementById('articleDescription').value;
    const cat = document.getElementById('category_id');
    const catName = cat.options[cat.selectedIndex]?.text || "CATEGORY";
    const thumb = document.getElementById('thumbnailUrl').value;

    document.getElementById('prevTitle').innerText = title || "Article Title Preview";
    document.getElementById('prevDesc').innerText = desc || "Brief summary will appear here as you type...";
    document.getElementById('prevCatName').innerText = catName;
    
    const imgElement = document.getElementById('prevImg');
    // GANTI via.placeholder.com jadi placehold.co biar nggak ERR_CONNECTION_CLOSED
    const fallbackImg = "https://placehold.co/600x350?text=No+Image";

    imgElement.src = thumb || fallbackImg;

    imgElement.onerror = function() {
        this.src = fallbackImg;
    };
}

    async function saveArticle() {
    const id = document.getElementById('articleId').value;
    const categoryId = document.getElementById('category_id').value;

    if (!categoryId) {
        alert("Pilih kategori dulu ya!");
        return;
    }
    
    // Ambil semua data dari form
    const payload = {
        title: document.getElementById('articleTitle').value,
        slug: document.getElementById('articleSlug').value,
        description: document.getElementById('articleDescription').value,
        category_id: categoryId,
        thumbnail: document.getElementById('thumbnailUrl').value || 'https://placehold.co/800x400?text=No+Image',
        // Logika status: Jika checkbox ON = published, jika OFF = draft
        status: document.getElementById('is_published').checked ? 'published' : 'draft',
        tags: document.getElementById('articleTags').value ? 
               document.getElementById('articleTags').value.split(',').map(t => t.trim()) : []
    };

    const method = id ? 'PUT' : 'POST';
    const url = id ? `${API_URL}/${id}` : API_URL;

    try {
        const res = await fetch(url, {
            method: method,
            headers: { 
                'Content-Type': 'application/json', 
                'Authorization': `Bearer ${token}`, // PASTIKAN TOKEN MASIH VALID
                'Accept': 'application/json' 
            },
            body: JSON.stringify(payload)
        });

        const result = await res.json();

        if (res.ok) {
            console.log("Berhasil simpan dengan status:", payload.status);
            // Optional: jangan pakai alert kalau mau simpan otomatis agar tidak mengganggu user
            // alert("Artikel berhasil disimpan!"); 
            closeEditor();
            loadArticles();
        } else {
            if (res.status === 401) {
                alert("Sesi login habis, silakan login ulang!");
                window.location.href = '/login'; // Arahkan ke login jika unauthenticated
            } else {
                const errorMessages = result.errors ? Object.values(result.errors).flat().join('\n') : result.message;
                alert("Gagal simpan:\n" + errorMessages);
            }
        }
    } catch (error) {
        console.error("Fetch Error:", error);
    }
}

    async function deleteArticle() {
        const id = document.getElementById('articleId').value;
        if (!confirm('Yakin ingin menghapus artikel ini?')) return;
        const res = await fetch(`${API_URL}/${id}`, {
            method: 'DELETE',
            headers: { 'Authorization': `Bearer ${token}` }
        });
        if (res.ok) {
            closeEditor();
            loadArticles();
        }
    }

    function closeEditor() {
        document.getElementById('editorOverlay').style.display = 'none';
    }
</script>
@endpush