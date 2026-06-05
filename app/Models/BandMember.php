<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BandMember extends Model
{
    protected $fillable = [
        'band_id', 'name', 'linked_user_id', 'role_instrument', 'join_year', 'leave_year', 'is_current'
    ];

    protected $casts = [
        'is_current' => 'boolean'
    ];

    public function band()
    {
        return $this->belongsTo(Band::class);
    }

    public function linkedUser()
    {
        return $this->belongsTo(User::class, 'linked_user_id');
    }
}
