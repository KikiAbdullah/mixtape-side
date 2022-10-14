<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class CreatedByObserver
{
	public function creating(Model $model)
    {
    	if (auth()->check()) {
        	$model->created_by = auth()->user()->id;
    	}
    }

}
