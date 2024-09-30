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
            'name.required' => 'The asset name is required.',
            'cost.required' => 'The cost is required and must be a number greater than zero.',
            'cost.numeric' => 'The cost must be a valid number.',
            'cost.min' => 'The cost must be at least 1.',
            'condition.required' => 'The condition of the asset is required.',
            'life.required' => 'The asset life is required and must be a number.',
            'life.numeric' => 'The asset life must be a valid number.',
            'life.min' => 'The asset life must be at least 1.',
            't_date.required' => 'The transaction date is required.',
            'location.required' => 'The location of the asset is required.',
            'quantity.required' => 'The quantity is required and must be a number greater than zero.',
            'quantity.numeric' => 'The quantity must be a valid number.',
            'quantity.min' => 'The quantity must be at least 1.',
            'depreciation.required' => 'The depreciation is required and must be a number greater than zero.',
            'depreciation.numeric' => 'The depreciation must be a valid number.',
            'depreciation.min' => 'The depreciation must be at least 1.',
        ];
    }
}
