<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'email' => ['required', 'string', Rule::unique('users', 'email')],
            'password' => ['required', 'string'],
            'phone_number' => ['required', 'string', Rule::unique('users', 'phone_number')],
            'first_name' => ['required', 'string'],
            'designation' => ['required', 'string'],
            'last_name' => ['required', 'string'],
        ];
    }
}
