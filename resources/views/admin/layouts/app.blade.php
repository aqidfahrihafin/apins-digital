<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <title>@yield('title', 'Admin') — Apins Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,300..700,0..1,-50..200">
    <link rel="stylesheet" href="{{ asset('css/admin-native.css') }}?v={{ config('app.asset_version', '1') }}">
</head>
<body>
    <aside class="admin-sidebar" id="adminSidebar">
        <a class="admin-brand" href="{{ route('admin.dashboard') }}"><span class="admin-brand-mark"><i></i><i></i><i></i><i></i></span><span>APINS <b>CONTROL</b></span></a>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><span class="material-symbols-rounded">space_dashboard</span><span>Ringkasan</span></a>
            @foreach(config('admin.groups') as $group => $resources)
                <p>{{ $group }}</p>
                @foreach($resources as $key => $item)
                    @continue(auth()->user()->hasRole('editor') && in_array($key, ['users', 'roles', 'permissions', 'activity-logs', 'settings', 'site-configs', 'contacts', 'newsletters']))
                    <a href="{{ route('admin.resources.index', $key) }}" class="{{ request()->route('resource') === $key ? 'active' : '' }}"><span class="material-symbols-rounded">{{ $item['icon'] }}</span><span>{{ $item['label'] }}</span></a>
                @endforeach
            @endforeach
        </nav>
        <div class="admin-user">
            <a class="admin-user-avatar" href="{{ route('admin.profile.edit') }}" aria-label="Buka profil">
                @if(auth()->user()->avatar)<img src="{{ Storage::disk('public')->url(auth()->user()->avatar) }}" alt="">@else{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}@endif
            </a>
            <a class="admin-user-detail" href="{{ route('admin.profile.edit') }}"><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->getRoleNames()->first() ?? 'Administrator' }} · Profil</small></a>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button title="Keluar"><span class="material-symbols-rounded">logout</span></button></form>
        </div>
    </aside>
    <main class="admin-main">
        <header class="admin-topbar">
            <button class="admin-menu-toggle" type="button" onclick="document.body.classList.toggle('sidebar-open')"><span class="material-symbols-rounded">menu</span></button>
            <div><span>Workspace</span><strong>@yield('heading', 'Dashboard')</strong></div>
            <a href="{{ route('home') }}" target="_blank">Lihat situs <span class="material-symbols-rounded">open_in_new</span></a>
        </header>
        <div class="admin-content">
            @if(session('success'))<div class="admin-alert success"><span class="material-symbols-rounded">check_circle</span>{{ session('success') }}</div>@endif
            @if($errors->any())<div class="admin-alert error"><span class="material-symbols-rounded">error</span>{{ $errors->first() }}</div>@endif
            @yield('content')
        </div>
    </main>
</body>
</html>
