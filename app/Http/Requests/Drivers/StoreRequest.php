<?php

namespace App\Http\Requests\Drivers;

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
            'account_id' => "required|numeric",
            'labourer_id' => "required|numeric",
            'amount' => "required|numeric",
            'start_date'=>"required|date",
            'end_date'=>"required|date"
        ];
    }
}
