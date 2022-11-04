<?php

namespace App\Http\Requests\Projects;

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
            'name' => "required|string|unique:projects,name",
            'location' => "required|string",
            'amount' => "required|string",
            'description' => "required|string",
            'start_date' => "required|date|before:end_date",
            'end_date' => "required|date|after:start_date",
            'status' => "nullable|numeric",
            'supervisor_id' => "nullable|numeric|exists:users,id",
            'client_id' => "required|numeric|exists:clients,id",
        ];
    }
}
