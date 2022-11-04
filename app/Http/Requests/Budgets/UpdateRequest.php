<?php

namespace App\Http\Requests\Budgets;

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
            'amount' => "required|string",
            'account_id' => "required|numeric|exists:Ministries,id",
            'financial_year_id' => "required|numeric|exists:financial_years,id",
        ];
    }
}
