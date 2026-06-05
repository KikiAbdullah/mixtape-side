<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = [
        'release_id', 'track_number', 'title', 'duration', 'lyrics', 'lyrics_translation'
    ];

    public function release()
    {
        return $this->belongsTo(Release::class);
    }

    public function contributors()
    {
        return $this->hasMany(TrackContributor::class);
    }
}
