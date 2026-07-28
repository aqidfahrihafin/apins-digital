@extends('layouts.app')

@section('title', 'Karya & Studi Kasus — Apins Digital')

@section('content')
<section class="portfolio-hero">
    <div class="site-container inner-page-hero">
        <div>
            <span class="eyebrow">Karya kami</span>
            <h1>Produk digital yang dirancang untuk bekerja.</h1>
        </div>
        <p>Jelajahi solusi yang kami bangun untuk membantu bisnis bekerja lebih efektif, melayani pelanggan, dan bertumbuh.</p>
    </div>
</section>

<section class="section">
    <div class="site-container">
        @if($categories->isNotEmpty())
            <nav class="tag-filter" aria-label="Filter kategori karya">
                <a href="{{ route('portfolio.index') }}" class="{{ $category ? '' : 'active' }}">Semua karya</a>
                @foreach($categories as $item)
                    <a href="{{ route('portfolio.index', ['kategori' => $item->slug]) }}" class="{{ $category === $item->slug ? 'active' : '' }}">{{ $item->name }}</a>
                @endforeach
            </nav>
        @endif

        <div class="portfolio-grid portfolio-list-grid">
            @forelse($portfolios as $item)
                @php
                    $primaryImage = $item->images->firstWhere('is_primary', true) ?? $item->images->first();
                    $imageUrl = $primaryImage
                        ? Storage::disk('public')->url($primaryImage->image_url)
                        : 'https://placehold.co/1000x750/e8f5f2/0b172a?text='.urlencode($item->title);
                @endphp
                <article class="project-card reveal">
                    <a href="{{ route('portfolio.show', $item->slug) }}" class="project-image">
                        <img src="{{ $imageUrl }}" alt="{{ $primaryImage?->caption ?: $item->title }}" loading="lazy">
                        @if($item->is_featured)<span class="featured-badge">Pilihan</span>@endif
                        @if($item->images->count() > 1)
                            <span class="gallery-badge"><span class="material-symbols-rounded">photo_library</span>{{ $item->images->count() }} gambar</span>
                        @endif
                        <span class="project-open"><span class="material-symbols-rounded">arrow_outward</span></span>
                    </a>
                    <div class="project-info">
                        <span>{{ $item->category?->name ?? 'Digital Product' }}</span>
                        <h2><a href="{{ route('portfolio.show', $item->slug) }}">{{ $item->title }}</a></h2>
                        <p>{{ \Illuminate\Support\Str::limit($item->description, 125) }}</p>
                        <div class="project-card-footer">
                            <div class="tech-list">@foreach(array_slice($item->technologies ?? [], 0, 3) as $technology)<span>{{ $technology }}</span>@endforeach</div>
                            @if($item->live_url)<span class="demo-available"><i></i> Demo tersedia</span>@endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty-section">Belum ada karya pada kategori ini.</div>
            @endforelse
        </div>
        <div class="pagination-shell">{{ $portfolios->links() }}</div>
    </div>
</section>
@endsection
