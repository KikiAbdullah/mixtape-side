<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $fillable = [
        'slug', 'name', 'logo_url', 'city', 'formed_year', 'defunct_year', 
        'status', 'contact_email', 'website_url', 'description', 'owner_id'
    ];

    public function releases()
    {
        return $this->belongsToMany(Release::class, 'release_labels')
                    ->using(ReleaseLabel::class)
                    ->withPivot(['id', 'catalog_number', 'press_year', 'format', 'press_type', 'notes'])
                    ->withTimestamps();
    }

    public function gigs()
    {
        return $this->belongsToMany(Gig::class, 'gig_labels')->withPivot('partnership_role');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
