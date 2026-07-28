@extends('admin.layouts.app')
@section('title', $definition['label'])
@section('heading', $definition['label'])
@section('content')
<div class="resource-head">
    <div><span class="card-kicker">Manajemen data</span><h1>{{ $definition['label'] }}</h1><p>Kelola data yang tersinkron langsung dengan website.</p></div>
    @unless($definition['readonly'] ?? false)<a class="admin-primary" href="{{ route('admin.resources.create', $resource) }}"><span class="material-symbols-rounded">add</span>Tambah data</a>@endunless
</div>
<section class="admin-card resource-card">
    <div class="resource-toolbar">
        <form class="resource-search"><span class="material-symbols-rounded">search</span><input name="q" value="{{ request('q') }}" placeholder="Cari {{ strtolower($definition['label']) }}..."><button>Cari</button></form>
        <span class="table-count">{{ $records->total() }} data</span>
    </div>
    <div class="table-wrap"><table><thead><tr><th>Data</th>@foreach($columns as $column)<th>{{ Str::headline(str_replace('_id', '', $column)) }}</th>@endforeach<th>Diperbarui</th><th></th></tr></thead><tbody>
    @forelse($records as $record)
        <tr>
            <td><span class="record-id">#{{ str_pad($record->getKey(), 4, '0', STR_PAD_LEFT) }}</span></td>
            @foreach($columns as $column)
                @php
                    $value = $record->{$column};
                    $display = isset($relationLabels[$column]) ? ($relationLabels[$column][$value] ?? 'Data tidak tersedia') : $value;
                @endphp
                <td>
                    @if(str_starts_with($column, 'is_'))
                        <form method="POST" action="{{ route('admin.resources.toggle', [$resource, $record->getKey(), $column]) }}" class="quick-toggle-form">@csrf @method('PATCH')
                            <label class="quick-toggle" title="Klik untuk mengubah status">
                                <input type="checkbox" @checked($value) onchange="this.form.submit()">
                                <i></i><span>{{ $value ? (in_array($column, ['is_approved', 'is_published']) ? 'Ya' : 'Aktif') : (in_array($column, ['is_approved', 'is_published']) ? 'Tidak' : 'Nonaktif') }}</span>
                            </label>
                        </form>
                    @elseif(isset($relationLabels[$column]))
                        <span class="relation-value"><span class="material-symbols-rounded">link</span>{{ $display }}</span>
                    @elseif(is_array($value))
                        <div class="table-tags">@foreach(array_slice($value, 0, 3) as $tag)<span>{{ is_array($tag) ? ($tag['name'] ?? $tag['title'] ?? $tag['label'] ?? collect($tag)->filter(fn ($item) => is_scalar($item))->first() ?? 'Item') : $tag }}</span>@endforeach</div>
                    @elseif(str_contains($column, 'image') || in_array($column, ['avatar', 'client_logo']))
                        @if($value)<img class="table-thumb" src="{{ Storage::disk('public')->url($value) }}" alt="">@else<span class="table-empty">Tanpa gambar</span>@endif
                    @else
                        {{ Str::limit((string) $display, 55) ?: '—' }}
                    @endif
                </td>
            @endforeach
            <td><span class="updated-cell">{{ $record->updated_at?->diffForHumans() ?? '—' }}</span></td>
            <td><div class="row-actions">@unless($definition['readonly'] ?? false)<a title="Edit" href="{{ route('admin.resources.edit', [$resource, $record->getKey()]) }}"><span class="material-symbols-rounded">edit</span></a><form method="POST" action="{{ route('admin.resources.destroy', [$resource, $record->getKey()]) }}" onsubmit="return confirm('Hapus data ini?')">@csrf @method('DELETE')<button title="Hapus"><span class="material-symbols-rounded">delete</span></button></form>@endunless</div></td>
        </tr>
    @empty<tr><td colspan="{{ $columns->count()+3 }}"><div class="empty-admin">Belum ada data yang dapat ditampilkan.</div></td></tr>@endforelse
    </tbody></table></div>
    @include('admin.partials.pagination', ['paginator' => $records])
</section>
@endsection
