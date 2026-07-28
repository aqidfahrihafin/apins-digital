@extends('layouts.app')

@section('title', $post->title.' — Apins Digital')
@section('meta_description', \Illuminate\Support\Str::limit($post->excerpt ?: strip_tags($post->content), 155))

@section('content')
<article>
    <header class="article-hero">
        <div class="site-container article-hero-inner">
            <div class="article-hero-top">
                <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ url('/') }}">Beranda</a><span>/</span><a href="{{ route('blog.index') }}">Insight</a></nav>
                <div class="article-tags">@foreach($post->tags as $tag)<a href="{{ route('blog.index', ['tag' => $tag->name]) }}">{{ $tag->name }}</a>@endforeach</div>
            </div>
            <div class="article-hero-editorial">
                <div><span class="article-kicker">Catatan dari tim Apins</span><h1>{{ $post->title }}</h1></div>
                <div class="article-summary">
                    @if($post->excerpt)<p class="article-lead">{{ $post->excerpt }}</p>@endif
                    <div class="article-stats" aria-label="Statistik artikel">
                        <span><span class="material-symbols-rounded">visibility</span>{{ number_format($post->view_count) }} kali dilihat</span>
                        <a href="#comments"><span class="material-symbols-rounded">chat_bubble</span>{{ number_format($post->approved_comments_count) }} komentar</a>
                    </div>
                </div>
            </div>
            <div class="article-meta-row">
                <div class="article-byline">
                    <span class="author-avatar">
                        @if($post->author?->avatar)
                            <img src="{{ Storage::disk('public')->url($post->author->avatar) }}" alt="" loading="lazy">
                        @else
                            {{ mb_strtoupper(mb_substr($post->author?->name ?? 'A', 0, 1)) }}
                        @endif
                    </span>
                    <div><strong>{{ $post->author?->name ?? 'Tim Apins Digital' }}</strong><span>{{ $post->published_at->translatedFormat('d F Y') }} · {{ max(1, ceil(str_word_count(strip_tags($post->content)) / 200)) }} menit baca</span></div>
                </div>
            </div>
        </div>
    </header>

    @if($post->featured_image)
        <div class="site-container article-cover"><img src="{{ Storage::disk('public')->url($post->featured_image) }}" alt="{{ $post->title }}"></div>
    @endif

    <div class="site-container article-layout">
        <div class="article-main">
            <div class="prose">{!! $post->content !!}</div>

            <div class="article-share">
                <div><span class="article-share-label">Bagikan artikel</span><small>Bantu insight ini menjangkau lebih banyak orang.</small></div>
                <div class="share-actions">
                    <button type="button" onclick="shareArticle()" aria-label="Bagikan artikel" title="Bagikan"><span class="material-symbols-rounded">ios_share</span></button>
                    <a href="https://wa.me/?text={{ urlencode($post->title.' '.route('blog.show', $post->slug)) }}" target="_blank" rel="noopener noreferrer" aria-label="Bagikan ke WhatsApp" title="WhatsApp"><span class="share-brand">WA</span></a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('blog.show', $post->slug)) }}" target="_blank" rel="noopener noreferrer" aria-label="Bagikan ke LinkedIn" title="LinkedIn"><span class="share-brand">in</span></a>
                    <button type="button" id="copyArticleLink" onclick="copyArticleLink()" aria-label="Salin tautan artikel" title="Salin tautan"><span class="material-symbols-rounded">link</span></button>
                </div>
            </div>

            <section class="comments-section" id="comments">
                <div class="comments-heading">
                    <div><span class="eyebrow">Diskusi</span><h2>Komentar <small>{{ $post->approved_comments_count }}</small></h2></div>
                    <p>Bagikan pandangan atau pertanyaan Anda tentang artikel ini.</p>
                </div>
                @forelse($post->comments as $comment)
                    <article class="comment-thread">
                        <div class="comment"><span class="author-avatar">{{ mb_strtoupper(mb_substr($comment->name, 0, 1)) }}</span><div><strong>{{ $comment->name }}</strong><time>{{ $comment->created_at->translatedFormat('d M Y') }}</time><p>{{ $comment->content }}</p><button type="button" class="reply-button" onclick="replyToComment({{ $comment->id }}, @js($comment->name))"><span class="material-symbols-rounded">reply</span> Balas</button></div></div>
                        @foreach($comment->replies as $reply)
                            <div class="comment comment-reply"><span class="author-avatar">{{ mb_strtoupper(mb_substr($reply->name, 0, 1)) }}</span><div><span class="reply-context">Membalas {{ $comment->name }}</span><strong>{{ $reply->name }}</strong><time>{{ $reply->created_at->translatedFormat('d M Y') }}</time><p>{{ $reply->content }}</p></div></div>
                        @endforeach
                    </article>
                @empty
                    <div class="comments-empty"><span class="material-symbols-rounded">forum</span><strong>Belum ada komentar</strong><p>Jadilah yang pertama memulai diskusi.</p></div>
                @endforelse
                @if(session('comment_success'))<div class="form-alert success">{{ session('comment_success') }}</div>@endif
                @if($errors->any())<div class="form-alert error"><strong>Komentar belum dapat dikirim.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                <form method="POST" action="{{ route('blog.comments.store', $post->slug) }}" class="comment-form">
                    @csrf
                    <input type="hidden" name="parent_id" id="commentParentId" value="{{ old('parent_id') }}">
                    <label class="comment-honeypot" aria-hidden="true">Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    <div class="reply-notice" id="replyNotice" hidden><span>Membalas <strong id="replyName"></strong></span><button type="button" onclick="cancelReply()" aria-label="Batalkan balasan"><span class="material-symbols-rounded">close</span></button></div>
                    <h3>Tinggalkan komentar</h3><p>Email Anda tidak akan dipublikasikan. Komentar normal langsung tampil; konten mencurigakan akan diperiksa.</p>
                    <div class="form-grid"><label><span>Nama</span><input name="name" value="{{ old('name') }}" required></label><label><span>Email</span><input type="email" name="email" value="{{ old('email') }}" required></label></div>
                    <label><span>Komentar</span><textarea name="content" rows="4" required>{{ old('content') }}</textarea></label>
                    <button class="button-primary" type="submit">Kirim untuk moderasi</button>
                </form>
            </section>
        </div>

        <aside class="article-aside">
            <div class="aside-card">
                <span class="eyebrow">Newsletter</span><h3>Insight baru, langsung ke inbox.</h3><p>Dapatkan artikel terbaru tanpa spam.</p>
                @if(session('newsletter_success'))<div class="form-alert success">{{ session('newsletter_success') }}</div>@endif
                <form method="POST" action="{{ route('newsletter.subscribe') }}">@csrf<input type="email" name="email" placeholder="Email Anda" required><button class="button-primary">Berlangganan</button></form>
            </div>
            @if($popularTags->isNotEmpty())<div class="aside-card"><h3>Topik populer</h3><div class="tag-cloud">@foreach($popularTags as $tag)<a href="{{ route('blog.index', ['tag' => $tag->name]) }}">{{ $tag->name }}</a>@endforeach</div></div>@endif
        </aside>
    </div>
