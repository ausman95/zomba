<?php

namespace App\Http\Requests\Assets;

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
            'name'=>"required|string",
            'cost'=>"required|numeric|min:1",
            'condition'=>"required|string",
            'life'=>"required|numeric|min:1",
            't_date'=>"required|string",
            'location'=>"required|string",
            'quantity'=>"required|numeric|min:1",
            'depreciation'=>"required|numeric|min:1",
        ];
    }
}
