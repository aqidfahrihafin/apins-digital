@extends('admin.layouts.app')
@section('title', ($record ? 'Edit ' : 'Tambah ').$definition['label'])
@section('heading', $definition['label'])
@section('content')
<div class="resource-head"><div><a class="back-link" href="{{ route('admin.resources.index', $resource) }}">← Kembali</a><h1>{{ $record ? 'Edit '.$definition['label'] : 'Tambah '.$definition['label'] }}</h1><p>Informasi dikelompokkan agar lebih cepat dipahami dan diperiksa.</p></div></div>
<form class="admin-form-card grouped-form" method="POST" enctype="multipart/form-data" action="{{ $record ? route('admin.resources.update', [$resource, $record->getKey()]) : route('admin.resources.store', $resource) }}">@csrf @if($record)@method('PUT')@endif
    @foreach($fieldGroups as $groupName => $groupFields)
        <section class="form-group-section">
            <header><span class="form-group-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><div><h2>{{ $groupName }}</h2><p>{{ $groupName === 'Relasi & akses' ? 'Pilih data terkait menggunakan nama yang mudah dikenali.' : 'Lengkapi informasi yang diperlukan pada kelompok ini.' }}</p></div></header>
            <div class="admin-form-grid">
                @foreach($groupFields as $field)
                    <div class="field-control {{ in_array($field['type'], ['textarea', 'richtext', 'permission-grid', 'repeater', 'tag-select', 'gallery']) ? 'full' : '' }}"><span class="field-label">{{ $field['label'] }}</span>
                    @if($field['type'] === 'textarea')<textarea name="{{ $field['name'] }}" rows="5">{{ old($field['name'], $record?->{$field['name']}) }}</textarea>
                    @elseif($field['type'] === 'richtext')
                        <div class="rich-editor" data-editor>
                            <div class="rich-toolbar" role="toolbar" aria-label="Format teks">
                                <button type="button" data-command="formatBlock" data-value="h2" title="Judul"><span class="material-symbols-rounded">title</span></button>
                                <button type="button" data-command="bold" title="Tebal"><span class="material-symbols-rounded">format_bold</span></button>
                                <button type="button" data-command="italic" title="Miring"><span class="material-symbols-rounded">format_italic</span></button>
                                <button type="button" data-command="insertUnorderedList" title="Daftar"><span class="material-symbols-rounded">format_list_bulleted</span></button>
                                <button type="button" data-command="insertOrderedList" title="Daftar bernomor"><span class="material-symbols-rounded">format_list_numbered</span></button>
                                <button type="button" data-command="formatBlock" data-value="blockquote" title="Kutipan"><span class="material-symbols-rounded">format_quote</span></button>
                                <button type="button" data-command="createLink" title="Tautan"><span class="material-symbols-rounded">link</span></button>
                                <button type="button" data-command="removeFormat" title="Hapus format"><span class="material-symbols-rounded">format_clear</span></button>
                            </div>
                            <div class="rich-content" contenteditable="true" role="textbox" aria-multiline="true">{!! old($field['name'], $record?->{$field['name']}) !!}</div>
                            <textarea name="{{ $field['name'] }}" hidden>{{ old($field['name'], $record?->{$field['name']}) }}</textarea>
                            <div class="rich-footer"><span>Tulis konten artikel dengan format yang mudah dibaca.</span><span data-word-count>0 kata</span></div>
                        </div>
                    @elseif($field['type'] === 'boolean')<label class="toggle-field"><input type="checkbox" name="{{ $field['name'] }}" value="1" @checked(old($field['name'], $record?->{$field['name']}))><i></i><small><b>Aktif</b><span>Klik untuk mengubah status</span></small></label>
                    @elseif($field['type'] === 'file')<input type="file" name="{{ $field['name'] }}" accept="image/*">@if($record?->{$field['name']})<small>File saat ini: {{ basename($record->{$field['name']}) }}</small>@endif
                    @elseif($field['type'] === 'multiselect')<select name="{{ $field['name'] }}[]" multiple size="{{ min(5, max(2, count($field['options']))) }}">@foreach($field['options'] as $value => $label)<option value="{{ $value }}" @selected(collect(old($field['name'], $record?->getRoleNames() ?? []))->contains($value))>{{ Str::headline($label) }}</option>@endforeach</select><small>Pilih satu atau beberapa role akses.</small>
                    @elseif($field['type'] === 'permission-grid')
                        @php $selectedPermissions = collect(old('permissions', $record?->permissions?->pluck('name') ?? [])); @endphp
                        <div class="permission-grid">@foreach($field['options']->groupBy(fn ($name) => Str::after($name, '_')) as $permissionGroup => $permissions)<fieldset><legend>{{ Str::headline($permissionGroup) }}</legend>@foreach($permissions as $permission)<label><input type="checkbox" name="permissions[]" value="{{ $permission }}" @checked($selectedPermissions->contains($permission))><span>{{ Str::headline(Str::before($permission, '_')) }}</span></label>@endforeach</fieldset>@endforeach</div>
                    @elseif($field['type'] === 'tag-select')
                        @php $selectedTags = collect(old('tags', $field['selected'])); @endphp
                        <div class="tag-choice-grid">@forelse($field['options'] as $value => $label)<label><input type="checkbox" name="tags[]" value="{{ $value }}" @checked($selectedTags->contains($value))><span>{{ $label }}</span></label>@empty<p>Belum ada tag. Tambahkan dahulu melalui menu Tag artikel.</p>@endforelse</div>
                    @elseif($field['type'] === 'repeater')
                        @php $detailItems = old('details', $field['items']); @endphp
                        <div class="repeater-field" id="serviceDetails">
                            <div class="repeater-list">
                                @foreach($detailItems as $index => $item)<div class="repeater-row"><span class="drag-handle material-symbols-rounded">drag_indicator</span><input name="details[{{ $index }}][feature]" value="{{ $item['feature'] ?? '' }}" placeholder="Contoh: Dashboard analitik"><input name="details[{{ $index }}][icon]" value="{{ $item['icon'] ?? '' }}" placeholder="Nama ikon (opsional)"><button type="button" onclick="this.parentElement.remove()" aria-label="Hapus fitur"><span class="material-symbols-rounded">close</span></button></div>@endforeach
                            </div>
                            <button class="add-repeater" type="button" onclick="addServiceDetail()"><span class="material-symbols-rounded">add</span>Tambah fitur layanan</button>
                        </div>
                    @elseif($field['type'] === 'gallery')
                        <input type="hidden" name="gallery_present" value="1">
                        <div class="gallery-admin" id="portfolioGallery">
                            <div class="gallery-admin-list">
                                @foreach($field['items'] as $index => $item)
                                    <div class="gallery-admin-row">
                                        <img src="{{ $item['url'] }}" alt="">
                                        <input type="hidden" name="gallery[{{ $index }}][id]" value="{{ $item['id'] }}">
                                        <div><input type="file" name="gallery[{{ $index }}][image]" accept="image/*"><input type="text" name="gallery[{{ $index }}][caption]" value="{{ $item['caption'] }}" placeholder="Caption gambar"></div>
                                        <label class="primary-choice"><input type="radio" name="primary_image" value="{{ $index }}" @checked($item['is_primary'])><span class="material-symbols-rounded">star</span>Utama</label>
                                        <button type="button" onclick="this.parentElement.remove()" aria-label="Hapus gambar"><span class="material-symbols-rounded">delete</span></button>
                                    </div>
                                @endforeach
                            </div>
                            <button class="add-repeater" type="button" onclick="addPortfolioImage()"><span class="material-symbols-rounded">add_photo_alternate</span>Tambah gambar</button>
                            <small>Gambar pertama otomatis menjadi gambar utama jika belum dipilih.</small>
                        </div>
                    @elseif($field['type'] === 'select')<select name="{{ $field['name'] }}"><option value="">Pilih {{ strtolower($field['label']) }}</option>@foreach($field['options'] as $value => $label)<option value="{{ $value }}" @selected((string) old($field['name'], $record?->{$field['name']}) === (string) $value)>{{ $label }}</option>@endforeach</select>
                    @else<input type="{{ $field['type'] === 'tags' ? 'text' : $field['type'] }}" name="{{ $field['name'] }}" value="{{ old($field['name'], $field['type'] === 'tags' ? collect($record?->{$field['name']} ?? [])->join(', ') : $record?->{$field['name']}) }}" @if($field['type']==='tags') placeholder="Pisahkan dengan koma" @endif @if($field['name']==='password' && $record) placeholder="Kosongkan jika tidak diubah" @endif>@endif
                    @error($field['name'])<small class="field-error">{{ $message }}</small>@enderror
                    </div>
                @endforeach
            </div>
        </section>
    @endforeach
    <div class="form-actions"><a href="{{ route('admin.resources.index', $resource) }}">Batal</a><button class="admin-primary"><span class="material-symbols-rounded">save</span>{{ $record ? 'Simpan perubahan' : 'Tambahkan data' }}</button></div>
