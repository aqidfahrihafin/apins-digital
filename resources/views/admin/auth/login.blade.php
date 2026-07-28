<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Admin — Apins Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-native.css') }}?v={{ config('app.asset_version', '1') }}">
</head>
<body class="login-body">
    <main class="login-shell">
        <section class="login-story"><span class="login-logo">APINS DIGITAL</span><div><span>Control center</span><h1>Kelola bisnis digital dari satu ruang kerja.</h1><p>Konten, proyek, pelanggan, dan performa situs tersinkron secara langsung.</p></div><small>© {{ date('Y') }} Apins Digital</small></section>
        <section class="login-panel"><form method="POST" action="{{ route('admin.login.store') }}">@csrf<span class="login-eyebrow">Selamat datang kembali</span><h2>Masuk ke admin</h2><p>Gunakan akun yang telah diberi akses.</p>@if($errors->any())<div class="admin-alert error">{{ $errors->first() }}</div>@endif<label>Email<input type="email" name="email" value="{{ old('email') }}" required autofocus></label><label>Password<input type="password" name="password" required></label><label class="remember"><input type="checkbox" name="remember"> Tetap masuk</label><button>Masuk ke workspace <span>→</span></button><a href="{{ route('home') }}">← Kembali ke website</a></form></section>
    </main>
</body>
</html>
