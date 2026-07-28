<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteConfigs extends Model
{
    protected $table = 'site_configs';

    protected $fillable = ['config_key', 'config_value', 'group_name', 'description'];

    protected static function booted()
    {
        static::saved(fn() => Cache::forget('site_configs'));
        static::deleted(fn() => Cache::forget('site_configs'));
    }

    public static function get(string $key, $default = null)
    {
        $configs = Cache::remember('site_configs', 3600, fn() => self::all()->pluck('config_value', 'config_key')->toArray());
        return $configs[$key] ?? $default;
    }

    public static function set(string $key, $value, ?string $group = null): self
    {
        return self::updateOrCreate(
            ['config_key' => $key],
            ['config_value' => $value, 'group_name' => $group ?? 'general']
        );
    }
}
