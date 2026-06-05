<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataDraft extends Model
{
    protected $table = 'data_drafts';

    protected $fillable = [
        'user_id', 'target_table', 'target_id', 'proposed_data', 'status', 'admin_notes'
    ];

    protected $casts = [
        'proposed_data' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
