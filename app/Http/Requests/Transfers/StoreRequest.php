<?php

namespace App\Http\Requests\Transfers;

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
            'to_account_id'=>"required|string",
            'from_account_id'=>"required|string",
            't_date'=>"required|date",
            'amount'=>"required|numeric|min:1",
        ];
    }
}
