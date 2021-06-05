<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\Request;

class PetController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return Pet::all();
        // return PetResource::collection(Pet::all());
        $pets = Pet::with('user')->get();
        return $this->sendResponse($pets, 'Pets retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $this->validatePet($request);
        // $data=request()->all();
        $user = User::find(request()->input('user_id'));
        if (!$user) {
            return $this->sendError('User Not Found');
        }
        $imagePath = "/storage/" . $request['image']->store('uploads', 'public');
        $user->pets()->create([
            'name' => $request['name'],
            'age' => $request['age'],
            'sex' => $request['sex'],
            'type' => $request['type'],
            'notes' => $request['notes'],
            'image' => $imagePath,
            'status' => true,
        ]);
        return $this->sendResponse([], 'Pet is added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        //  return new PetResource(Pet::findOrFail($id));
        $pet = Pet::with('user')->find($id);
        if (is_null($pet)) {
            return $this->sendError('Pet not found.');
        }
        return $this->sendResponse($pet, 'Pet retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pet $pet)
    {
        // $data=$request->all();
        $this->authorize('update', $pet);
        // if (!empty($request['user_id'])) {
        //     $user = User::find(request()->input('user_id'));
        //     if (!$user) {
        //         return $this->sendError('User Not Found');
        //     }
        // }
        $pet = Pet::find($pet->id);
        if ($request->hasFile('image')) {
            $imagePath = $request['image']->store('uploads', 'public');
            $pet->image = "/storage/" . $imagePath;
        }
        // $pet->user_id = $request['user_id'];
        $pet->name = $request['name'];
        $pet->age = $request['age'];
        $pet->sex = $request['sex'];
        $pet->type = $request['type'];
        $pet->notes = $request['notes'];

        $pet->save();
        return response()->json([], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pet = Pet::find($id);
        if ($pet) {
            $pet->delete();
            Storage::delete('public/uploads'); // Change it to delete the image from public
            $pet->delete();
            return $this->sendResponse([], 'Pet deleted successfully.');
        } else {
            return $this->sendError('Pet not found.');
        }
    }
    public function validatePet(Request $request)
    {
        return $request->validate([
            'user_id' => 'required',
            'name' => 'required|max:255',
            'age' => 'required|integer|max:100',
            'sex' => 'required|in:male,female',
            'type' => 'required',
            'notes' => 'max:1024',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048e',

        ]);
    }
    // public function filterByType()
    // {
    //     $result = QueryBuilder::for(Pet::class) {
    //             ->allowedFilters('type')
    //             ->get();
    //     }

    //     if ($result->isEmpty()) {
    //         return $this->sendError('No Pets are found.');
    //     }
    //     return $this->sendResponse($result, 'Pets Retrieved successfully.');
    // }
}