<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class LoginRequest extends BaseRequest
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
            'accessType' => ['required', Rule::in(['API'])],
            'appType' => ['required', Rule::in(['Ahed', 'Ataa', 'TimeCatcher','BreedMe'])],
            'email' => ['required', 'email'],
            'password' => ['required'],
            'fcmToken' => ['required_if:appType,TimeCatcher'],
        ];
    }
}