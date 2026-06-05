<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ReleaseLabel extends Pivot
{
    protected $table = 'release_labels';

    protected $fillable = [
        'release_id', 'label_id', 'catalog_number', 'press_year', 'format', 'press_type', 'notes'
    ];

    public function release()
    {
        return $this->belongsTo(Release::class);
    }

    public function label()
    {
        return $this->belongsTo(Label::class);
    }
}
