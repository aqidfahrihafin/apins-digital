@if($testimonials->isNotEmpty())
<section class="section testimonial-section">
    <div class="site-container testimonial-shell reveal">
        <div class="testimonial-intro">
            <span class="eyebrow">Cerita klien</span>
            <h2>Kepercayaan yang tumbuh dari hasil nyata.</h2>
        </div>
        <div class="quote-mark" aria-hidden="true">&ldquo;</div>
        <div id="testimonialTrack">
            @foreach($testimonials as $index => $testi)
                <article class="testimonial-slide {{ $index === 0 ? 'active' : '' }}">
                    <div class="rating" aria-label="{{ $testi->rating }} dari 5 bintang">
                        @for($i = 0; $i < 5; $i++)
                            <span class="material-symbols-rounded {{ $i < $testi->rating ? 'filled' : '' }}">star</span>
                        @endfor
                    </div>
                    <blockquote>{{ $testi->content }}</blockquote>
                    <footer>
                        <span class="testimonial-avatar" aria-hidden="true">{{ collect(explode(' ', trim($testi->name)))->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->join('') }}</span>
                        <span class="testimonial-person">
                            <strong>{{ $testi->name }}</strong>
                            <span>{{ $testi->position }}{{ $testi->company ? ' · '.$testi->company : '' }}</span>
                        </span>
                    </footer>
                </article>
            @endforeach
        </div>
        @if($testimonials->count() > 1)
            <div class="testimonial-controls">
                <button type="button" onclick="changeTestimonial(-1)" aria-label="Testimoni sebelumnya"><span class="material-symbols-rounded">arrow_back</span></button>
                <span class="testimonial-count"><b id="testimonialCurrent">01</b> / {{ str_pad($testimonials->count(), 2, '0', STR_PAD_LEFT) }}</span>
                <button type="button" onclick="changeTestimonial(1)" aria-label="Testimoni berikutnya"><span class="material-symbols-rounded">arrow_forward</span></button>
            </div>
        @endif
    </div>
</section>
@endif
