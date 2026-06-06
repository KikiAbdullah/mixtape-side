<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZineComment extends Model
{
    protected $fillable = [
        'zine_id', 'user_id', 'comment', 'parent_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(ZineComment::class, 'parent_id');
    }

    public function zine()
    {
        return $this->belongsTo(Zine::class);
    }
}
