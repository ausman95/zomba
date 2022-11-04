<?php

namespace App\Http\Requests\Services;

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
            'name' => ["nullable", "string", Rule::unique('services', 'name')->ignore($this->route('service'))],
            'email' => ["nullable", "string", Rule::unique('services', 'email')->ignore($this->route('service'))],
            'phone' => ["nullable", "string", Rule::unique('services', 'phone')->ignore($this->route('service'))],
            'service'=>"required|string",
            'address'=>"required|string",
        ];
    }
}
