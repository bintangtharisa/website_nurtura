@extends('father.layout')

@section('title', $article->title . ' — Nurtura Family')

@section('content')

<div style="margin-bottom: 18px;">
    <a href="{{ route('father.support') }}" style="font-size: 13px; color: var(--clr-primary); font-weight: 700; text-decoration: none;">← Kembali ke Dukungan Istri</a>
    <h1 style="font-family: var(--font-display); font-size: 28px; font-weight: 700; color: var(--clr-text-heading); margin: 18px 0 8px; line-height: 1.15;">
        {{ $article->title }}
    </h1>
    <div style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center; color: var(--clr-text-muted); font-size: 13px;">
        <span style="background: rgba(79, 70, 229, 0.08); color: var(--clr-primary); padding: 6px 12px; border-radius: 999px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;">{{ $article->category->name ?? 'Tanpa Kategori' }}</span>
        <span>{{ \Carbon\Carbon::parse($article->published_at ?? $article->created_at)->translatedFormat('j F Y') }}</span>
    </div>
</div>

<div class="card" style="padding: 0; overflow: hidden; border-radius: 28px; box-shadow: 0 18px 50px rgba(15, 23, 42, 0.08);">
    @if($article->thumbnail)
        <div style="height: 360px; overflow: hidden; background: #f3f4f6;"><img src="{{ $article->thumbnail }}" alt="{{ $article->title }}" style="width: 100%; height: 100%; object-fit: cover;"></div>
    @endif
    <div style="padding: 32px; display: flex; flex-direction: column; gap: 24px; background: #ffffff;">
        <div style="color: var(--clr-text-muted); font-size: 15px; line-height: 1.9; white-space: pre-line;">
            {!! nl2br(e($article->description ?? 'Konten artikel tidak tersedia.')) !!}
        </div>
    </div>
</div>

@endsection
