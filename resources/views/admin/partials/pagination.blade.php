@if($paginator->hasPages())
<nav class="modern-pagination" aria-label="Navigasi halaman">
    <div><strong>{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</strong><span>dari {{ $paginator->total() }} data</span></div>
    <div class="pagination-buttons">
        @if($paginator->onFirstPage())<span class="disabled"><span class="material-symbols-rounded">arrow_back</span></span>@else<a href="{{ $paginator->previousPageUrl() }}" aria-label="Halaman sebelumnya"><span class="material-symbols-rounded">arrow_back</span></a>@endif
        @foreach($paginator->getUrlRange(max(1, $paginator->currentPage()-2), min($paginator->lastPage(), $paginator->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}" class="{{ $page === $paginator->currentPage() ? 'active' : '' }}" @if($page === $paginator->currentPage()) aria-current="page" @endif>{{ $page }}</a>
        @endforeach
        @if($paginator->hasMorePages())<a href="{{ $paginator->nextPageUrl() }}" aria-label="Halaman berikutnya"><span class="material-symbols-rounded">arrow_forward</span></a>@else<span class="disabled"><span class="material-symbols-rounded">arrow_forward</span></span>@endif
    </div>
</nav>
@endif
