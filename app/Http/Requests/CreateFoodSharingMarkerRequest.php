<?php

namespace App\Http\Requests;

use App\ConverterModels\FoodSharingMarkerPriority;
use App\ConverterModels\FoodSharingMarkerType;
use Illuminate\Validation\Rule;

class CreateFoodSharingMarkerRequest extends BaseRequest
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
            'latitude' => 'required|numeric|min:0',
            'longitude' => 'required|numeric|min:0',
            'type' => ['required', Rule::in(array_values(FoodSharingMarkerType::$text))],
            'description' => 'required|max:1024',
            'quantity' => 'required|integer|min:1|max:10',
            'priority' => ['required', Rule::in(array_values(FoodSharingMarkerPriority::$value))]
        ];
    }
}
