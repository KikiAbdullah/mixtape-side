<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackContributor extends Model
{
    protected $table = 'track_contributors';

    protected $fillable = [
        'track_id', 'name', 'role', 'notes'
    ];

    public function track()
    {
        return $this->belongsTo(Track::class);
    }
}
