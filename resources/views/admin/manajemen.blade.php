@extends('admin.layout')

@section('title', 'Manajemen Model — Nurtura Family')

@section('page_title', '') 

@push('styles')
<style>
  /* Search & Button di Header */
  .m-search-box { position: relative; }
  .m-search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94A3B8; }
  .m-search-input { padding: 10px 16px 10px 36px; border-radius: 6px; border: 1px solid #E2E8F0; outline: none; font-size: 13px; width: 280px; background: #FFFFFF; color: #334155; }
  
  .m-btn-publish { background: #A3B18A; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: background 0.2s; }
  .m-btn-publish:hover { background: #8E9C76; }

  /* Kuesioner List Style */
  .m-card-full { background: transparent; margin-top: 10px; }
  .m-section-subtitle { font-size: 14px; font-weight: 700; color: #475569; text-decoration: underline; margin-bottom: 24px; display: inline-block; }
  .m-card-header-inline { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
  .m-preview-title { font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 10px; color: #1E293B; }
  .m-active-badge { background: #F1F5F9; color: #64748B; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }

  /* Question Item */
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
  .status-pill { display: flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; background: #E2E8F0; color: #64748B; }
  .status-pill.active { background: #ECFDF5; color: #059669; }
  .status-pill::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

  /* Modal Edit */
  .m-modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); display: none; align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(2px); }
  .m-modal-card { background: white; padding: 28px; border-radius: 12px; width: 480px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
  .m-modal-card h3 { margin-bottom: 20px; color: #1E293B; font-size: 18px; }
  .m-form-group { margin-bottom: 15px; }
  .m-form-group label { display: block; font-size: 13px; font-weight: 600; color: #64748B; margin-bottom: 5px; }
  .m-input-style { width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px; outline: none; }
  .m-modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
  .m-fixed-ans { font-size: 12px; color: #A3B18A; background: #F0F4E8; padding: 4px 8px; border-radius: 4px; font-weight: 700; }
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

  <div class="q-container">
    <div class="q-item active-border" data-id="1">
      <div class="q-header">
        <div class="q-number-title"><div class="q-number">01</div><div class="q-title-text">Frekuensi Nyeri Dada</div></div>
        <div class="q-actions">
          <i class="btn-edit-icon" onclick="openEditForm(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></i>
          <span class="status-pill active">AKTIF</span>
        </div>
      </div>
      <div class="q-body">
        <p class="q-desc">Seberapa sering Anda merasakan tekanan atau nyeri di bagian dada saat beraktivitas fisik dalam 30 hari terakhir?</p>
        <div class="q-options"><button class="q-btn-opt">Ya</button><button class="q-btn-opt">Tidak</button><button class="q-btn-opt">Kadang-kadang</button></div>
      </div>
    </div>

    <div class="q-item active-border" data-id="2">
      <div class="q-header">
        <div class="q-number-title"><div class="q-number">02</div><div class="q-title-text">Riwayat Merokok Keluarga</div></div>
        <div class="q-actions">
          <i class="btn-edit-icon" onclick="openEditForm(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></i>
          <span class="status-pill active">AKTIF</span>
        </div>
      </div>
      <div class="q-body">
        <p class="q-desc">Apakah orang tua atau saudara kandung memiliki riwayat perokok aktif selama lebih dari 10 tahun?</p>
        <div class="q-options"><button class="q-btn-opt">Ya</button><button class="q-btn-opt">Tidak</button><button class="q-btn-opt">Kadang-kadang</button></div>
      </div>
    </div>

    <div class="q-item active-border" data-id="3">
      <div class="q-header">
        <div class="q-number-title"><div class="q-number">03</div><div class="q-title-text">Kualitas Pola Makan</div></div>
        <div class="q-actions">
          <i class="btn-edit-icon" onclick="openEditForm(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></i>
          <span class="status-pill active">AKTIF</span>
        </div>
      </div>
      <div class="q-body">
        <p class="q-desc">Apakah Anda mengonsumsi sayuran dan buah-buahan secara rutin minimal 3 porsi sehari?</p>
        <div class="q-options"><button class="q-btn-opt">Ya</button><button class="q-btn-opt">Tidak</button><button class="q-btn-opt">Kadang-kadang</button></div>
      </div>
    </div>

    <div class="q-item active-border" data-id="4">
      <div class="q-header">
        <div class="q-number-title"><div class="q-number">04</div><div class="q-title-text">Tingkat Stres Kerja</div></div>
        <div class="q-actions">
          <i class="btn-edit-icon" onclick="openEditForm(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></i>
          <span class="status-pill active">AKTIF</span>
        </div>
      </div>
      <div class="q-body">
        <p class="q-desc">Apakah beban pekerjaan Anda sering menyebabkan gangguan tidur atau kecemasan berlebih?</p>
        <div class="q-options"><button class="q-btn-opt">Ya</button><button class="q-btn-opt">Tidak</button><button class="q-btn-opt">Kadang-kadang</button></div>
      </div>
    </div>

    <div class="q-item active-border" data-id="5">
      <div class="q-header">
        <div class="q-number-title"><div class="q-number">05</div><div class="q-title-text">Aktivitas Olahraga</div></div>
        <div class="q-actions">
          <i class="btn-edit-icon" onclick="openEditForm(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></i>
          <span class="status-pill active">AKTIF</span>
        </div>
      </div>
      <div class="q-body">
        <p class="q-desc">Apakah Anda rutin berolahraga setidaknya 30 menit sebanyak 3 kali dalam seminggu?</p>
        <div class="q-options"><button class="q-btn-opt">Ya</button><button class="q-btn-opt">Tidak</button><button class="q-btn-opt">Kadang-kadang</button></div>
      </div>
    </div>

    <div class="q-item active-border" data-id="6">
      <div class="q-header">
        <div class="q-number-title"><div class="q-number">06</div><div class="q-title-text">Konsumsi Air Putih</div></div>
        <div class="q-actions">
          <i class="btn-edit-icon" onclick="openEditForm(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></i>
          <span class="status-pill active">AKTIF</span>
        </div>
      </div>
      <div class="q-body">
        <p class="q-desc">Apakah Anda memenuhi asupan air putih minimal 2 liter atau 8 gelas dalam satu hari?</p>
        <div class="q-options"><button class="q-btn-opt">Ya</button><button class="q-btn-opt">Tidak</button><button class="q-btn-opt">Kadang-kadang</button></div>
      </div>
    </div>

    <div class="q-item active-border" data-id="7">
      <div class="q-header">
        <div class="q-number-title"><div class="q-number">07</div><div class="q-title-text">Paparan Polusi</div></div>
        <div class="q-actions">
          <i class="btn-edit-icon" onclick="openEditForm(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></i>
          <span class="status-pill active">AKTIF</span>
        </div>
      </div>
      <div class="q-body">
        <p class="q-desc">Apakah lingkungan kerja atau tempat tinggal Anda memiliki tingkat polusi udara yang tinggi?</p>
        <div class="q-options"><button class="q-btn-opt">Ya</button><button class="q-btn-opt">Tidak</button><button class="q-btn-opt">Kadang-kadang</button></div>
      </div>
    </div>

    <div class="q-item active-border" data-id="8">
      <div class="q-header">
        <div class="q-number-title"><div class="q-number">08</div><div class="q-title-text">Kesehatan Mental</div></div>
        <div class="q-actions">
          <i class="btn-edit-icon" onclick="openEditForm(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></i>
          <span class="status-pill active">AKTIF</span>
        </div>
      </div>
      <div class="q-body">
        <p class="q-desc">Apakah Anda sering merasa cemas atau kesulitan berkonsentrasi dalam melakukan pekerjaan harian?</p>
        <div class="q-options"><button class="q-btn-opt">Ya</button><button class="q-btn-opt">Tidak</button><button class="q-btn-opt">Kadang-kadang</button></div>
      </div>
    </div>

    <div class="q-item" data-id="9">
      <div class="q-header">
        <div class="q-number-title"><div class="q-number">09</div><div class="q-title-text">Pemeriksaan Medis</div></div>
        <div class="q-actions">
          <i class="btn-edit-icon" onclick="openEditForm(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></i>
          <span class="status-pill">NONAKTIF</span>
        </div>
      </div>
      <div class="q-body">
        <p class="q-desc">Apakah Anda melakukan medical check-up rutin setidaknya sekali dalam setahun?</p>
        <div class="q-options"><button class="q-btn-opt">Ya</button><button class="q-btn-opt">Tidak</button><button class="q-btn-opt">Kadang-kadang</button></div>
      </div>
    </div>
  </div>
</div>

{{-- MODAL EDIT --}}
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
      <div class="m-form-group">
        <label>Status Aktif</label>
        <select id="edit-status" class="m-input-style">
          <option value="active">Aktif</option>
          <option value="nonactive">Nonaktif</option>
        </select>
      </div>
      <div class="m-modal-actions">
        <button type="button" style="background:none; border:none; color:#64748B; cursor:pointer;" onclick="closeEditForm()">Batal</button>
        <button type="submit" class="m-btn-publish">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  let currentTargetCard = null;

  function openEditForm(btn) {
    currentTargetCard = btn.closest('.q-item');
    const id = currentTargetCard.getAttribute('data-id');
    const title = currentTargetCard.querySelector('.q-title-text').innerText;
    const desc = currentTargetCard.querySelector('.q-desc').innerText;
    const isActive = currentTargetCard.querySelector('.status-pill').classList.contains('active');

    document.getElementById('edit-id').value = id;
    document.getElementById('edit-title').value = title;
    document.getElementById('edit-desc').value = desc;
    document.getElementById('edit-status').value = isActive ? 'active' : 'nonactive';
    document.getElementById('modalEdit').style.display = 'flex';
  }

  function closeEditForm() {
    document.getElementById('modalEdit').style.display = 'none';
  }

  document.getElementById('formUpdateQuest').addEventListener('submit', function(e) {
    e.preventDefault();
    currentTargetCard.querySelector('.q-title-text').innerText = document.getElementById('edit-title').value;
    currentTargetCard.querySelector('.q-desc').innerText = document.getElementById('edit-desc').value;
    
    const status = document.getElementById('edit-status').value;
    const pill = currentTargetCard.querySelector('.status-pill');
    if(status === 'active') {
      pill.innerText = 'AKTIF';
      pill.classList.add('active');
      currentTargetCard.classList.add('active-border');
    } else {
      pill.innerText = 'NONAKTIF';
      pill.classList.remove('active');
      currentTargetCard.classList.remove('active-border');
    }
    closeEditForm();
    // Untuk teman Backend: Di sini nanti panggil axios/fetch dengan JWT Header buat update ke DB.
  });
</script>
@endpush