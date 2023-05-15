<?php

namespace App\Http\Requests;

use App\ConverterModels\Gender;
use App\ConverterModels\Nationality;
use Illuminate\Validation\Rule;

class RegisterRequest extends BaseRequest
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
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'gender' => ['required', Rule::in(array_values(Gender::$text))],
            //|regex:^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$
            'phone_number' => 'required',
            'address' => 'string|max:1024',
            'image' => 'image',
            'nationality' => ['required', Rule::in(array_values(Nationality::$text))],
        ];
    }
}
