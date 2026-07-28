@extends('layouts.app')

@section('title', 'Insight & Artikel — Apins Digital')

@section('content')
<section class="blog-hero">
    <div class="site-container inner-page-hero">
        <div>
            <span class="eyebrow">Insight Apins</span>
            <h1>Belajar membangun produk digital yang lebih baik.</h1>
        </div>
        <p>Wawasan praktis tentang teknologi, desain produk, dan transformasi bisnis dari tim kami.</p>
    </div>
</section>

<section class="section">
    <div class="site-container">
        @if($popularTags->isNotEmpty())
            <nav class="tag-filter" aria-label="Filter artikel">
                <a href="{{ route('blog.index') }}" class="{{ request('tag') ? '' : 'active' }}">Semua</a>
                @foreach($popularTags as $tag)<a href="{{ route('blog.index', ['tag' => $tag->name]) }}" class="{{ request('tag') === $tag->name ? 'active' : '' }}">{{ $tag->name }}</a>@endforeach
            </nav>
        @endif
        <div class="articles-grid blog-list-grid">
            @forelse($posts as $post)
                <article class="article-card reveal">
                    <a href="{{ route('blog.show', $post->slug) }}" class="article-image">
                        <img src="{{ $post->featured_image ? Storage::disk('public')->url($post->featured_image) : 'https://placehold.co/900x600/e8f5f2/0b172a?text='.urlencode($post->title) }}" alt="{{ $post->title }}" loading="lazy">
                    </a>
                    <div class="article-meta"><span>{{ $post->published_at->translatedFormat('d M Y') }}</span><span>{{ max(1, ceil(str_word_count(strip_tags($post->content)) / 200)) }} menit baca</span></div>
                    <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                    <p>{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 120) }}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="text-link">Baca artikel <span class="material-symbols-rounded">arrow_forward</span></a>
                </article>
            @empty
                <div class="empty-section">Belum ada artikel pada kategori ini.</div>
            @endforelse
        </div>
        <div class="pagination-shell">{{ $posts->links() }}</div>
    </div>
</section>
@endsection
