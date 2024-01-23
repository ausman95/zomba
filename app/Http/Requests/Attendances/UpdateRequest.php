<?php

namespace App\Http\Requests\Attendances;

use Illuminate\Foundation\Http\FormRequest;

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
            'service_id'=>'required|numeric',
            'male'=>'required|numeric|min:0',
            'female'=>'required|numeric|min:0',
            'visitors'=>'required|numeric|min:0',
            'date'=>'required|date',
        ];
    }
}
