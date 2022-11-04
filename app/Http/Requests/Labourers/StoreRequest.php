<?php

namespace App\Http\Requests\Labourers;

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
            'name' => "required|string",
            'phone_number' => "required|string|unique:members,phone_number",
            'gender' => "required|string",
            'department_id' => "required|numeric",
            'labour_id' => "required|string",
            'type' => "required|string",
        ];
    }
}
