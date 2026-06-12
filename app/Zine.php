<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zine extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'status', 'published_at', 'author_id', 'thumbnail_url', 'banner_url'];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(ZineComment::class);
    }
    
    public function bands()
    {
        return $this->belongsToMany(Band::class, 'zine_band', 'zine_id', 'band_id');
    }
    
    public function releases()
    {
        return $this->belongsToMany(Release::class, 'zine_release', 'zine_id', 'release_id');
    }
    
    public function labels()
    {
        return $this->belongsToMany(Label::class, 'zine_label', 'zine_id', 'label_id');
    }
    
    public function organizers()
    {
        return $this->belongsToMany(Organizer::class, 'zine_organizer', 'zine_id', 'organizer_id');
    }}