</form>
<script>
    document.querySelectorAll('[data-editor]').forEach(editor => {
        const content = editor.querySelector('.rich-content');
        const textarea = editor.querySelector('textarea');
        const wordCount = editor.querySelector('[data-word-count]');
        const sync = () => {
            textarea.value = content.innerHTML;
            const words = content.innerText.trim().split(/\s+/).filter(Boolean).length;
            wordCount.textContent = `${words} kata`;
        };
        editor.querySelectorAll('[data-command]').forEach(button => button.addEventListener('click', () => {
            let value = button.dataset.value || null;
            if (button.dataset.command === 'createLink') {
                value = prompt('Masukkan alamat tautan:', 'https://');
                if (!value) return;
            }
            content.focus();
            document.execCommand(button.dataset.command, false, value);
            sync();
        }));
        content.addEventListener('input', sync);
        content.addEventListener('blur', sync);
        editor.closest('form').addEventListener('submit', sync);
        sync();
    });

    function addServiceDetail() {
        const list = document.querySelector('#serviceDetails .repeater-list');
        const index = Date.now();
        const row = document.createElement('div');
        row.className = 'repeater-row';
        row.innerHTML = `<span class="drag-handle material-symbols-rounded">drag_indicator</span><input name="details[${index}][feature]" placeholder="Contoh: Dashboard analitik"><input name="details[${index}][icon]" placeholder="Nama ikon (opsional)"><button type="button" onclick="this.parentElement.remove()" aria-label="Hapus fitur"><span class="material-symbols-rounded">close</span></button>`;
        list.appendChild(row);
        row.querySelector('input').focus();
    }

    function addPortfolioImage() {
        const list = document.querySelector('#portfolioGallery .gallery-admin-list');
        const index = `new_${Date.now()}`;
        const row = document.createElement('div');
        row.className = 'gallery-admin-row new';
        row.innerHTML = `<div class="gallery-placeholder"><span class="material-symbols-rounded">image</span></div><div><input type="file" name="gallery[${index}][image]" accept="image/*" required><input type="text" name="gallery[${index}][caption]" placeholder="Caption gambar"></div><label class="primary-choice"><input type="radio" name="primary_image" value="${index}"><span class="material-symbols-rounded">star</span>Utama</label><button type="button" onclick="this.parentElement.remove()" aria-label="Hapus gambar"><span class="material-symbols-rounded">delete</span></button>`;
        list.appendChild(row);
        row.querySelector('input[type=file]').click();
    }
</script>
@endsection
