<section id="blog" class="section section-soft">
    <div class="site-container">
        <div class="section-heading-row reveal">
            <div class="section-heading">
                <span class="eyebrow">Insight</span>
                <h2>Ide praktis untuk bisnis digital.</h2>
                <p>Catatan dari tim kami seputar teknologi, produk, dan transformasi bisnis.</p>
            </div>
            <a href="{{ route('blog.index') }}" class="section-cta section-cta-compact">
                <span>
                    <small>Jelajahi insight lainnya</small>
                    <strong>Lihat semua artikel</strong>
                </span>
                <span class="material-symbols-rounded section-cta-arrow">arrow_forward</span>
            </a>
        </div>
        <div class="articles-grid">
            @forelse($blogs as $post)
                <article class="article-card reveal">
                    <a href="{{ route('blog.show', $post->slug) }}" class="article-image">
                        <img src="{{ $post->featured_image ? Storage::disk('public')->url($post->featured_image) : 'https://placehold.co/900x600/e8f5f2/0b172a?text='.urlencode($post->title) }}" alt="{{ $post->title }}" loading="lazy">
                    </a>
                    <div class="article-meta"><span>{{ $post->published_at->translatedFormat('d M Y') }}</span><span>{{ max(1, ceil(str_word_count(strip_tags($post->content)) / 200)) }} menit baca</span></div>
                    <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                    <p>{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 115) }}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="text-link">Baca selengkapnya <span class="material-symbols-rounded">arrow_forward</span></a>
                </article>
            @empty
                <div class="empty-section">Artikel sedang disiapkan.</div>
            @endforelse
        </div>
    </div>
</section>
