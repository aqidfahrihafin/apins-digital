<section class="section faq-section">
    <div class="site-container faq-grid">
        <div class="section-heading reveal">
            <span class="eyebrow">Pertanyaan umum</span>
            <h2>Sebelum kita mulai.</h2>
            <p>Jawaban singkat untuk beberapa hal yang sering ditanyakan calon klien.</p>
            <a href="#contact" class="text-link faq-contact">Masih punya pertanyaan? Hubungi kami <span class="material-symbols-rounded">arrow_forward</span></a>
        </div>
        <div class="faq-list reveal">
            @forelse($faqs as $index => $faq)
                <article class="faq-item {{ $index === 0 ? 'active' : '' }}">
                    <button type="button" onclick="toggleAccordion(this)" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                        <span>{{ $faq->question }}</span><span class="material-symbols-rounded">add</span>
                    </button>
                    <div class="faq-answer"><p>{{ $faq->answer }}</p></div>
                </article>
            @empty
                <div class="empty-section">FAQ sedang disiapkan.</div>
            @endforelse
        </div>
    </div>
</section>
