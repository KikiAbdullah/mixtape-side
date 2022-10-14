<?php 

namespace App\Models\Traits;

use App\Observers\CreatedByObserver;

trait CreatedByTrait {

	public static function bootCreatedByTrait()
    {
        static::observe(CreatedByObserver::class);
    }

}