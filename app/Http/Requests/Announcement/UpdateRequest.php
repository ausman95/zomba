<?php

namespace App\Http\Requests\Announcement;

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
            'ministry_id'=>'required|numeric',
            'start_date'=>'required|date',
            'end_date'=>'required|date',
            'title'=>'required|string',
            'body'=>'required|string',
        ];
    }
}
