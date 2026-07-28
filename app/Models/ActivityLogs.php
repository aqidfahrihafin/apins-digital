<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLogs extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = ['user_id', 'action', 'module', 'description', 'ip_address', 'user_agent', 'old_data', 'new_data'];

    protected $casts = ['old_data' => 'array', 'new_data' => 'array'];

    public static function log(?User $user, string $action, ?string $module = null, ?string $description = null): self
    {
        return self::create([
            'user_id' => $user?->id,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
