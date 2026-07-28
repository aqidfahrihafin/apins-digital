@extends('admin.layouts.app')

@section('title', 'Profil Saya')
@section('heading', 'Profil Saya')

@section('content')
<div class="profile-heading">
    <div><span class="card-kicker">Akun pribadi</span><h1>Profil saya</h1><p>Kelola identitas yang digunakan di dashboard dan sebagai penulis artikel.</p></div>
    <span class="profile-security"><span class="material-symbols-rounded">verified_user</span>Akun terlindungi</span>
</div>

<div class="profile-layout">
    <aside class="profile-summary admin-card">
        <div class="profile-avatar-large">@if($user->avatar)<img src="{{ Storage::disk('public')->url($user->avatar) }}" alt="{{ $user->name }}">@else{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}@endif</div>
        <h2>{{ $user->name }}</h2>
        <p>{{ $user->email }}</p>
        <span class="profile-role">{{ $user->getRoleNames()->first() ?? 'Administrator' }}</span>
        <dl>
            <div><dt>Status akun</dt><dd><i></i>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</dd></div>
            <div><dt>Login terakhir</dt><dd>{{ $user->last_login?->translatedFormat('d M Y, H:i') ?? 'Belum tercatat' }}</dd></div>
            <div><dt>Terdaftar sejak</dt><dd>{{ $user->created_at?->translatedFormat('d M Y') }}</dd></div>
        </dl>
        <small>Foto dan nama Anda juga digunakan pada informasi penulis artikel di frontend.</small>
    </aside>

    <div class="profile-forms">
        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="admin-card profile-form">
            @csrf @method('PUT')
            <header><div><span class="card-kicker">Informasi dasar</span><h2>Identitas akun</h2><p>Pastikan informasi ini selalu akurat dan mudah dikenali.</p></div><span class="material-symbols-rounded">badge</span></header>
            <div class="profile-photo-field">
                <div class="profile-photo-preview">@if($user->avatar)<img src="{{ Storage::disk('public')->url($user->avatar) }}" alt="">@else<span>{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span>@endif</div>
                <div>
                    <label class="upload-button"><span class="material-symbols-rounded">add_a_photo</span>Ganti foto<input type="file" name="avatar" accept="image/jpeg,image/png,image/webp"></label>
                    <small>JPG, PNG, atau WebP. Maksimal 2 MB.</small>
                    @if($user->avatar)<label class="remove-avatar"><input type="checkbox" name="remove_avatar" value="1"> Hapus foto saat disimpan</label>@endif
                    @error('avatar')<small class="field-error">{{ $message }}</small>@enderror
                </div>
            </div>
            <div class="profile-fields">
                <label><span>Nama lengkap</span><input name="name" value="{{ old('name', $user->name) }}" required>@error('name')<small class="field-error">{{ $message }}</small>@enderror</label>
                <label><span>Email</span><input type="email" name="email" value="{{ old('email', $user->email) }}" required>@error('email')<small class="field-error">{{ $message }}</small>@enderror</label>
                <label><span>Nomor telepon</span><input name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 0812 3456 7890">@error('phone')<small class="field-error">{{ $message }}</small>@enderror</label>
                <label><span>Role</span><input value="{{ $user->getRoleNames()->join(', ') ?: 'Administrator' }}" disabled><small>Role dikelola melalui menu pengguna dan hak akses.</small></label>
            </div>
            <div class="profile-form-actions"><button class="admin-primary" type="submit"><span class="material-symbols-rounded">save</span>Simpan profil</button></div>
        </form>

        <form method="POST" action="{{ route('admin.profile.password') }}" class="admin-card profile-form password-form">
            @csrf @method('PUT')
            <header><div><span class="card-kicker">Keamanan</span><h2>Ubah password</h2><p>Gunakan kombinasi huruf dan angka yang tidak mudah ditebak.</p></div><span class="material-symbols-rounded">lock</span></header>
            <div class="profile-fields">
                <label class="full"><span>Password saat ini</span><input type="password" name="current_password" autocomplete="current-password" required>@error('current_password')<small class="field-error">{{ $message }}</small>@enderror</label>
                <label><span>Password baru</span><input type="password" name="password" autocomplete="new-password" required>@error('password')<small class="field-error">{{ $message }}</small>@enderror</label>
                <label><span>Konfirmasi password baru</span><input type="password" name="password_confirmation" autocomplete="new-password" required></label>
            </div>
            <div class="profile-form-actions"><button class="admin-primary" type="submit"><span class="material-symbols-rounded">key</span>Perbarui password</button></div>
        </form>
    </div>
</div>
@endsection
