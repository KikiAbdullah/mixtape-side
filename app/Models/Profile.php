<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id', 'display_name', 'avatar_url', 'bio', 'location'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
