<?php

namespace App\Http\Requests\Programs;

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
            'venue'=>'required|string',
            't_date'=>'required|date',
            'preacher_id'=>'required|numeric',
            'facilitator_id'=>'required|numeric'
        ];
    }
}
