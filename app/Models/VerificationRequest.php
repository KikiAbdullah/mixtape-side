<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationRequest extends Model
{
    protected $table = 'verification_requests';

    protected $fillable = [
        'user_id', 'target_type', 'target_id', 'relationship_desc', 'verification_documents', 'status', 'admin_notes'
    ];

    protected $casts = [
        'verification_documents' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
