<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Marker;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class MarkersController extends Controller
{
    public function __construct(){
        $this->content = array();
    }
    public function getAll()
    {
        $markers = Marker::all()->sortBy('id');
        foreach ($markers as $marker)
            $marker["food"] = $marker->food;
        $response["markers"] = $markers;
        return response()->json($response);
    }
    public function create(){
        $data=request()->all();
        $rules= [
            'Latitude' => ['required'],
            'Longitude' => ['required'],
            'user_id' => ['required'],
            'name' => ['required'],
            'description' => ['required'],
            'quantity' => ['required'],
            'priority' => ['required'],
        ];
        $validator = Validator::make($data,$rules);
        if($validator->passes()) {
            $user = User::findOrFail(request('user_id'));
            $user->markers()->create([
                'Latitude' => request('Latitude'),
                'Longitude' => request('Longitude')])
            ->food()->create([
                    'name' => request('name'),
                    'description' => request('description'),
                    'quantity' => request('quantity'),
                    'priority' => request('priority'),
            ]);
            $this->content['status'] = 'done';
        }
        else{
            $this->content['status'] = 'undone';
            $this->content['details']=$validator->errors()->all();
        }
        return response()->json($this->content);
    }
    public function delete(){
        $marker = Marker::findOrFail(request()->input('id'));
        $marker->delete();
        $response["status"] = 'done';
        return response()->json($response);
    }
}
