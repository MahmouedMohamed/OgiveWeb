<?php

namespace App\Http\Requests;

class CreateUserBanRequest extends BaseRequest
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
            'tag' => 'required',
            'start_at' => 'date',
            'end_at' => 'date|after:from',
        ];
    }
}