</article>

@if($relatedPosts->isNotEmpty())
<section class="section section-soft">
    <div class="site-container">
        <div class="section-heading"><span class="eyebrow">Lanjut membaca</span><h2>Artikel terkait.</h2></div>
        <div class="articles-grid">@foreach($relatedPosts as $related)<article class="article-card"><div class="article-meta"><span>{{ $related->published_at->translatedFormat('d M Y') }}</span></div><h3><a href="{{ route('blog.show', $related->slug) }}">{{ $related->title }}</a></h3><p>{{ $related->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($related->content), 100) }}</p><a href="{{ route('blog.show', $related->slug) }}" class="text-link">Baca artikel <span class="material-symbols-rounded">arrow_forward</span></a></article>@endforeach</div>
    </div>
</section>
@endif

@push('scripts')
<script>
    async function shareArticle() {
        const data = { title: @json($post->title), text: @json($post->excerpt ?: $post->title), url: location.href };
        if (navigator.share) {
            try { await navigator.share(data); } catch (error) { if (error.name !== 'AbortError') copyArticleLink(); }
        } else {
            copyArticleLink();
        }
    }
    async function copyArticleLink() {
        const button = document.getElementById('copyArticleLink');
        try {
            await navigator.clipboard.writeText(location.href);
            button.classList.add('copied');
            button.title = 'Tautan tersalin';
            setTimeout(() => { button.classList.remove('copied'); button.title = 'Salin tautan'; }, 1800);
        } catch {
            window.prompt('Salin tautan artikel:', location.href);
        }
    }
    function replyToComment(id, name) {
        document.getElementById('commentParentId').value = id;
        document.getElementById('replyName').textContent = name;
        document.getElementById('replyNotice').hidden = false;
        document.querySelector('.comment-form').scrollIntoView({ behavior: 'smooth', block: 'center' });
        document.querySelector('.comment-form textarea').focus({ preventScroll: true });
    }
    function cancelReply() {
        document.getElementById('commentParentId').value = '';
        document.getElementById('replyNotice').hidden = true;
    }
</script>
@endpush
@endsection
