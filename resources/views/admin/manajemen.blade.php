@extends('admin.layout')

@section('title', 'Manajemen Model — Nurtura Family')

@section('page_title', '') 

@push('styles')
<style>
  .m-search-box { position: relative; }
  .m-search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94A3B8; }
  .m-search-input { padding: 10px 16px 10px 36px; border-radius: 6px; border: 1px solid #E2E8F0; outline: none; font-size: 13px; width: 280px; background: #FFFFFF; color: #334155; }
  
  .m-btn-publish { background: #A3B18A; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: background 0.2s; }
  .m-btn-publish:hover { background: #8E9C76; }

  .m-card-full { background: transparent; margin-top: 10px; }
  .m-section-subtitle { font-size: 14px; font-weight: 700; color: #475569; text-decoration: underline; margin-bottom: 24px; display: inline-block; }
  .m-card-header-inline { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
  .m-preview-title { font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 10px; color: #1E293B; }
  .m-active-badge { background: #F1F5F9; color: #64748B; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }

  .q-item { background: #FFFFFF; padding: 24px; border-radius: 12px; margin-bottom: 16px; position: relative; border-left: 4px solid transparent; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
  .q-item.active-border { border-left-color: #A3B18A; }
  .q-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
  .q-number-title { display: flex; gap: 16px; align-items: flex-start; }
  .q-number { background: #E5E7EB; color: #475569; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 6px; font-weight: 800; font-size: 14px; flex-shrink: 0; }
  .q-title-text { font-size: 15px; font-weight: 700; color: #1E293B; margin-top: 4px; }
  .q-body { margin-left: 48px; }
  .q-desc { font-size: 13px; color: #64748B; line-height: 1.6; margin-bottom: 16px; max-width: 80%; }
  .q-options { display: flex; gap: 12px; }
  .q-btn-opt { background: #FFFFFF; border: 1px solid #E2E8F0; padding: 6px 20px; border-radius: 20px; font-size: 12px; color: #475569; }

  .q-actions { display: flex; gap: 12px; align-items: center; }
  .btn-edit-icon { color: #94A3B8; cursor: pointer; transition: color 0.2s; }
  .btn-edit-icon:hover { color: #A3B18A; }
  .status-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    border: 1px solid #CBD5E1;
    background: #F1F5F9;
    color: #475569;
    transition: all 0.2s ease;
    user-select: none;
  }

  .status-pill:hover {
    background: #E2E8F0;
    transform: scale(1.05);
  }

  .status-pill:active {
    transform: scale(0.95);
  }

  .status-pill.active {
    background: #A3B18A;
    color: #FFFFFF;
    border-color: #A3B18A;
  }

  .status-pill.active:hover {
    background: #8E9C76;
  }

  .m-modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); display: none; align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(2px); }
  .m-modal-card { background: white; padding: 28px; border-radius: 12px; width: 480px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
  .m-modal-card h3 { margin-bottom: 20px; color: #1E293B; font-size: 18px; }
  .m-form-group { margin-bottom: 15px; }
  .m-form-group label { display: block; font-size: 13px; font-weight: 600; color: #64748B; margin-bottom: 5px; }
  .m-input-style { width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px; outline: none; }
  .m-modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
  .m-fixed-ans { font-size: 12px; color: #A3B18A; background: #F0F4E8; padding: 4px 8px; border-radius: 4px; font-weight: 700; }
  
  .q-item.loading {
    position: relative;
    pointer-events: none;
  }

  .q-item.loading::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,0.6);
    border-radius: 12px;
    z-index: 2;
  }

  .q-item.loading::after {
    content: '';
    width: 22px;
    height: 22px;
    border: 3px solid #A3B18A;
    border-top: 3px solid transparent;
    border-radius: 50%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation: spin 0.6s linear infinite;
    z-index: 3;
  }

  @keyframes spin {
    to { transform: translate(-50%, -50%) rotate(360deg); }
  }
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <h2 class="page-header__title">Manajemen Model & Dataset</h2>
    <p class="page-header__desc">Konfigurasi 9 parameter klinis kuesioner diagnosis sistem Nurtura.</p>
  </div>
  
  <div class="page-header__actions">
    <div class="m-search-box">
      <svg class="m-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" class="m-search-input" placeholder="Cari parameter...">
    </div>
    <button class="m-btn-publish">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
      Publish Versi Baru
    </button>
  </div>
</div>

<div class="m-card-full">
  <span class="m-section-subtitle">Kuesioner</span>

  <div class="m-card-header-inline">
    <div class="m-preview-title">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="9" x2="15" y2="9"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="11" y2="17"/></svg>
      Preview Kuesioner
    </div>
    <span class="m-active-badge">9 Pertanyaan Terdaftar</span>
  </div>

  <div class="q-container" id="q-container"></div>

  <div class="m-modal-overlay" id="modalEdit">
    <div class="m-modal-card">
      <h3>Edit Pertanyaan</h3>
      <form id="formUpdateQuest">
        <input type="hidden" id="edit-id">
        <div class="m-form-group">
          <label>Pertanyaan</label>
          <input type="text" id="edit-title" class="m-input-style">
        </div>
        <div class="m-form-group">
          <label>Deskripsi Singkat</label>
          <textarea id="edit-desc" class="m-input-style" rows="3"></textarea>
        </div>
        <div class="m-form-group">
          <label>Pilihan Jawaban</label>
          <div style="display: flex; gap: 8px;"><span class="m-fixed-ans">Ya</span><span class="m-fixed-ans">Tidak</span><span class="m-fixed-ans">Kadang-kadang</span></div>
        </div>
        <div class="m-modal-actions">
          <button type="button" style="background:none; border:none; color:#64748B; cursor:pointer;" onclick="closeEditForm()">Batal</button>
          <button type="submit" class="m-btn-publish">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const token = localStorage.getItem('token');
    const container = document.getElementById('q-container');

    if (!container) return;

    async function loadQuestions() {
        try {
            const res = await fetch('/api/admin/questions', {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            });

            const result = await res.json();
            if (!result.status) throw new Error(result.message);

            container.innerHTML = '';

            result.data.forEach((q, index) => {
                container.innerHTML += `
                <div class="q-item" data-id="${q._id}">
                    <div class="q-header">
                        <div class="q-number-title">
                            <div class="q-number">${String(index+1).padStart(2, '0')}</div>
                            <div class="q-title-text">${q.question_text}</div>
                        </div>
                        <div class="q-actions">
                            <i onclick="openEditForm(this)" style="cursor:pointer">✏️</i>
                        </div>
                    </div>

                    <div class="q-body">
                        <p class="q-desc">${q.category ?? '-'}</p>
                        <div class="q-options">
                            ${(q.options || []).map(opt => `<button class="q-btn-opt">${opt}</button>`).join('')}
                        </div>
                    </div>
                </div>`;
            });

        } catch (err) {
            container.innerHTML = `<p style="color:red;">Gagal load data</p>`;
        }
    }

    document.getElementById('formUpdateQuest')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        try {
            const id = document.getElementById('edit-id').value;

            const res = await fetch(`/api/admin/questions/${id}`, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    question_text: document.getElementById('edit-title').value,
                    category: document.getElementById('edit-desc').value
                })
            });

            const result = await res.json();
            if (!result.status) throw new Error(result.message);

            closeEditForm();
            loadQuestions();

        } catch (err) {
            alert('Gagal update: ' + err.message);
        }
    });

    loadQuestions();
});

window.openEditForm = function(btn) {
    const card = btn.closest('.q-item');

    document.getElementById('edit-id').value = card.dataset.id;
    document.getElementById('edit-title').value = card.querySelector('.q-title-text').innerText;
    document.getElementById('edit-desc').value = card.querySelector('.q-desc').innerText;

    document.getElementById('modalEdit').style.display = 'flex';
};

window.closeEditForm = function() {
    document.getElementById('modalEdit').style.display = 'none';
};
</script>
@endpush