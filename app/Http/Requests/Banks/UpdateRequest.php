<?php

namespace App\Http\Requests\Banks;

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
            'bank_name' => "required|string",
            'account_name' => "required|string",
            'account_number' => ["nullable", "string", Rule::unique('banks', 'account_name')->ignore($this->route('bank'))],
            'service_centre' => "required|string",
            'account_type'=>"required|string"
        ];

    }
}
