<?php

namespace App\Http\Requests\Projects;

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
            'name' => ["nullable", "string", Rule::unique('projects', 'name')->ignore($this->route('project'))],
            'location' => "nullable|string",
            'description' => "nullable|string",
            'start_date' => "nullable|date|before:end_date",
            'end_date' => "nullable|date|after:start_date",
            'status' => "nullable|numeric",
            'supervisor_id' => "nullable|numeric|exists:users,id",
            'client_id' => "nullable|numeric|exists:clients,id",
        ];
    }
}
