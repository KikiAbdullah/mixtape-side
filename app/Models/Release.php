<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    protected $fillable = [
        'band_id', 'slug', 'title', 'release_type', 'cover_url', 'banner_url', 'original_release_year', 'description', 'track_count', 'created_by', 'is_verified', 'verified_by', 'verified_at'
    ];

    public function band()
    {
        return $this->belongsTo(Band::class);
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'release_labels')
                    ->using(ReleaseLabel::class)
                    ->withPivot(['id', 'catalog_number', 'press_year', 'format', 'press_type', 'notes'])
                    ->withTimestamps();
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }
}
