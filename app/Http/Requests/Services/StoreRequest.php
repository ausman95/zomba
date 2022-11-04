<?php

namespace App\Http\Requests\Services;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        return [
            'name' => "required|string|unique:services,name",
            'email' => "required|email|unique:services,email",
            'service'=>"required|string",
            'address'=>"required|string",
            'phone'=>"required|string|unique:services,phone"
        ];
    }
}
