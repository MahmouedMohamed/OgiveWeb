<?php

namespace App\Http\Controllers;

use App\Food;
use App\Marker;
use App\User;
use App\UserLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use phpDocumentor\Reflection\Types\Collection;

class MarkersController extends Controller
{
    public function __construct(){
        $this->content = array();
    }
    public function createMarker(Request $request){
        $data=request()->all();
        $rules= [
            'Latitude' => ['required'],
            'Longitude' => ['required'],
            'user_id' => ['required'],
            'status' => ['required'],
            'name' => ['required'],
            'description' => ['required'],
            'quantity' => ['required'],
            'priority' => ['required'],
        ];
        $validator = Validator::make($data,$rules);
        if($validator->passes()){
            $marker = Marker::create([
            'Latitude' => request('Latitude'),
            'Longitude' => request('Longitude'),
            'user_id' => request('user_id'),
            'status' => request('status'),
        ]);
            $marker->food()->create([
                'name' => request('name'),
                'description' => request('description'),
                'quantity' => request('quantity'),
                'priority' => request('quantity'),
            ]);
            $this->content['status'] = 200;
        }
        else{
            $this->content['status'] = 405;
            $this->content['details']=$validator->errors()->all();
        }
        return response()->json($this->content);
    }
    public function updateMarker(Request $request){
        $marker = Marker::updateOrCreate(['id' => request()->input('id')], [
            'Latitude' => request()->input('Latitude'),
            'Longitude' => request()->input('Longitude'),
            'status' => request()->input('status'),
        ]);
        $response["status"] = 200;
        $response["marker"] = $marker;
        return response()->json($response);
    }
    public function deleteMarker(Request $request){
        $marker = Marker::updateOrCreate(['id' => request()->input('id')], [
            'status' => request()->input('status'),
        ]);
        $response["status"] = 200;
        $response["marker"] = $marker;
        return response()->json($response);
    }
    public function getAllMarkers()
    {
        $result = Marker::all()->values()->sortBy('id');
        $food = Food::all()->values()->sortBy('marker_id');
        $response["markers"] = $result;
        $response["properties"] = $food;
        return response()->json($response);
    }
}
