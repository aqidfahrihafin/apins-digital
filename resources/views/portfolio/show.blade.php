@extends('layouts.app')

@section('title', $portfolio->title.' — Apins Digital')
@section('meta_description', \Illuminate\Support\Str::limit($portfolio->description, 155))

@section('content')
<header class="project-hero">
    <div class="site-container project-hero-grid">
        <div>
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Beranda</a><span>/</span>
                <a href="{{ route('portfolio.index') }}">Karya</a><span>/</span>
                <span>{{ $portfolio->title }}</span>
            </nav>
            <span class="eyebrow">{{ $portfolio->category?->name ?? 'Studi kasus' }}</span>
            <h1>{{ $portfolio->title }}</h1>
            <p>{{ $portfolio->description }}</p>
            <div class="project-actions">
                @if($portfolio->live_url)
                    <a class="button-primary" href="{{ $portfolio->live_url }}" target="_blank" rel="noopener noreferrer">Buka demo <span class="material-symbols-rounded">open_in_new</span></a>
                @endif
                <a class="button-secondary" href="#project-story">Lihat studi kasus</a>
            </div>
        </div>
        <dl class="project-facts">
            @if($portfolio->client_name)<div><dt>Klien</dt><dd>{{ $portfolio->client_name }}</dd></div>@endif
            <div><dt>Kategori</dt><dd>{{ $portfolio->category?->name ?? 'Produk digital' }}</dd></div>
            @if($portfolio->end_date)<div><dt>Selesai</dt><dd>{{ $portfolio->end_date->translatedFormat('M Y') }}</dd></div>@endif
            <div><dt>Teknologi</dt><dd>{{ collect($portfolio->technologies ?? [])->take(3)->join(', ') ?: 'Solusi khusus' }}</dd></div>
        </dl>
    </div>
</header>

@php
    $primaryImage = $portfolio->images->firstWhere('is_primary', true) ?? $portfolio->images->first();
@endphp
@if($primaryImage)
    <section class="project-gallery site-container" aria-label="Galeri {{ $portfolio->title }}">
        <button class="gallery-main" type="button" onclick="openGallery(0)" aria-label="Perbesar gambar {{ $primaryImage->caption ?: $portfolio->title }}">
            <img src="{{ Storage::disk('public')->url($primaryImage->image_url) }}" alt="{{ $primaryImage->caption ?: $portfolio->title }}">
            <span><span class="material-symbols-rounded">zoom_in</span> Perbesar</span>
        </button>
        @if($portfolio->images->count() > 1)
            <div class="gallery-thumbs">
                @foreach($portfolio->images as $index => $image)
                    <button type="button" onclick="openGallery({{ $index }})" aria-label="Lihat gambar {{ $index + 1 }}">
                        <img src="{{ Storage::disk('public')->url($image->image_url) }}" alt="{{ $image->caption ?: $portfolio->title.' gambar '.($index + 1) }}" loading="lazy">
                        @if($image->caption)<small>{{ $image->caption }}</small>@endif
                    </button>
                @endforeach
            </div>
        @endif
    </section>
@endif

<section class="section" id="project-story">
    <div class="site-container project-story">
        <div class="project-narrative">
            @if($portfolio->challenge)<div><span>01</span><article><h2>Tantangan</h2><p>{{ $portfolio->challenge }}</p></article></div>@endif
            @if($portfolio->solution)<div><span>02</span><article><h2>Solusi</h2><p>{{ $portfolio->solution }}</p></article></div>@endif
            @if($portfolio->result)<div><span>03</span><article><h2>Hasil</h2><p>{{ $portfolio->result }}</p></article></div>@endif
        </div>
        <aside class="project-sidebar">
            <span class="eyebrow">Stack proyek</span>
            <div class="tech-list">@foreach($portfolio->technologies ?? [] as $technology)<span>{{ $technology }}</span>@endforeach</div>
            @if($portfolio->live_url)
                <a class="button-primary project-demo-button" href="{{ $portfolio->live_url }}" target="_blank" rel="noopener noreferrer">Kunjungi demo <span class="material-symbols-rounded">open_in_new</span></a>
            @endif
            <a href="{{ route('home') }}#contact" class="text-link">Diskusikan proyek serupa <span class="material-symbols-rounded">arrow_forward</span></a>
        </aside>
    </div>
</section>

@if($relatedPortfolios->isNotEmpty())
<section class="section section-soft">
    <div class="site-container">
        <div class="section-heading"><span class="eyebrow">Karya lainnya</span><h2>Proyek yang mungkin relevan.</h2></div>
        <div class="portfolio-grid related-projects">
            @foreach($relatedPortfolios as $item)
                @php $image = $item->images->firstWhere('is_primary', true) ?? $item->images->first(); @endphp
                <article class="project-card">
                    <a class="project-image" href="{{ route('portfolio.show', $item->slug) }}">
                        <img src="{{ $image ? Storage::disk('public')->url($image->image_url) : 'https://placehold.co/1000x750/e8f5f2/0b172a?text='.urlencode($item->title) }}" alt="{{ $item->title }}" loading="lazy">
                    </a>
                    <div class="project-info"><span>{{ $item->category?->name }}</span><h3><a href="{{ route('portfolio.show', $item->slug) }}">{{ $item->title }}</a></h3></div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($portfolio->images->isNotEmpty())
<div class="lightbox" id="projectLightbox" role="dialog" aria-modal="true" aria-label="Galeri gambar" onclick="if(event.target === this) closeGallery()">
    <button type="button" class="lightbox-close" onclick="closeGallery()" aria-label="Tutup galeri"><span class="material-symbols-rounded">close</span></button>
    <button type="button" class="lightbox-nav lightbox-prev" onclick="changeGallery(-1)" aria-label="Gambar sebelumnya"><span class="material-symbols-rounded">arrow_back</span></button>
    <figure><img id="lightboxImage" src="" alt=""><figcaption id="lightboxCaption"></figcaption></figure>
    <button type="button" class="lightbox-nav lightbox-next" onclick="changeGallery(1)" aria-label="Gambar berikutnya"><span class="material-symbols-rounded">arrow_forward</span></button>
</div>
@push('scripts')
<script>
    const projectImages = @json($portfolio->images->map(fn ($image) => ['src' => Storage::disk('public')->url($image->image_url), 'caption' => $image->caption ?: $portfolio->title])->values());
    let galleryIndex = 0;
    function renderGallery() {
        document.getElementById('lightboxImage').src = projectImages[galleryIndex].src;
        document.getElementById('lightboxImage').alt = projectImages[galleryIndex].caption;
        document.getElementById('lightboxCaption').textContent = `${galleryIndex + 1} / ${projectImages.length} — ${projectImages[galleryIndex].caption}`;
    }
    function openGallery(index) { galleryIndex = index; renderGallery(); document.getElementById('projectLightbox').classList.add('active'); document.body.style.overflow = 'hidden'; }
    function closeGallery() { document.getElementById('projectLightbox').classList.remove('active'); document.body.style.overflow = ''; }
    function changeGallery(direction) { galleryIndex = (galleryIndex + direction + projectImages.length) % projectImages.length; renderGallery(); }
    document.addEventListener('keydown', (event) => {
        if (!document.getElementById('projectLightbox').classList.contains('active')) return;
        if (event.key === 'Escape') closeGallery();
        if (event.key === 'ArrowLeft') changeGallery(-1);
        if (event.key === 'ArrowRight') changeGallery(1);
    });
</script>
@endpush
@endif
@endsection
