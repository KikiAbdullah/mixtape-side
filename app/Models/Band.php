<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Band extends Model
{
    protected $fillable = [
        'slug', 'name', 'alternative_names', 'logo_url', 'banner_url', 'photo_url', 'city', 
        'country', 'formed_year', 'disbanded_year', 'status', 'genre', 
        'biography', 'social_links', 'owner_id', 'created_by', 'is_verified', 'verified_by', 'verified_at'
    ];

    protected $casts = [
        'alternative_names' => 'array',
        'genre' => 'array',
        'social_links' => 'array'
    ];

    public function members()
    {
        return $this->hasMany(BandMember::class);
    }

    public function releases()
    {
        return $this->hasMany(Release::class);
    }

    public function gigs()
    {
        return $this->belongsToMany(Gig::class, 'gig_bands')->withPivot('performance_order');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function zines()
    {
        return $this->morphToMany(Zine::class, 'taggable', 'zine_taggables');
    }
}
