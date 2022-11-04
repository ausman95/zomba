<?php

namespace App\Http\Requests\FinancialYears;

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
            'name' => "required|string|unique:financial_years,name",
            'start_date' => "required|date|unique:financial_years,start_date",
            'end_date' => "required|date|unique:financial_years,end_date",
        ];
    }
}
