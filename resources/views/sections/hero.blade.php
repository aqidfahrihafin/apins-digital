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
            <div class="hero-capabilities" aria-label="Fokus layanan">
                <span>Website & aplikasi</span><span>IoT & integrasi</span><span>Desain digital</span>
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
                            <div class="mini-label">DARI IDE MENJADI NYATA</div>
                            <h2 class="workspace-title">Ruang untuk<br>ide besar Anda.</h2>
                            <p class="workspace-subtitle">Satu partner, setiap tahap perjalanan.</p>
                            <div class="workspace-cards">
                                <div><span class="material-symbols-rounded" aria-hidden="true">draw</span><strong>Desain</strong><small>Berpusat pada pengguna</small></div>
                                <div><span class="material-symbols-rounded" aria-hidden="true">code</span><strong>Bangun</strong><small>Presisi di setiap detail</small></div>
                            </div>
                            <div class="workspace-progress"><span>Ide</span><i></i><span>Pengembangan</span><i></i><strong>Siap tumbuh</strong></div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="floating-note note-top"><span class="material-symbols-rounded">verified</span><div><strong>Production ready</strong><small>Aman dan mudah dikembangkan</small></div></div>
            <div class="floating-note note-bottom"><span class="pulse-dot"></span><div><strong>Dibangun untuk bertumbuh</strong><small>Dari langkah pertama hingga skala berikutnya</small></div></div>
        </div>
    </div>
</section>
