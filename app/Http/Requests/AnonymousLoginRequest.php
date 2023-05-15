<?php

namespace App\Http\Requests;

class AnonymousLoginRequest extends BaseRequest
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
            'deviceId' => ['required'],
            'nationality' => ['required'],
            'appType' => ['required'],
            'accessType' => ['required'],
        ];
    }
}
