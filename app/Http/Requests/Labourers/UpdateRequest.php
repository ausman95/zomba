<?php

namespace App\Http\Requests\Labourers;

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
            'name' => "required|string",
            'phone_number' => ["nullable", "string", Rule::unique('members', 'phone_number')->ignore($this->route('labourer'))],
            'gender' => "required|string",
            'department_id' => "required|numeric",
            'labour_id' => "required|string",
            'type' => "required|string",
//            'phone_number' => ["nullable", "string", Rule::unique('members', 'phone_number')->ignore($this->route('members'))],
        ];
    }
}
