<?php 

namespace App\Http\Controllers\Traits;

use DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

trait ListTrait {

    public function list_role()
	{
        return Role::pluck('name','id');
    }

}