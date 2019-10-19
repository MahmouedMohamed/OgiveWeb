<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});





Route::get('hhh',function (){
   return response('hhhhh',200);
});


Route::group(['namespace' => 'api'], function () {
    Route::post('/login', 'UserController@login');
});

Route::group(['namespace' => 'api'], function () {
    Route::post('/register', 'UserController@register');
});

Route::post('/createMarker','MarkersController@createMarker');

Route::delete('/deleteMarker', 'MarkersController@deleteMarker');

Route::get('/getMarkers', 'MarkersController@getAllMarkers');

Route::put('/updateUserLocation','UserLocationController@updateUserLocation');
