<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organizer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug', 'name', 'logo_url', 'city', 'description', 'contact_info', 'owner_id', 'created_by', 'is_verified', 'verified_by', 'verified_at'
    ];

    public function gigs()
    {
        return $this->hasMany(Gig::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function zines()
    {
        return $this->morphToMany(Zine::class, 'taggable', 'zine_taggables');
    }
}
