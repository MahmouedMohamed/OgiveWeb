<?php

namespace App\Http\Controllers\api\BreedMe;

use App\Http\Controllers\api\BaseController;
use App\Models\BreedMe\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $consultations = Consultation::all();
        return $this->sendResponse($consultations, 'Consultations are retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->sendError("Not Implemented");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required',
            'description' => 'required',
        ]);
        $consultation = new Consultation();
        if ($request->hasFile('image')) {
            $imagePath = $request['image']->store('uploads', 'public');
            $consultation->image = $imagePath;
        }
        $consultation->user_id = $request['user_id'];
        $consultation->description =$request['description'];
        $consultation->save();
        return $this->sendResponse([], 'The Consultation is  added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $consultation = Consultation::find($id);
        if($consultation){
            return $this->sendResponse($consultation,'The Consultation is retrieved successfully');
        }else{
            return $this->sendError('The Consultation not found.');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\Http\Response
     */
    public function edit(Consultation $consultation)
    {
        return $this->sendError("Not Implemented");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Consultation $consultation)
    {
        $consultation = Consultation::find($consultation->id);
        $consultation->description = $request['description'];
        if ($request->hasFile('image')) {
            $imagePath = $request['image']->store('uploads','public');
            $consultation->image = $imagePath;
        }
        $consultation->save();
        return $this->sendResponse([], 'The Consultation is updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $consultation = Consultation::find($id);
        if ($consultation) {
            $consultation->delete();
            // Storage::delete('public/uploads'); // Change it to delete the image from public
            $consultation->delete();
            return $this->sendResponse([], 'The Consultation is deleted successfully.');
        } else {
            return $this->sendError('The Consultation not found.');
        }
    }
}
