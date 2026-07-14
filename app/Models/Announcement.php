<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'user_id',
        'service_id',
        'priority',
        'views',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function reads()
    {
        return $this->hasMany(AnnouncementRead::class);
    }

    public function comments()
    {
        return $this->hasMany(AnnouncementComment::class)
                    ->latest();
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isReadBy(User $user)
    {
        return $this->reads()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function readersCount()
    {
        return $this->reads()->count();
    }
}