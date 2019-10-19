<?php

namespace App\Http\Controllers;

use App\Marker;
use App\UserLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class UserLocationController extends Controller
{
    public function createUserLocation(Request $request)
    {
        $location = UserLocation::create($request->all());
        return response()->json($location);
    }

    public function updateUserLocation(Request $request)
    {
        $location = UserLocation::updateOrCreate(['user_id' => request()->input('user_id')], [
            'Latitude' => request()->input('Latitude'),
            'Longitude' => request()->input('Longitude'),
        ]);
        $response["status"] = 200;
        $response["location"] = $location;
        return response()->json($response);
    }
}
