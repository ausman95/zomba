<?php

namespace App\Http\Requests\FinancialYears;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' => ["nullable", "string", Rule::unique('financial_years', 'name')->ignore($this->route('financial_year'))],
            'start_date' => ["nullable", "date", Rule::unique('financial_years', 'start_date')->ignore($this->route('financial_year'))],
            'end_date' => ["nullable", "date", Rule::unique('financial_years', 'end_date')->ignore($this->route('financial_year'))],
        ];
    }
}
