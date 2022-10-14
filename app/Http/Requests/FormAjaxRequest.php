<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class FormRequest extends LaravelFormRequest
{
    /**
     * 
     * Eextends class ini untuk mendapatkan return json
     *
     */

    abstract public function rules();

    abstract public function authorize();

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        $transformed = [];

        foreach ($errors as $field => $message) {
            $transformed = [
                'field' => $field,
                'message' => $message[0],
            ];
        }

        $response = [
            'code'              => 422,
            'status'            => false,
            'data'              => null,
            'message'           => $transformed,
        ];

        throw new HttpResponseException(
            response()->json($response, 422)
        );
    }
}