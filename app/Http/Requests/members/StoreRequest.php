<?php

namespace App\Http\Requests\members;

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
//            'phone_number' => "required|string|unique:members,phone_number",
            'name' => "required|string|unique:members,name",
            'gender' => "required|string",
            'position_id' => "required|numeric",
            'church_id' => "required|numeric",
        ];
    }
}
