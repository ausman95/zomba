<?php

namespace App\Http\Requests\Clients;

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
            'name' => "required|string|unique:clients,name",
            'address' => "required|string|unique:clients,address",
            'email' => "required|email|unique:clients,email",
            'phone_number' => "required|string|unique:clients,phone_number",
            'location' => "required|string|max:100",
        ];
    }
}
