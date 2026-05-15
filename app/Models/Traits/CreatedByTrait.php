<?php 

namespace App\Models\Traits;

use App\Observers\CreatedByObserver;

trait CreatedByTrait {

	public static function bootCreatedByTrait()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });
    }

}