<?php

namespace App\Http\Requests\MaterialBudgets;

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
            'quantity' => "required|numeric",
            'department_id' => "required|numeric|exists:departments,id",
            'material_id' => "required|numeric|exists:materials,id",
        ];
    }
}
