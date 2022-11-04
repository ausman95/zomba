<?php

namespace App\Http\Requests\members;

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
            'phone_number' => ["nullable", "string", Rule::unique('members', 'phone_number')->ignore($this->route('member'))],
            'name' => "required|string",
            'gender' => "required|string",
            'church_id' => "required|numeric",
            'ministry_id' => "required|numeric"
        ];
    }
}
