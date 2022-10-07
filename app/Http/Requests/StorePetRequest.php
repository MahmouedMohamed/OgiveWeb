<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\ConverterModels\PetType;
use Illuminate\Validation\Rule;

class StorePetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; //temp
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'createdBy' => 'required',
            'name' => 'required|max:255',
            'age' => 'required|integer|max:100',
            'sex' => 'required|in:male,female',
            'type' => ['required', Rule::in(array_values(PetType::$text))],
            'notes' => 'max:1024',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048e'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'A Name is required',
            'age.required' => 'AN Age is required',
        ];
    }
}