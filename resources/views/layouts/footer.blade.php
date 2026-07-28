<footer class="site-footer">
    <div class="site-container">
        <div class="footer-top">
            <div class="footer-brand">
                <a href="{{ url('/') }}" class="brand"><span class="brand-mark"><span></span><span></span><span></span><span></span></span><span>{{ $siteConfig['site_name'] ?? 'APINS DIGITAL' }}</span></a>
                <p>{{ $siteConfig['footer_description'] ?? 'Solusi digital yang jelas, efektif, dan siap bertumbuh bersama bisnis Anda.' }}</p>
            </div>
            <div><h3>Jelajahi</h3><a href="{{ url('/#services') }}">Layanan</a><a href="{{ url('/#portfolio') }}">Portofolio</a><a href="{{ url('/#about') }}">Tentang kami</a><a href="{{ route('blog.index') }}">Insight</a></div>
            <div><h3>Layanan</h3>@forelse($footerServices as $service)<a href="{{ url('/#services') }}">{{ $service->name }}</a>@empty<a href="{{ url('/#contact') }}">Konsultasi digital</a>@endforelse</div>
            <div><h3>Hubungi</h3><a href="mailto:{{ $siteConfig['email'] ?? 'info@apinsdigital.com' }}">{{ $siteConfig['email'] ?? 'info@apinsdigital.com' }}</a><a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteConfig['phone'] ?? '') }}">{{ $siteConfig['phone'] ?? '-' }}</a><p>{{ $siteConfig['address'] ?? 'Jakarta, Indonesia' }}</p></div>
        </div>
        <div class="footer-bottom"><span>© {{ date('Y') }} {{ $siteConfig['company_name'] ?? 'CV Apins Digital' }}</span><span>Dibangun dengan ketelitian.</span></div>
    </div>
</footer>
