<section id="home" class="hero-section">
    <div class="site-container hero-grid">
        <div class="hero-copy reveal">
            <span class="eyebrow">{{ $setting->hero_title ?: 'Digital product studio' }}</span>
            <h1>{!! $setting->headline !!}</h1>
            <p>{{ $setting->subheadline ?: $setting->description }}</p>
            <div class="hero-actions">
                <a href="{{ $setting->consultation_url ?: '#contact' }}" class="button-primary">
                    {{ $setting->consultation_button_text ?: 'Konsultasi gratis' }}
                    <span class="material-symbols-rounded">arrow_forward</span>
                </a>
                <a href="{{ $setting->portfolio_url ?: '#portfolio' }}" class="button-secondary">
                    {{ $setting->portfolio_button_text ?: 'Lihat portofolio' }}
                </a>
            </div>
            <div class="hero-proof">
                <div class="proof-avatars">
                    @foreach($teams->take(3) as $member)
                        <span>{{ mb_strtoupper(mb_substr($member->user->name, 0, 1)) }}</span>
                    @endforeach
                </div>
                <p><strong>{{ $totalProjects }}+ proyek</strong><br>dipercaya bisnis lintas industri</p>
            </div>
        </div>

        <div class="hero-visual reveal">
            @if($setting->image)
                <img src="{{ Storage::disk('public')->url($setting->image) }}" alt="Tim Apins Digital membantu transformasi bisnis" class="hero-image">
            @else
                <div class="product-window">
                    <div class="window-bar"><i></i><i></i><i></i><span>apins.digital/workspace</span></div>
                    <div class="window-body">
                        <div class="mini-sidebar"><b></b><i></i><i></i><i></i></div>
                        <div class="mini-content">
                            <div class="mini-label">PROJECT OVERVIEW</div>
                            <div class="mini-title"></div>
                            <div class="mini-title short"></div>
                            <div class="mini-cards"><i></i><i></i><i></i></div>
                            <div class="mini-chart"><span></span><span></span><span></span><span></span><span></span><span></span></div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="floating-note note-top"><span class="material-symbols-rounded">verified</span><div><strong>Production ready</strong><small>Aman dan mudah dikembangkan</small></div></div>
            <div class="floating-note note-bottom"><span class="pulse-dot"></span><div><strong>99.9% uptime</strong><small>Monitoring sepanjang waktu</small></div></div>
        </div>
    </div>
</section>
