<?php

namespace App\Http\Requests\LabourerPayments;

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
            'amount' => "required|string",
            'labourer_id' => "required|string",
            'method' => "required|string",
            'expense_name' => "required|string",
        ];
    }
}
