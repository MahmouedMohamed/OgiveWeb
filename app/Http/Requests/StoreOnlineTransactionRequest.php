<?php

namespace App\Http\Requests;

class StoreOnlineTransactionRequest extends BaseRequest
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
            'giver' => 'required|exists:users,id',
            'needy' => 'required|exists:needies,id',
            'amount' => 'required|numeric|min:1',
        ];
    }
}
