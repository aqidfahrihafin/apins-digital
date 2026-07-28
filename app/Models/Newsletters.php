<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletters extends Model
{
    protected $table = 'newsletters';

    protected $fillable = ['email', 'is_active', 'subscribed_at', 'unsubscribed_at'];

    protected $casts = [
        'is_active' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    public static function subscribe(string $email): self
    {
        $subscriber = self::where('email', $email)->first();
        if ($subscriber) {
            $subscriber->update(['is_active' => true, 'unsubscribed_at' => null]);
            return $subscriber;
        }
        return self::create(['email' => $email, 'is_active' => true, 'subscribed_at' => now()]);
    }
}
