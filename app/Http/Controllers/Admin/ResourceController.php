<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPosts;
use App\Models\BlogCategory;
use App\Models\BlogTags;
use App\Models\Comments;
use App\Models\Portofolios;
use App\Models\PortofoliosImages;
use App\Models\PortofoliosCategory;
use App\Models\Service;
use App\Models\ServiceDetails;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class ResourceController extends Controller
{
    public function index(Request $request, string $resource)
    {
        [$definition, $model] = $this->resolve($resource);
        $query = $model->newQuery();
        $searchable = collect($model instanceof Role || $model instanceof Permission ? ['name', 'guard_name'] : $model->getFillable())
            ->reject(fn ($field) => $this->isSensitive($field))
            ->take(8);

        if ($search = trim((string) $request->query('q'))) {
            $query->where(function ($builder) use ($searchable, $search) {
                foreach ($searchable as $field) {
                    $builder->orWhere($field, 'like', "%{$search}%");
                }
            });
        }

        $columns = $this->columns($model);
        return view('admin.resources.index', [
            'resource' => $resource,
            'definition' => $definition,
            'records' => $query->latest()->paginate(15)->withQueryString(),
            'columns' => $columns,
            'relationLabels' => $columns->mapWithKeys(fn ($field) => [
                $field => $this->relationshipOptions($model, $field),
            ])->filter()->all(),
        ]);
    }

    public function create(string $resource)
    {
        [$definition, $model] = $this->resolve($resource);
        abort_if($definition['readonly'] ?? false, 403);

        return view('admin.resources.form', $this->formData($resource, $definition, $model));
    }

    public function store(Request $request, string $resource)
    {
        [$definition, $model] = $this->resolve($resource);
        abort_if($definition['readonly'] ?? false, 403);
        $record = $model->newInstance();
        $this->validateRecord($request, $record);
        $this->persist($request, $record);

        return redirect()->route('admin.resources.index', $resource)->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(string $resource, int $record)
    {
        [$definition, $model] = $this->resolve($resource);
        abort_if($definition['readonly'] ?? false, 403);
        $item = $model->newQuery()->findOrFail($record);

        return view('admin.resources.form', $this->formData($resource, $definition, $model, $item));
    }

    public function update(Request $request, string $resource, int $record)
    {
        [$definition, $model] = $this->resolve($resource);
        abort_if($definition['readonly'] ?? false, 403);
        $item = $model->newQuery()->findOrFail($record);
        $this->validateRecord($request, $item);
        $this->persist($request, $item);

        return redirect()->route('admin.resources.index', $resource)->with('success', 'Perubahan berhasil disimpan.');
    }

    public function destroy(string $resource, int $record)
    {
        [$definition, $model] = $this->resolve($resource);
        abort_if($definition['readonly'] ?? false, 403);
        abort_if($model instanceof User && auth()->id() === $record, 422, 'Akun yang sedang digunakan tidak dapat dihapus.');
        $model->newQuery()->findOrFail($record)->delete();

        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function toggle(string $resource, int $record, string $field)
    {
        [$definition, $model] = $this->resolve($resource);
        abort_if($definition['readonly'] ?? false, 403);
        abort_unless(str_starts_with($field, 'is_')
            && in_array($field, $model->getFillable())
            && Schema::hasColumn($model->getTable(), $field), 422);

        $item = $model->newQuery()->findOrFail($record);
        $item->{$field} = ! (bool) $item->{$field};
        $item->save();

        return back()->with('success', Str::headline($field).' berhasil diperbarui.');
    }

    private function resolve(string $resource): array
    {
        $definition = null;
        foreach (config('admin.groups') as $items) {
            if (isset($items[$resource])) {
                $definition = $items[$resource];
                break;
            }
        }
        abort_unless($definition, 404);
        if (auth()->user()?->hasRole('editor')) {
            abort_if(in_array($resource, [
                'users', 'roles', 'permissions', 'activity-logs',
                'settings', 'site-configs', 'contacts', 'newsletters',
            ]), 403);
        }

        return [$definition, app($definition['model'])];
    }

    private function formData(string $resource, array $definition, Model $model, ?Model $record = null): array
    {
        $baseFields = $model instanceof Role || $model instanceof Permission
            ? ['name']
            : $model->getFillable();
        $fields = collect($baseFields)
                ->reject(fn ($field) => in_array($field, ['slug', 'remember_token', 'email_verified_at', 'last_login', 'view_count']))
                ->reject(fn ($field) => $model instanceof \App\Models\Settings && $field === 'services')
                ->reject(fn ($field) => $model instanceof BlogPosts && $field === 'author_id')
                ->filter(fn ($field) => Schema::hasColumn($model->getTable(), $field))
                ->map(fn ($field) => $this->fieldDefinition($field, $model))
                ->values();

        if ($model instanceof User) {
            $fields->push([
                'name' => 'roles',
                'label' => 'Role Akses',
                'type' => 'multiselect',
                'options' => Role::query()->orderBy('name')->pluck('name', 'name'),
            ]);
        }
        if ($model instanceof Service) {
            $fields->push([
                'name' => 'details',
                'label' => 'Fitur layanan',
                'type' => 'repeater',
                'items' => $record?->details?->map(fn ($detail) => [
                    'feature' => $detail->feature,
                    'icon' => $detail->icon,
                ])->values()->all() ?? [],
            ]);
        }
        if ($model instanceof BlogPosts) {
            $fields->push([
                'name' => 'tags',
                'label' => 'Tag artikel',
                'type' => 'tag-select',
                'options' => BlogTags::query()->orderBy('name')->pluck('name', 'id'),
                'selected' => $record?->tags?->pluck('id')->all() ?? [],
            ]);
        }
        if ($model instanceof Portofolios) {
            $fields->push([
                'name' => 'gallery',
                'label' => 'Galeri karya',
                'type' => 'gallery',
                'items' => $record?->images?->map(fn ($image) => [
                    'id' => $image->id,
                    'url' => Storage::disk('public')->url($image->image_url),
                    'caption' => $image->caption,
                    'is_primary' => $image->is_primary,
                ])->values()->all() ?? [],
            ]);
        }
        if ($model instanceof Role) {
            $fields->push([
                'name' => 'permissions',
                'label' => 'Permission',
                'type' => 'permission-grid',
                'options' => Permission::query()->orderBy('name')->pluck('name', 'name'),
            ]);
        }

        $fieldGroups = $fields->groupBy(fn ($field) => $this->fieldGroup($field['name'], $field['type']));

        return compact('resource', 'definition', 'record', 'fields', 'fieldGroups');
    }

    private function fieldDefinition(string $field, ?Model $model = null): array
    {
        $options = $model ? $this->relationshipOptions($model, $field) : null;
        $type = match (true) {
            $options !== null => 'select',
            $field === 'password' => 'password',
            str_starts_with($field, 'is_') => 'boolean',
            str_ends_with($field, '_date'), $field === 'published_at' => 'date',
            str_contains($field, 'image'), $field === 'avatar', str_contains($field, 'logo') => 'file',
            $field === 'content' => 'richtext',
            in_array($field, ['description', 'challenge', 'solution', 'result', 'answer', 'message', 'subheadline']) => 'textarea',
            in_array($field, ['technologies']) => 'tags',
            str_ends_with($field, '_id'), in_array($field, ['order_index', 'rating']) => 'number',
            str_contains($field, 'email') => 'email',
            str_contains($field, 'url') => 'url',
            default => 'text',
        };

        return ['name' => $field, 'label' => Str::headline($field), 'type' => $type, 'options' => $options];
    }

    private function persist(Request $request, Model $record): void
    {
        if ($record instanceof Role) {
            $record->name = $request->string('name')->trim()->toString();
            $record->guard_name = 'web';
            $record->save();
            $record->syncPermissions($request->input('permissions', []));
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            return;
        }

        if ($record instanceof Permission) {
            $record->name = Str::snake($request->string('name')->trim()->toString());
            $record->guard_name = 'web';
            $record->save();
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            return;
        }

        $data = [];
        foreach ($record->getFillable() as $field) {
            if (! Schema::hasColumn($record->getTable(), $field)) {
                continue;
            }
            if ($this->isSensitive($field) && $field !== 'password') {
                continue;
            }

            $definition = $this->fieldDefinition($field, $record);
            if ($definition['type'] === 'boolean') {
                $data[$field] = $request->boolean($field);
            } elseif ($definition['type'] === 'file') {
                if ($request->hasFile($field)) {
                    $data[$field] = $request->file($field)->store('admin-uploads/'.Str::kebab(class_basename($record)), 'public');
                }
            } elseif ($field === 'password') {
                if ($request->filled($field)) {
                    $data[$field] = Hash::make($request->string($field));
                }
            } elseif ($definition['type'] === 'tags') {
                $data[$field] = collect(explode(',', (string) $request->input($field)))->map(fn ($value) => trim($value))->filter()->values()->all();
            } elseif ($request->exists($field)) {
                $data[$field] = $request->input($field) ?: null;
            }
        }

        if (! $record->exists && in_array('slug', $record->getFillable())) {
            $source = $data['title'] ?? $data['name'] ?? null;
            if ($source) {
                $data['slug'] = $this->uniqueSlug($record, $source);
            }
        }
        if ($record instanceof BlogPosts && ! $record->exists) {
            $data['author_id'] = auth()->id();
        }

        $record->fill($data)->save();

        if ($record instanceof Service) {
            $record->details()->delete();
            $order = 0;
            foreach ($request->input('details', []) as $detail) {
                if (! empty($detail['feature'])) {
                    $record->details()->create([
                        'feature' => $detail['feature'],
                        'icon' => $detail['icon'] ?: 'check_circle',
                        'order_index' => $order++,
                    ]);
                }
            }
        }
        if ($record instanceof BlogPosts) {
            $record->tags()->sync($request->input('tags', []));
        }
        if ($record instanceof Portofolios && $request->boolean('gallery_present')) {
            $this->syncPortfolioGallery($request, $record);
        }
        if ($record instanceof User && $request->exists('roles')) {
            $record->syncRoles($request->input('roles', []));
        }
    }

    private function columns(Model $model)
    {
        $fields = $model instanceof Role || $model instanceof Permission
            ? ['name', 'guard_name']
            : $model->getFillable();

        $available = collect($fields)
            ->filter(fn ($field) => Schema::hasColumn($model->getTable(), $field))
            ->reject(fn ($field) => $this->isSensitive($field) || in_array($field, ['slug', 'content', 'description', 'challenge', 'solution', 'result', 'message']));
        $statusFields = $available->filter(fn ($field) => str_starts_with($field, 'is_'));

        return $available
            ->reject(fn ($field) => str_starts_with($field, 'is_'))
            ->take(5)
            ->concat($statusFields)
            ->unique()
            ->values();
    }

    private function isSensitive(string $field): bool
    {
        return in_array($field, ['password', 'remember_token']);
    }

    private function relationshipOptions(Model $model, string $field): ?array
    {
        $query = match ($field) {
            'service_id' => Service::query(),
            'portofolios_id' => Portofolios::query(),
            'author_id', 'user_id' => User::query(),
            'post_id' => BlogPosts::query(),
            'tag_id' => BlogTags::query(),
            'parent_id' => Comments::query()->whereNull('parent_id'),
            'category_id' => $model instanceof BlogPosts ? BlogCategory::query() : PortofoliosCategory::query(),
            default => null,
        };

        if (! $query) {
            return null;
        }

        $labelColumn = match ($field) {
            'author_id', 'user_id', 'parent_id' => 'name',
            default => in_array($field, ['tag_id', 'category_id', 'service_id']) ? 'name' : 'title',
        };

        return $query->orderBy($labelColumn)->pluck($labelColumn, 'id')->all();
    }

    private function fieldGroup(string $field, string $type): string
    {
        return match (true) {
            in_array($field, ['name', 'title', 'slug', 'email', 'phone', 'position', 'client_name']) => 'Informasi utama',
            str_ends_with($field, '_id'), in_array($type, ['select', 'multiselect', 'permission-grid']) => 'Relasi & akses',
            in_array($type, ['textarea', 'richtext', 'tags', 'url', 'file', 'repeater', 'tag-select', 'gallery']) => 'Konten & media',
            str_starts_with($field, 'is_'), in_array($field, ['order_index', 'rating', 'start_date', 'end_date', 'published_at']) => 'Status & publikasi',
            default => 'Informasi tambahan',
        };
    }

    private function validateRecord(Request $request, Model $record): void
    {
        if ($record instanceof User) {
            $request->validate([
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'email', 'max:150', 'unique:users,email,'.$record->getKey()],
                'password' => [$record->exists ? 'nullable' : 'required', 'string', 'min:8'],
                'roles' => ['required', 'array', 'min:1'],
                'roles.*' => ['exists:roles,name'],
                'avatar' => ['nullable', 'image', 'max:2048'],
            ]);
        }

        if ($record instanceof Role) {
            $request->validate([
                'name' => ['required', 'string', 'max:100', 'unique:roles,name,'.$record->getKey()],
                'permissions' => ['nullable', 'array'],
                'permissions.*' => ['exists:permissions,name'],
            ]);
        }

        if ($record instanceof Permission) {
            $request->validate([
                'name' => ['required', 'string', 'max:100', 'unique:permissions,name,'.$record->getKey()],
            ]);
        }

        if ($record instanceof Portofolios) {
            $request->validate([
                'gallery.*.image' => ['nullable', 'image', 'max:4096'],
                'gallery.*.caption' => ['nullable', 'string', 'max:255'],
            ]);
        }
    }

    private function uniqueSlug(Model $model, string $source): string
    {
        $base = Str::slug($source) ?: Str::lower(Str::random(8));
        $slug = $base;
        $suffix = 2;

        while ($model->newQuery()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function syncPortfolioGallery(Request $request, Portofolios $portfolio): void
    {
        $rows = $request->input('gallery', []);
        $keptIds = collect($rows)->pluck('id')->filter()->map(fn ($id) => (int) $id);
        foreach ($portfolio->images()->whereNotIn('id', $keptIds)->get() as $removed) {
            Storage::disk('public')->delete($removed->image_url);
            $removed->delete();
        }

        $primaryKey = (string) $request->input('primary_image', '');
        $saved = collect();
        $order = 0;
        foreach ($rows as $key => $row) {
            $image = ! empty($row['id'])
                ? $portfolio->images()->find($row['id'])
                : new PortofoliosImages();
            if (! $image) {
                continue;
            }

            $upload = $request->file("gallery.{$key}.image");
            if ($upload) {
                if ($image->exists) {
                    Storage::disk('public')->delete($image->image_url);
                }
                $image->image_url = $upload->store('portofolios/images', 'public');
            }
            if (! $image->image_url) {
                continue;
            }

            $image->portofolios_id = $portfolio->id;
            $image->caption = $row['caption'] ?? null;
            $image->order_index = $order++;
            $image->is_primary = (string) $key === $primaryKey;
            $image->save();
            $saved->push($image);
        }

        if ($saved->isNotEmpty() && ! $saved->contains('is_primary', true)) {
            $saved->first()->update(['is_primary' => true]);
        }
    }
}
