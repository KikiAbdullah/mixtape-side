<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gig extends Model
{
    protected $fillable = [
        'slug', 'title', 'poster_url', 'banner_url', 'date', 'start_time', 'venue_name', 
        'venue_address', 'city', 'ticket_price', 'ticket_info', 'organizer_id', 'created_by', 'is_verified', 'verified_by', 'verified_at'
    ];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function bands()
    {
        return $this->belongsToMany(Band::class, 'gig_bands')->withPivot('performance_order');
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'gig_labels')->withPivot('partnership_role');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
