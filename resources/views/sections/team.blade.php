<section id="team" class="section section-soft">
    <div class="site-container">
        <div class="section-heading-row reveal">
            <div class="section-heading">
                <span class="eyebrow">Tim kami</span>
                <h2>Orang-orang di balik setiap produk.</h2>
                <p>Tim lintas disiplin yang menggabungkan pemahaman bisnis, desain, dan engineering.</p>
            </div>
            @if($teams->count() > 1)
                <div class="team-controls" aria-label="Navigasi anggota tim">
                    <button type="button" onclick="scrollTeam(-1)" aria-label="Geser tim ke kiri"><span class="material-symbols-rounded">arrow_back</span></button>
                    <button type="button" onclick="scrollTeam(1)" aria-label="Geser tim ke kanan"><span class="material-symbols-rounded">arrow_forward</span></button>
                </div>
            @endif
        </div>
        <div class="team-track" id="teamTrack">
            @forelse($teams as $member)
                <article class="team-card reveal">
                    <div class="team-photo {{ $member->user->avatar ? 'has-photo' : 'team-photo-fallback' }}">
                        @if($member->user->avatar)
                            <img src="{{ Storage::disk('public')->url($member->user->avatar) }}" alt="{{ $member->user->name }}" loading="lazy">
                        @else
                            <span class="team-grid-mark" aria-hidden="true"><i></i><i></i><i></i><i></i></span>
                            <span class="team-role-icon material-symbols-rounded" aria-hidden="true">north_east</span>
                            <strong aria-hidden="true">{{ collect(preg_split('/\s+/', trim($member->user->name)))->filter()->take(2)->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))->implode('') }}</strong>
                            <small>Apins team</small>
                        @endif
                    </div>
                    <div class="team-info">
                        <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <div><h3>{{ $member->user->name }}</h3><p>{{ $member->position }}</p></div>
                    </div>
                </article>
            @empty
                <div class="empty-section">Profil tim sedang disiapkan.</div>
            @endforelse
        </div>
    </div>
</section>
