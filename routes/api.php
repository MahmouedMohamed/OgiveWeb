<?php

use Illuminate\Http\Request;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\MarkersController;
use App\Http\Controllers\api\MemoryController;
use App\Http\Controllers\api\LikesController;
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

Route::post('/login',[UserController::class, 'login']);
Route::post('/register',[UserController::class, 'register']);
Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/marker',[MarkersController::class, 'getAll']);
    Route::post('/marker',[MarkersController::class, 'create']);
    Route::delete('/marker',[MarkersController::class, 'delete']);

    Route::get('/memory',[MemoryController::class, 'getAll']);
    Route::post('/memory',[MemoryController::class, 'create']);
    Route::delete('/memory',[MemoryController::class, 'delete']);

    Route::post('/like',[LikesController::class, 'likeUnlike']);
});




