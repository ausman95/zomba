<?php

namespace App\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'address' => "required|string",
            'location' => "required|string|max:100",
            'name' => ["nullable", "string", Rule::unique('clients', 'name')->ignore($this->route('client'))],
            'email' => ["nullable", "string", Rule::unique('clients', 'email')->ignore($this->route('client'))],
            'phone_number' => ["nullable", "string", Rule::unique('clients', 'phone_number')->ignore($this->route('client'))],

        ];
    }
}
