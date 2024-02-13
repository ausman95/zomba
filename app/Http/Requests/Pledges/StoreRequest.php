<?php

namespace App\Http\Requests\Pledges;

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
            'account_id' =>"required|numeric",
            'member_id'=>"required|numeric",
            'date'=>"required|date",
            'amount'=>"required|numeric|min:1"
        ];
    }
}
