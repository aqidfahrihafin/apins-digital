<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="description" content="@yield('meta_description', $siteConfig['meta_description'] ?? 'Apins Digital membantu bisnis membangun website, aplikasi, IoT, dan sistem digital yang mudah digunakan.')">
    <meta name="theme-color" content="#0b172a">
    <title>@yield('title', ($siteConfig['site_name'] ?? 'Apins Digital').' — Solusi Digital untuk Bisnis')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,300..700,0..1,-50..200">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}?v={{ filemtime(public_path('css/frontend.css')) }}">
    @stack('styles')
</head>
<body>
    <a href="#main-content" class="skip-link">Lewati ke konten utama</a>
    @include('partials.navbar')

    <main id="main-content">
        @yield('content')
    </main>

    @include('layouts.footer')
    @include('partials.modals')
    @include('partials.scripts')
    @stack('scripts')
</body>
</html>
