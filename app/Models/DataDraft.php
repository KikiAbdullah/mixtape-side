<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataDraft extends Model
{
    protected $table = 'data_drafts';

    protected $fillable = [
        'user_id', 
        'target_table', 
        'target_id', 
        'version',
        'original_snapshot',
        'proposed_data', 
        'change_summary',
        'status', 
        'reviewed_by',
        'admin_notes'
    ];

    protected $casts = [
        'original_snapshot' => 'array',
        'proposed_data' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
