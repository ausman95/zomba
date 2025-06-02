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
            'name' => ["nullable", "string", Rule::unique('members', 'name')->ignore($this->route('member'))],
            'position_id' => "required|numeric",
            'gender' => "required|string",
            'church_id' => "required|numeric",
        ];
    }
}
