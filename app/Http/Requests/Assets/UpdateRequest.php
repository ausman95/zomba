<?php

namespace App\Http\Requests\Assets;

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
            'name'=>"required|string",
            'cost'=>"required|numeric|min:1",
            'category_id'=>"required|numeric|min:1",
            'life'=>"required|numeric|min:1",
            't_date'=>"required|string",
            'serial_number'=>"required|string",
            'quantity'=>"required|numeric|min:1",
            'depreciation'=>"required|numeric|min:1",
        ];
    }
}
