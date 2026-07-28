<section id="portfolio" class="section">
    <div class="site-container">
        <div class="section-heading-row reveal">
            <div class="section-heading">
                <span class="eyebrow">Karya terpilih</span>
                <h2>Produk digital yang memberi dampak nyata.</h2>
                <p>Lihat bagaimana kami mengubah kebutuhan operasional menjadi produk yang efektif dan mudah dipakai.</p>
            </div>
            <a href="{{ route('portfolio.index') }}" class="section-cta">
                <span class="section-cta-icon material-symbols-rounded">grid_view</span>
                <span>
                    <small>Jelajahi studi kasus</small>
                    <strong>Lihat semua karya</strong>
                </span>
                <span class="material-symbols-rounded section-cta-arrow">arrow_forward</span>
            </a>
        </div>
        <div class="portfolio-grid">
            @forelse($portfolios as $item)
                @php
                    $portfolioImage = $item->images->firstWhere('is_primary', true) ?? $item->images->first();
                    $portfolioImageUrl = $portfolioImage
                        ? Storage::disk('public')->url($portfolioImage->image_url)
                        : 'https://placehold.co/1000x750/e8f5f2/0b172a?text='.urlencode($item->title);
                @endphp
                <article class="project-card reveal">
                    <a href="{{ route('portfolio.show', $item->slug) }}" class="project-image" aria-label="Lihat detail {{ $item->title }}">
                        <img src="{{ $portfolioImageUrl }}" alt="{{ $item->title }}" loading="lazy">
                        @if($item->is_featured)<span class="featured-badge">Pilihan</span>@endif
                        <span class="project-open"><span class="material-symbols-rounded">arrow_outward</span></span>
                    </a>
                    <div class="project-info">
                        <span>{{ $item->category?->name ?? 'Digital Product' }}</span>
                        <h3>{{ $item->title }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($item->description, 110) }}</p>
                        <div class="tech-list">
                            @foreach(array_slice($item->technologies ?? [], 0, 3) as $technology)<span>{{ $technology }}</span>@endforeach
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty-section">Portofolio sedang disiapkan.</div>
            @endforelse
        </div>
    </div>
</section>
