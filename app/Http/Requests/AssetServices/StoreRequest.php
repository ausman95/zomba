<?php

namespace App\Http\Requests\AssetServices;

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
            'provider_id' => "required|numeric",
            'asset_id' => "required|numeric",
            'service_due' => "required|date",
        ];
    }
}
