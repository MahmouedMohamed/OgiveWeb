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
Route::group(['namespace' => 'api'], function () {
    Route::post('/login', 'UserController@login');
});

Route::group(['namespace' => 'api'], function () {
    Route::post('/register', 'UserController@register');
});
Route::group(['namespace' => 'api','middleware' => 'auth:api'], function(){
    Route::get('/markers', 'MarkersController@getAll');
    Route::post('/marker','MarkersController@create');
    Route::delete('/marker', 'MarkersController@delete');

    Route::get('/memories', 'MemoryController@getAllMemories');
    Route::post('/memory','MemoryController@createMemory');
    Route::delete('/memory', 'MemoryController@deleteMemory');

    Route::post('/like','LikesController@likeUnlike');
});




