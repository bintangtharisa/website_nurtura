@extends('father.layout')

@section('title', 'Dukungan Istri — Nurtura Family')

@section('content')

<div style="margin-bottom: 18px;">
    <h1 style="font-family: var(--font-display); font-size: 22px; font-weight: 700; color: var(--clr-text-heading); margin: 0; line-height: 1.2;">
        Dukungan Istri
    </h1>
    <p style="font-size: 13px; color: var(--clr-text-muted); margin-top: 6px; line-height: 1.6; max-width: 820px;">
        Pilih artikel terbaik untuk membantu Ayah mendukung Bunda selama kehamilan dan persalinan. Gunakan tombol kategori untuk menyaring topik, dan buka artikel lengkap dengan sekali klik.
    </p>
</div>

<section class="card" style="padding: 20px; margin: 0;">
    <div class="card__header" style="padding-bottom: 16px;">
        <div class="card__title">Artikel Dukungan Istri</div>
    </div>

    <div id="categoryFilters" style="display: flex; flex-wrap: wrap; justify-content: flex-start; gap: 10px; margin-top: 18px; padding-bottom: 18px; border-bottom: 1px solid rgba(15, 23, 42, 0.08);"></div>

    <div id="articlesContainer" style="display: grid; justify-content: left; grid-template-columns: repeat(auto-fit, minmax(280px, 320px)); gap: 18px; padding-top: 18px;"></div>
</section>

@endsection

@push('scripts')
<script>
  const categoryFilters = document.getElementById('categoryFilters');
  const articlesContainer = document.getElementById('articlesContainer');

  let categories = [];
  let allArticles = [];
  let selectedCategory = 'all';

  function getCategoryId(category) {
    return String(category?._id ?? category?.id ?? category ?? 'all');
  }

  function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    if (Number.isNaN(date.getTime())) return dateString;
    return date.toLocaleDateString('id-ID', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  }

  function createCategoryButton(category) {
    const categoryId = getCategoryId(category);
    const button = document.createElement('button');
    button.type = 'button';
    button.textContent = category.name;
    button.dataset.categoryId = categoryId;
    button.style.padding = '10px 18px';
    button.style.borderRadius = '999px';
    button.style.border = '1px solid rgba(15, 23, 42, 0.12)';
    button.style.background = selectedCategory === categoryId ? 'var(--clr-primary)' : 'transparent';
    button.style.color = selectedCategory === categoryId ? '#ffffff' : 'var(--clr-text-heading)';
    button.style.cursor = 'pointer';
    button.style.fontSize = '13px';
    button.style.fontWeight = '700';
    button.style.transition = 'all 0.18s ease';
    button.addEventListener('mouseenter', () => {
      if (selectedCategory !== categoryId) {
        button.style.background = 'rgba(79, 70, 229, 0.08)';
      }
    });
    button.addEventListener('mouseleave', () => {
      if (selectedCategory !== categoryId) {
        button.style.background = 'transparent';
      }
    });
    button.addEventListener('click', () => selectCategory(categoryId));
    return button;
  }

  function renderCategories() {
    categoryFilters.innerHTML = '';
    const allOption = { _id: 'all', name: 'Semua Kategori' };
    categoryFilters.appendChild(createCategoryButton(allOption));
    categories.forEach(category => categoryFilters.appendChild(createCategoryButton(category)));
  }

  function renderArticles() {
    if (!allArticles.length) {
      articlesContainer.innerHTML = '<div style="color: var(--clr-text-muted); font-size: 13px;">Belum ada artikel untuk kategori ini.</div>';
      return;
    }

    articlesContainer.innerHTML = allArticles.map(article => {
      const categoryName = article.category?.name || 'Tanpa Kategori';
      const publishedAt = formatDate(article.published_at || article.created_at || '');
      const thumbnail = article.thumbnail ? `<div style="height: 180px; border-radius: 20px 20px 0 0; overflow: hidden; background: #f3f4f6;"><img src="${article.thumbnail}" alt="${article.title}" style="width: 100%; height: 100%; object-fit: cover;"></div>` : '';
      const articleUrl = `/father/support/article/${encodeURIComponent(article.slug || article._id)}`;
      return `
        <a href="${articleUrl}" style="text-decoration: none; color: inherit; display: block; text-align: left;">
          <div style="display: flex; flex-direction: column; justify-content: space-between; border: 1px solid rgba(15, 23, 42, 0.08); border-radius: 24px; overflow: hidden; background: #ffffff; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06); min-height: 380px; transition: transform 0.18s ease, box-shadow 0.18s ease; text-align: left;">
            ${thumbnail}
            <div style="padding: 20px; display: flex; flex-direction: column; gap: 16px; flex: 1; text-align: left;">
              <div style="display: flex; justify-content: space-between; gap: 12px; align-items: center; flex-wrap: wrap; text-align: left;">
                <span style="font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--clr-primary);">${categoryName}</span>
                <span style="font-size: 11px; color: var(--clr-text-muted);">${publishedAt}</span>
              </div>
              <div>
                <h3 style="margin: 0 0 10px; font-size: 16px; line-height: 1.4; color: var(--clr-text-heading); text-align: left;">${article.title}</h3>
                <p style="margin: 0; color: var(--clr-text-muted); font-size: 13px; line-height: 1.7; text-align: left;">${String(article.description || '').slice(0, 120)}${String(article.description || '').length > 120 ? '...' : ''}</p>
              </div>
              <div style="margin-top: auto; align-self: flex-start; padding: 12px 18px; border-radius: 999px; background: var(--clr-primary); color: #ffffff; font-weight: 700; font-size: 13px; width: fit-content; text-align: left;">Baca Selengkapnya →</div>
            </div>
          </div>
        </a>
      `;
    }).join('');
  }

  function loadArticles(categoryId = 'all') {
    const url = categoryId === 'all'
      ? '/api/articles/all'
      : `/api/articles/all?category_id=${encodeURIComponent(categoryId)}`;

    return fetch(url)
      .then(res => {
        if (!res.ok) throw new Error('Gagal memuat daftar artikel.');
        return res.json();
      })
      .then(data => {
        allArticles = data;
        renderArticles();
      });
  }

  function selectCategory(categoryId) {
    selectedCategory = categoryId;
    renderCategories();
    loadArticles(categoryId).catch(error => {
      articlesContainer.innerHTML = '<div style="color: var(--clr-text-danger);">Tidak dapat memuat daftar artikel.</div>';
      console.error(error);
    });
  }

  async function loadSupportContent() {
    try {
      const categoryRes = await fetch('/api/article-categories');
      if (!categoryRes.ok) {
        throw new Error('Gagal memuat kategori artikel.');
      }
      categories = await categoryRes.json();
      renderCategories();
      await loadArticles();
    } catch (error) {
      categoryFilters.innerHTML = '<div style="color: var(--clr-text-danger);">Tidak dapat memuat kategori artikel.</div>';
      articlesContainer.innerHTML = '<div style="color: var(--clr-text-danger);">Tidak dapat memuat daftar artikel.</div>';
      console.error(error);
    }
  }

  document.addEventListener('DOMContentLoaded', loadSupportContent);
</script>
@endpush