<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
            {
                return [
                    'username'              => 'required|unique:users',
                    'email'                 => 'nullable|email|unique:users,email',
                    'name'                  => 'required',
                    'password'              => 'required',
                ];
            }
            case 'PATCH':
            case 'PUT':
            {
                return [
                    // 'email' => "nullable|email|unique:users,email,'".$this->user,

                    //below way will only work in Laravel ^5.5 
                    'name'                  => 'required',
                    'email' => [Rule::unique('users')->ignore($this->user),'nullable','email'],
    
                   //Sometimes you dont have id in $this object
                   //then you can use route method to get object of model 
                   //and then get the id or slug whatever you want like below:
    
                //   'email' => Rule::unique('users')->ignore($this->route()->user->id),
                ];
            }
            default: break;
        }
    }

}
