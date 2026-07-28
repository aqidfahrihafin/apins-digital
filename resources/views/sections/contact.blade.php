<section id="contact" class="section contact-section">
    <div class="site-container contact-shell">
        <div class="contact-copy reveal">
            <span class="eyebrow">Mulai percakapan</span>
            <h2>Ceritakan apa yang ingin Anda bangun.</h2>
            <p>Tidak perlu menyiapkan dokumen teknis. Ceritakan masalah bisnisnya, tim kami akan membantu memetakan langkah berikutnya.</p>
            <div class="contact-points">
                <div><span class="material-symbols-rounded">schedule</span><p><strong>Respons cepat</strong><small>Tim kami merespons dalam 1 hari kerja.</small></p></div>
                <div><span class="material-symbols-rounded">lock</span><p><strong>Tanpa komitmen awal</strong><small>Konsultasi pertama gratis dan rahasia.</small></p></div>
                <div><span class="material-symbols-rounded">mail</span><p><strong>{{ $siteConfig['email'] ?? 'info@apinsdigital.com' }}</strong><small>{{ $siteConfig['phone'] ?? 'Hubungi kami via email' }}</small></p></div>
            </div>
        </div>
        <div class="contact-form-card reveal">
            @if(session('success'))<div class="form-alert success">{{ session('success') }}</div>@endif
            @if($errors->any())<div class="form-alert error"><strong>Periksa kembali data berikut:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form method="POST" action="{{ route('contact.submit') }}">
                @csrf
                <div class="form-grid">
                    <label><span>Nama lengkap</span><input name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Nama Anda"></label>
                    <label><span>Email</span><input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@perusahaan.com"></label>
                    <label><span>Nomor telepon <small>(opsional)</small></span><input name="phone" value="{{ old('phone') }}" autocomplete="tel" placeholder="+62"></label>
                    <label><span>Layanan</span><select name="service"><option value="">Pilih layanan</option>@foreach($services as $service)<option value="{{ $service->name }}" @selected(old('service') === $service->name)>{{ $service->name }}</option>@endforeach</select></label>
                </div>
                <label><span>Ceritakan kebutuhan Anda</span><textarea name="message" rows="5" required placeholder="Contoh: Kami ingin membuat dashboard untuk memantau operasional...">{{ old('message') }}</textarea></label>
                <button type="submit" class="button-primary submit-button">Kirim pesan <span class="material-symbols-rounded">arrow_forward</span></button>
                <p class="form-note">Dengan mengirim formulir ini, Anda menyetujui kami menghubungi Anda terkait kebutuhan tersebut.</p>
            </form>
        </div>
    </div>
</section>
