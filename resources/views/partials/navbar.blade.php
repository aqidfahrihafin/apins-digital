<header class="site-header" id="siteHeader">
    <div class="site-container nav-shell">
        <a href="{{ url('/') }}" class="brand" aria-label="Apins Digital, kembali ke beranda">
            <span class="brand-mark"><span></span><span></span><span></span><span></span></span>
            <span>{{ $siteConfig['site_name'] ?? 'APINS DIGITAL' }}</span>
        </a>

        <nav class="desktop-nav" aria-label="Navigasi utama">
            <a href="{{ url('/#services') }}">Layanan</a>
            <a href="{{ url('/#portfolio') }}">Portofolio</a>
            <a href="{{ url('/#about') }}">Tentang</a>
            <a href="{{ route('blog.index') }}">Insight</a>
        </nav>

        <div class="nav-actions">
            <a href="{{ url('/#contact') }}" class="nav-cta">Konsultasi gratis</a>
            <button id="mobileMenuBtn" class="menu-button" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="mobileMenu">
                <span class="material-symbols-rounded">menu</span>
            </button>
        </div>
    </div>

    <nav id="mobileMenu" class="mobile-menu" aria-label="Navigasi mobile" hidden>
        <div class="mobile-menu-inner">
            <div class="mobile-menu-intro">
                <span>Jelajahi Apins</span>
                <p>Temukan solusi, karya, dan insight yang relevan untuk bisnis Anda.</p>
            </div>
            <div class="mobile-nav-grid">
                <a href="{{ url('/#services') }}">
                    <span class="mobile-nav-number">01</span>
                    <span class="material-symbols-rounded">deployed_code</span>
                    <strong>Layanan</strong>
                    <small>Solusi yang kami kerjakan</small>
                </a>
                <a href="{{ route('portfolio.index') }}">
                    <span class="mobile-nav-number">02</span>
                    <span class="material-symbols-rounded">grid_view</span>
                    <strong>Karya</strong>
                    <small>Produk & studi kasus</small>
                </a>
                <a href="{{ url('/#about') }}">
                    <span class="mobile-nav-number">03</span>
                    <span class="material-symbols-rounded">groups</span>
                    <strong>Tentang</strong>
                    <small>Cara kami bekerja</small>
                </a>
                <a href="{{ route('blog.index') }}">
                    <span class="mobile-nav-number">04</span>
                    <span class="material-symbols-rounded">auto_stories</span>
                    <strong>Insight</strong>
                    <small>Ide praktis digital</small>
                </a>
            </div>
            <a href="{{ url('/#contact') }}" class="mobile-cta">
                <span><small>Punya ide atau kebutuhan?</small><strong>Mulai konsultasi</strong></span>
                <span class="material-symbols-rounded">north_east</span>
            </a>
            <div class="mobile-menu-foot">
                <span>Terhubung dengan kami</span>
                <a href="mailto:{{ $siteConfig['contact_email'] ?? 'hello@apinsdigital.com' }}">{{ $siteConfig['contact_email'] ?? 'hello@apinsdigital.com' }}</a>
            </div>
        </div>
    </nav>
</header>
