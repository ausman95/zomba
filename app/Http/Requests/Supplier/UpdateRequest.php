<?php

namespace App\Http\Requests\Supplier;

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
            'name' => ["nullable", "string", Rule::unique('suppliers', 'name')->ignore($this->route('supplier'))],
            'phone_number' => ["nullable", "string", Rule::unique('suppliers', 'phone_number')->ignore($this->route('supplier'))],
            'location'=>"nullable|string",
            'address'=>"nullable|string",
        ];
    }
}
