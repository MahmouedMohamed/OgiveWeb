<?php

namespace App\Http\Requests;

use App\ConverterModels\Nationality;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
            'bio' => 'nullable|string|max:255',
            //|regex:^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$
            'phone_number' => 'nullable',
            'address' => 'string|max:1024',
            'nationality' => ['nullable', Rule::in(array_values(Nationality::$text))],
        ];
    }
}
