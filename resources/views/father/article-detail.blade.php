<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} - Nurtura Family</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Lora:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboardayah.css') }}">
</head>
<body class="reading-page">
@php
    $publishedAt = $article->published_at ?? $article->created_at ?? null;
    $publishedLabel = $publishedAt
        ? \Carbon\Carbon::parse($publishedAt)->translatedFormat('j F Y')
        : 'Tanggal belum tersedia';

    $rawContent = trim((string) ($article->description ?? ''));
    $paragraphs = $rawContent !== ''
        ? preg_split("/\R{2,}/", $rawContent)
        : ['Konten artikel tidak tersedia.'];
@endphp

<main class="reading-page__wrap">
    <a href="{{ route('father.support') }}" class="reading-page__back">&larr; Kembali ke daftar artikel</a>

    <article class="reading-article">
        <div class="reading-article__body">
            <header class="reading-article__header">
                <div class="reading-article__meta">
                    <span class="reading-article__category">{{ $article->category->name ?? 'Tanpa Kategori' }}</span>
                    <span>{{ $publishedLabel }}</span>
                </div>

                <h1 class="reading-article__title">{{ $article->title }}</h1>

                @if(!empty($article->tags))
                    <div class="reading-article__tags" aria-label="Tag artikel">
                        @foreach($article->tags as $tag)
                            <span class="reading-article__tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
            </header>

            @if($article->thumbnail)
                <figure class="reading-article__figure">
                    <img src="{{ $article->thumbnail }}" alt="{{ $article->title }}" class="reading-article__image">
                </figure>
            @endif

            <section class="reading-article__content">
                @foreach($paragraphs as $paragraph)
                    @php $paragraph = trim($paragraph); @endphp
                    @if($paragraph !== '')
                        <p>{!! nl2br(e($paragraph)) !!}</p>
                    @endif
                @endforeach
            </section>
        </div>
    </article>
</main>
</body>
</html>
