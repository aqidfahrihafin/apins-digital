@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('heading', 'Ringkasan bisnis')
@section('content')
<section class="dashboard-welcome"><div><span>{{ now()->translatedFormat('l, d F Y') }}</span><h1>Halo, {{ Str::before(auth()->user()->name, ' ') }}.</h1><p>Berikut kondisi terbaru website dan interaksi pelanggan Anda.</p></div><a href="{{ route('admin.resources.create', 'blog-posts') }}"><span class="material-symbols-rounded">add</span>Tulis artikel</a></section>
<div class="stats-grid">@foreach($stats as $stat)<article><span class="stat-icon {{ $stat['tone'] }}"><span class="material-symbols-rounded">{{ $stat['icon'] }}</span></span><div><small>{{ $stat['label'] }}</small><strong>{{ number_format($stat['value']) }}</strong></div></article>@endforeach</div>
<section class="analytics-section">
    <header class="analytics-heading"><div><span class="card-kicker">Website analytics</span><h2>Performa 30 hari terakhir</h2><p>Data internal, tanpa menyimpan alamat IP mentah pengunjung.</p></div><span class="analytics-live"><i></i> Tracking aktif</span></header>
    <div class="analytics-kpis">
        <article><span class="material-symbols-rounded">visibility</span><div><small>Page views</small><strong>{{ number_format($analytics['views30Days']) }}</strong><em>{{ number_format($analytics['views7Days']) }} dalam 7 hari</em></div></article>
        <article><span class="material-symbols-rounded">group</span><div><small>Pengunjung unik</small><strong>{{ number_format($analytics['visitors30Days']) }}</strong><em>Berdasarkan sesi anonim</em></div></article>
        <article><span class="material-symbols-rounded">speed</span><div><small>Respons rata-rata</small><strong>{{ $analytics['avgResponseMs'] }}<b> ms</b></strong><em>{{ $analytics['avgResponseMs'] <= 500 ? 'Performa baik' : 'Perlu diperiksa' }}</em></div></article>
        <article><span class="material-symbols-rounded">ads_click</span><div><small>Konversi kontak</small><strong>{{ $analytics['conversionRate'] }}<b>%</b></strong><em>Pengunjung menjadi pesan</em></div></article>
    </div>
    <div class="analytics-grid">
        <article class="admin-card traffic-chart-card">
            <header><div><span class="card-kicker">Tren kunjungan</span><h2>14 hari terakhir</h2></div></header>
            @php $maxDaily = max(1, $analytics['dailyViews']->max('value')); @endphp
            <div class="traffic-chart" aria-label="Grafik kunjungan 14 hari">
                @foreach($analytics['dailyViews'] as $day)<div class="chart-column" title="{{ $day['date'] }}: {{ $day['value'] }} kunjungan"><span class="chart-value">{{ $day['value'] }}</span><i style="height: {{ max(4, ($day['value'] / $maxDaily) * 100) }}%"></i><small>{{ $loop->odd ? $day['date'] : '' }}</small></div>@endforeach
            </div>
        </article>
        <article class="admin-card device-card">
            <header><div><span class="card-kicker">Perangkat</span><h2>Cara pengunjung membuka situs</h2></div></header>
            @php $deviceTotal = max(1, $analytics['deviceStats']->sum()); $deviceLabels = ['desktop' => 'Desktop', 'mobile' => 'Mobile', 'tablet' => 'Tablet']; @endphp
            <div class="device-list">@forelse($deviceLabels as $device => $label)@php $count = $analytics['deviceStats'][$device] ?? 0; $percentage = round(($count/$deviceTotal)*100); @endphp<div><span class="material-symbols-rounded">{{ $device === 'mobile' ? 'smartphone' : ($device === 'tablet' ? 'tablet_mac' : 'desktop_windows') }}</span><div><strong>{{ $label }}</strong><i><b style="width:{{ $percentage }}%"></b></i></div><em>{{ $percentage }}%</em></div>@empty<p class="empty-admin">Belum ada data.</p>@endforelse</div>
        </article>
        <article class="admin-card popular-pages">
            <header><div><span class="card-kicker">Konten teratas</span><h2>Halaman paling sering dikunjungi</h2></div></header>
            <div class="ranking-list">@forelse($analytics['popularPages'] as $page)<div><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><div><strong>{{ $page->title ?: $page->path }}</strong><small>{{ $page->path }} · {{ number_format($page->visitors) }} pengunjung</small></div><em>{{ number_format($page->total) }}</em></div>@empty<p class="empty-admin">Data kunjungan akan tampil setelah website dikunjungi.</p>@endforelse</div>
        </article>
        <article class="admin-card referrer-card">
            <header><div><span class="card-kicker">Akuisisi</span><h2>Sumber kunjungan</h2></div></header>
            <div class="referrer-list">@forelse($analytics['referrerStats'] as $source)<div><span class="material-symbols-rounded">public</span><strong>{{ $source->referrer_host }}</strong><em>{{ number_format($source->total) }}</em></div>@empty<div class="empty-state-small"><span class="material-symbols-rounded">travel_explore</span><p>Belum ada kunjungan dari situs eksternal.</p></div>@endforelse</div>
        </article>
    </div>
</section>
<div class="dashboard-grid">
    <section class="admin-card wide"><header><div><span class="card-kicker">CRM</span><h2>Pesan terbaru</h2></div><a href="{{ route('admin.resources.index', 'contacts') }}">Lihat semua</a></header><div class="compact-list">@forelse($latestContacts as $item)<div><span class="list-avatar">{{ mb_strtoupper(mb_substr($item->name,0,1)) }}</span><div><strong>{{ $item->name }}</strong><small>{{ Str::limit($item->message, 60) }}</small></div><time>{{ $item->created_at->diffForHumans() }}</time></div>@empty<p class="empty-admin">Belum ada pesan.</p>@endforelse</div></section>
    <section class="admin-card"><header><div><span class="card-kicker">Perlu ditinjau</span><h2>Moderasi</h2></div></header><div class="compact-list">@forelse($pendingComments as $item)<div><span class="list-avatar amber">{{ mb_strtoupper(mb_substr($item->name,0,1)) }}</span><div><strong>{{ $item->name }}</strong><small>{{ Str::limit($item->content, 45) }}</small></div></div>@empty<p class="empty-admin">Semua komentar sudah bersih.</p>@endforelse</div></section>
    <section class="admin-card wide"><header><div><span class="card-kicker">Publikasi</span><h2>Artikel terakhir</h2></div><a href="{{ route('admin.resources.index', 'blog-posts') }}">Kelola artikel</a></header><div class="compact-list">@forelse($latestPosts as $item)<div><span class="list-icon material-symbols-rounded">article</span><div><strong>{{ $item->title }}</strong><small>{{ $item->author?->name ?? 'Tanpa penulis' }}</small></div><span class="status-pill {{ $item->is_published ? 'live' : '' }}">{{ $item->is_published ? 'Terbit' : 'Draf' }}</span></div>@empty<p class="empty-admin">Belum ada artikel.</p>@endforelse</div></section>
    <section class="admin-card quick-summary"><span class="card-kicker">Kapasitas</span><h2>Sistem aktif</h2><div><strong>{{ $activeUsers }}</strong><small>pengguna aktif</small></div><div><strong>{{ $activeServices }}</strong><small>layanan tersedia</small></div></section>
</div>
@endsection
