<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name'];

public function users()
{
    return $this->hasMany(User::class);
}

public function announcements()
{
    return $this->hasMany(Announcement::class);
}
}
