<?php

namespace App\Http\Requests;

class CreateMemoryRequest extends BaseRequest
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
            'personName' => 'required|string',
            'birthDate' => 'required|date|date_format:Y-m-d|before:deathDate',
            'deathDate' => 'required|date|date_format:Y-m-d|after:birthDate',
            'brief' => 'required|string|max:300',
            'lifeStory' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
