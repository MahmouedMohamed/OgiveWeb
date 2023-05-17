<?php

namespace App\Http\Requests;

class UpdateMemoryRequest extends BaseRequest
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
            'personName' => 'string',
            'birthDate' => 'date|date_format:Y-m-d|before:deathDate',
            'deathDate' => 'date|date_format:Y-m-d|after:birthDate',
            'brief' => 'required|string|max:300',
            'lifeStory' => 'string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
