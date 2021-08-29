<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\FoodSharingMarker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHandler;

class FoodSharingMarkersController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $responseHandler = new ResponseHandler($request['language']);
        //Validate Request
        $validated = $this->validateMarker($request);
        if ($validated->fails()) {
            return $this->sendError($responseHandler->words['WrongData'], $validated->messages(), 400);
        }

        $user = User::find(request()->input('createdBy'));
        if (!$user) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        }

        $user->foodSharingMarkers()->create([
            'latitude' => $request['latitude'],
            'longitude' => $request['longitude'],
            'type' => $request['type'],
            'description' => $request['description'],
            'quantity' => $request['quantity'],
            'priority' => $request['priority'],
            'collected' => 0,
        ]);
        return $this->sendResponse([], $responseHandler->words['MarkerCreationSuccessMessage']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return \Illuminate\Http\Response
     */
    public function show(FoodSharingMarker $foodSharingMarker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoodSharingMarker $foodSharingMarker)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoodSharingMarker $foodSharingMarker)
    {
        //
    }

    public function validateMarker(Request $request)
    {
        $rules = [
            'createdBy' => 'required',
            'latitude' => 'required|numeric|min:0',
            'longitude' => 'required|numeric|min:0',
            'type' => 'required|in:Food,Drink,Both of them',
            'description' => 'required|max:1024',
            'quantity' => 'required|integer|min:1|max:10',
            'priority' => 'required|integer|min:1|max:10'
        ];
        $messages = [];
        if ($request['language'] != null)
            $messages = $this->getValidatorMessagesBasedOnLanguage($request['language']);
        return Validator::make($request->all(), $rules, $messages);
    }

    public function getValidatorMessagesBasedOnLanguage(string $language)
    {
        if ($language == 'En')
            return [
                'required' => 'This field is required',
                'min' => 'Wrong value, minimum value is :min',
                'max' => 'Wrong value, maximum value is :max',
                'integer' => 'Wrong value, supports only real numbers',
                'in' => 'Wrong value, supported values are :values',
                'numeric' => 'Wrong value, supports only numeric numbers',
            ];
        else if ($language == 'Ar')
            return [
                'required' => 'هذا الحقل مطلوب',
                'min' => 'قيمة خاطئة، أقل قيمة هي :min',
                'max' => 'قيمة خاطئة أعلي قيمة هي :max',
                'integer' => 'قيمة خاطئة، فقط يمكن قبول الأرقام فقط',
                'in' => 'قيمة خاطئة، القيم المتاحة هي :values',
                'image' => 'قيمة خاطئة، يمكن قبول الصور فقط',
                'mimes' => 'يوجد خطأ في النوع، الأنواع المتاحة هي :values',
                'numeric' => 'قيمة خاطئة، يمكن قبول الأرقام فقط',
            ];
    }
}
