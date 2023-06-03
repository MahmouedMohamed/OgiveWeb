<?php

namespace App\Http\Requests;

use App\ConverterModels\CaseType;
use Illuminate\Validation\Rule;

class StoreOfflineTransactionRequest extends BaseRequest
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
            'needy' => 'required|max:255|exists:needies,id',
            'giver' => 'nullable|max:255|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'preferredSection' => ['required', Rule::in(array_values(CaseType::$text_ar))],
            'address' => 'required',
            'phoneNumber' => 'required',
            'startCollectDate' => 'required|date|before:endCollectDate',
            'endCollectDate' => 'required|date|after:startCollectDate',
        ];
    }
}
