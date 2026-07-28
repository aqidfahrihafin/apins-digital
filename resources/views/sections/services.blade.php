<section id="services" class="section section-soft">
    <div class="site-container">
        <div class="section-heading reveal">
            <span class="eyebrow">Yang kami kerjakan</span>
            <h2>Teknologi yang menyelesaikan masalah bisnis.</h2>
            <p>Dari ide sampai produk siap digunakan, tim kami membantu Anda memilih solusi yang tepat—tanpa jargon yang membingungkan.</p>
        </div>
        <div class="services-grid">
            @forelse($services as $index => $service)
                <article class="service-card reveal">
                    <div class="service-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="service-icon"><span class="material-symbols-rounded">{{ $service->icon ?: 'apps' }}</span></div>
                    <h3>{{ $service->name }}</h3>
                    <p>{{ $service->short_desc }}</p>
                    @if($service->details->isNotEmpty())
                        <ul>
                            @foreach($service->details->take(3) as $detail)
                                <li><span class="material-symbols-rounded">check</span>{{ $detail->feature }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <button type="button" onclick="openModal('service-{{ $service->id }}')" class="text-link">
                        Pelajari layanan <span class="material-symbols-rounded">arrow_outward</span>
                    </button>
                    <template id="service-{{ $service->id }}">
                        <div class="modal-kicker">Layanan Apins Digital</div>
                        <h3 class="modal-title">{{ $service->name }}</h3>
                        <p class="modal-copy">{{ $service->long_desc ?: $service->short_desc }}</p>
                        <div class="modal-feature-list">
                            @foreach($service->details as $detail)
                                <div><span class="material-symbols-rounded">{{ $detail->icon ?: 'check_circle' }}</span>{{ $detail->feature }}</div>
                            @endforeach
                        </div>
                        <a href="#contact" onclick="closeModal()" class="button-primary modal-cta">Diskusikan kebutuhan Anda</a>
                    </template>
                </article>
            @empty
                <div class="empty-section">Layanan sedang disiapkan.</div>
            @endforelse
        </div>
    </div>
</section>
