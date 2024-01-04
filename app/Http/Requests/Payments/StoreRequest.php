<?php

namespace App\Http\Requests\Payments;

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
            'account_id'=>"required|numeric",
            'bank_id'=>"required|numeric",
            'name'=>"required|string",
            'amount'=>"required|numeric",
            'type'=>"required|numeric",
            't_date'=>"required|date",
            'payment_method'=>"required|numeric",
            'cheque_number'=>"required|string",
        ];
    }
}
