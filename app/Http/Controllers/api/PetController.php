<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\Request;

class PetController extends Controller
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
        $validated = $this->validatePet($request);
        // $data=request()->all();
        $user = User::find(request()->input('user_id'));
        if (!$user) {
            return response()->json(['Err_Flag' => true, 'Err_Desc' => "User Not Found"], 404);
        }
        $imagePath = $request['image']->store('uploads', 'public');
        $user->pets()->create([
            'name' => $request['name'],
            'age' => $request['age'],
            'sex' => $request['sex'],
            'type' => $request['type'],
            'notes' => $request['notes'],
            'image' => $imagePath,
            'status' => true
        ]);
        return response()->json([], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function show(Pet $pet)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pet $pet)
    {
        //
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
            'image' => 'required|image',
        ]);
    }
}
