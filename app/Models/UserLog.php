<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $table 	= 'user_logs';
    protected $fillable = [
    	'actor_id',
        'action',
        'entity_type',
        'entity_id',
        'old_values',
        'new_values',
        'ip',
        'user_agent',
        'menu', // Kept for backward compatibility if needed, but not part of new spec
        'message', // Kept for backward compatibility if needed, but not part of new spec
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
