<?php

namespace App\Http\Requests;

use App\ConverterModels\CaseType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNeedyRequest extends FormRequest
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
            'name' => 'required|max:255',
            'age' => 'required|integer|max:100',
            'severity' => 'required|integer|min:1|max:10',
            'type' => ['required', Rule::in(array_values(CaseType::$text_ar))],
            'details' => 'required|max:1024',
            'need' => 'required|numeric|min:1',
            'address' => 'required',
        ];
    }
}
