<?php

namespace App\Http\Requests\Purchases;

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
            'material_id' => "required|numeric",
            'supplier_id' => "required|numeric",
            'account_id' => "required|numeric",
            'date' => "required|date",
            'payment_type' => "required|numeric",
            'project_id'=>"required|numeric",
            'quantity' => "required|numeric|min:1",
            'amount' => "required|numeric|min:1",
        ];
    }
}
