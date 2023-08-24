<?php

namespace App\Http\Requests;

class StoreAtaaPrizeRequest extends BaseRequest
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
            'name' => 'required',
            'arabic_name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'required_markers_collected' => 'required|integer|min:0',
            'required_markers_posted' => 'required|integer|min:0',
            'from' => 'date',
            'to' => 'date|after:from',
            'level' => 'required|integer|min:1',
            'action' => 'required|in:shift,replace',
        ];
    }
}
