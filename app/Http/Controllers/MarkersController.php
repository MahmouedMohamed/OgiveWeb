<?php

namespace App\Http\Controllers;

use App\Marker;
use App\UserLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class MarkersController extends Controller
{
    public function __construct(){
        $this->content = array();
    }
    public function createMarker(Request $request){
        $marker = Marker::create($request->all());
        return response()->json($marker);
    }
    public function updateMarker(Request $request){
        $marker = Marker::updateOrCreate(['id' => request()->input('id')], [
            'Latitude' => request()->input('Latitude'),
            'Longitude' => request()->input('Longitude'),
            'status' => request()->input('status'),
        ]);
        $response["status"] = 200;
        $response["location"] = $marker;
        return response()->json($response);
    }
    public function deleteMarker(Request $request){
        $marker  = DB::table('markers')->where('id',$request->input('id'))->get();
        $marker->delete();
        return response()->json('Removed successfully.');
    }
    public function getAllMarkers()
    {
        $response["status"] = 200;
        $response["markers"] = Marker::all()->where('status',null,1);
        return response()->json($response);
    }
}
