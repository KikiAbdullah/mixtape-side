<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zine extends Model
{
    protected $fillable = [
        'title', 'slug', 'content', 'thumbnail_url', 'banner_url', 'author_id', 'status', 'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(ZineComment::class)->whereNull('parent_id');
    }

    public function allComments()
    {
        return $this->hasMany(ZineComment::class);
    }

    public function bands()
    {
        return $this->morphedByMany(Band::class, 'taggable', 'zine_taggables');
    }

    public function releases()
    {
        return $this->morphedByMany(Release::class, 'taggable', 'zine_taggables');
    }

    public function labels()
    {
        return $this->morphedByMany(Label::class, 'taggable', 'zine_taggables');
    }

    public function organizers()
    {
        return $this->morphedByMany(Organizer::class, 'taggable', 'zine_taggables');
    }
}
