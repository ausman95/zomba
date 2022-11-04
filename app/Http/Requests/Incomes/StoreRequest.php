<?php

namespace App\Http\Requests\Incomes;

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
            'method'=>"required|string",
            'account_id'=>"required|string",
            'amount'=>"required|string",
            'description'=>"required|string",
            'cheque_number' => "required|string|unique:incomes,cheque_number",
            'transaction_type'=>"required|string",
            'project_id'=>"required|string"
        ];
    }
}
