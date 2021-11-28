<?php

namespace App\Http\Controllers\api\BreedMe;

use App\Http\Controllers\api\BaseController;

use App\Models\AdoptionRequest;
use Illuminate\Http\Request;

class AdoptionRequestController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRequests(Request $request)
    {
        $pet_id = $request['pet_id'];
        $user_id = $request['user_id'];
        $requests = AdoptionRequest::where('pet_id', $pet_id)->where('user_id', $user_id)->get();
        if ($requests->isEmpty()) {
            return $this->sendError("No Requests found");
        } else {
            return $this->sendResponse($requests, "Requests are Retrieved Successfully");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendRequest(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required',
            'pet_id' => 'required',
            'phone_number' => 'required',
            'adoption_place' => 'required',
            'experience' => 'required',
            'accepted_terms' => 'required',
            'address' => 'required',
        ]);

        $adoptionRequest = new AdoptionRequest();
        $adoptionRequest->user_id = $request['user_id'];
        $adoptionRequest->phone_number = $request['phone_number'];
        $adoptionRequest->pet_id = $request['pet_id'];
        $adoptionRequest->address = $request['address'];
        $adoptionRequest->adoption_place = $request['adoption_place'];
        $adoptionRequest->experience = $request['experience'];
        $adoptionRequest->accepted_terms = $request['accepted_terms'];
        $adoptionRequest->save();
        return $this->sendResponse([], 'Request is sent successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdoptionRequest  $adoptionRequest
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $request = AdoptionRequest::find($id);
        if ($request) {
            return $this->sendResponse($request, "Request is retrieved Successfully");
        } else {
            return $this->sendError("Request not found");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdoptionRequest  $adoptionRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(AdoptionRequest $adoptionRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdoptionRequest  $adoptionRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $adoptionRequest = AdoptionRequest::find($id);
        $adoptionRequest->phone_number = $request['phone_number'];
        $adoptionRequest->user_id = $request['user_id'];
        $adoptionRequest->pet_id = $request['pet_id'];
        $adoptionRequest->address = $request['address'];
        $adoptionRequest->adoption_place = $request['adoption_place'];
        $adoptionRequest->experience = $request['experience'];
        $adoptionRequest->accepted_terms = $request['accepted_terms'];
        $adoptionRequest->save();
        return $this->sendResponse([], 'Request is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdoptionRequest  $adoptionRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $request = AdoptionRequest::find($id);
        if ($request) {
            $request->delete();
            return $this->sendResponse([], "Request is deleted Successfully");
        } else {
            return $this->sendError("Request not found");
        }
    }
}
