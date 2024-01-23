<?php

namespace App\Http\Requests\Banks;

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
            'account_name' => "required|string",
            'account_number' => "required|numeric|min:1|unique:banks,account_number",
            'service_centre' => "required|string",
            'account_type'=>"required|string",
            'bank_name'=>"required|string"
        ];
    }
}
