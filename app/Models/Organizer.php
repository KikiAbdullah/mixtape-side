<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    protected $fillable = [
        'slug', 'name', 'logo_url', 'city', 'description', 'contact_info', 'owner_id'
    ];

    public function gigs()
    {
        return $this->hasMany(Gig::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
