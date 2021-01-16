<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
<<<<<<< Updated upstream

use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\Request;
=======
use App\Http\Resources\PetResource;
use Illuminate\Http\Request;
use App\Models\Pet;
use Illuminate\Support\Facades\Validator;
>>>>>>> Stashed changes

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
<<<<<<< Updated upstream
        //
=======
        return new PetResource(Pet::all());

>>>>>>> Stashed changes
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
<<<<<<< Updated upstream
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
=======
        
        $imageName = time().'.'.$request->image->extension();  
        $path = $request->image->move(public_path('images'), $imageName);
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'type' => 'required',
            'sex' => 'required',
            'age' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $pet = Pet::create([
            "name" => $request['name'],
            "age" => $request['age'],
            "type" => $request['type'],
            "sex" => $request['sex'],
            "notes" => $request['notes'],
            "user_id" => $request['user_id'],
            "image" => $path
         ] );
        return response()->json(['Err_Flag'=> false ,"message" => "Pet is added successfully"], 200);
        
>>>>>>> Stashed changes
    }

    /**
     * Display the specified resource.
     *
<<<<<<< Updated upstream
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function show(Pet $pet)
    {
        //
=======
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new PetResource($id);

        // $pet = Pet::find($id);
        // if($pet){
        //     return response()->json($pet, 200);
        // }else{
        //     return response()->json(['Err_Flag'=> true,'Err_Desc' => "Pet is not found"] , 404);
        // }
>>>>>>> Stashed changes
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
<<<<<<< Updated upstream
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pet $pet)
    {
        //
=======
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //
        
        $pet = Pet::find($id);
        $name = $request->input('name');
        var_dump($name);
        return $name;
        if($request->has('image')) {
            $image = $request->file('image');
            $filename = $image->getClientOriginalName();
            $image->move(public_path('images/'), $filename);
            $pet->image = $request->file('image')->getClientOriginalName();
        }
        $request->validate([
            'name' => 'required',
            'age' => 'required',
            'type' => 'required',
            'user_id' => 'required'
        ]);
        $pet->name = $request['name'];
        $pet->age = $request['age'];
        $pet->type = $request['type'];
        $pet->sex = $request['sex'];
        $pet->notes = $request['notes'];
        $pet->status = $request['status'];
        $pet->user_id = $request['user_id'];
        $pet->save();
        return response()->json(["Err_Flag => false","message => Updated Successfully"],200);
>>>>>>> Stashed changes
    }

    /**
     * Remove the specified resource from storage.
     *
<<<<<<< Updated upstream
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
=======
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pet = Pet::Find($id);
        if($pet){
            $pet->delete();
            return response()->json(['Err_Flag'=> false,'message' => "Pet is successfully delete"] , 200);
    
        }else{
            return response()->json(['Err_Flag'=> true,'Err_Desc' => "Pet Couldnt delete"] , 404);
        }
    }
}
>>>>>>> Stashed changes
