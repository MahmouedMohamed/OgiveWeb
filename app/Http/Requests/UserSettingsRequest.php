<?php

namespace App\Http\Requests;

class UserSettingsRequest extends BaseRequest
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
            'auto_donate' => 'nullable|boolean',
            'auto_donate_on_severity' => 'nullable|integer|min:1|max:10',
        ];
    }
}
