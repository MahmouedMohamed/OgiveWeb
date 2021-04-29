<?php

use App\Http\Controllers\api\AdoptionRequestController;
use App\Http\Controllers\api\ConsultationCommentController;
use App\Http\Controllers\api\ConsultationController;
use App\Http\Controllers\api\LikesController;
use App\Http\Controllers\api\MarkersController;
use App\Http\Controllers\api\MemoryController;
use App\Http\Controllers\api\PetController;
use App\Http\Controllers\api\PetsArticleController;
use App\Http\Controllers\api\PlaceController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\NeediesController;
use App\Http\Controllers\api\TransactionsController;
use App\Models\Pet;
use Illuminate\Http\Request;
// use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/marker', [MarkersController::class, 'getAll']);
    Route::post('/marker', [MarkersController::class, 'create']);
    Route::delete('/marker', [MarkersController::class, 'delete']);
    Route::get('/memory', [MemoryController::class, 'getAll']);
    Route::post('/memory', [MemoryController::class, 'create']);
    Route::delete('/memory', [MemoryController::class, 'delete']);
    Route::post('/like', [LikesController::class, 'likeUnlike']);
});

//ToDo: Add This APIs to be auth by 2oauth token
// Route::get('/pet',[PetController::class, 'index']);
// Route::get('/pet/{id}',[PetController::class, 'show']);
// Route::post('/pet',[PetController::class, 'store']);
// Route::patch('/pet/{id}',[PetController::class, 'update']);
// Route::delete('/pet/{id}',[PetController::class, 'destroy']);

Route::apiResource('pets', PetController::class);
Route::get('/filterByType', [PetController::class, 'filterByType']);

Route::put('/pet/{pet}', function (Pet $pet) {
    // The current user may update the post...
})->middleware('can:update,pet');

Route::apiResource('consultations', ConsultationController::class);
Route::apiResource('comments', ConsultationCommentController::class);
Route::apiResource('requests', AdoptionRequestController::class);

Route::post('myRequests', [AdoptionRequestController::class, 'getRequests']);
Route::post('sendRequest', [AdoptionRequestController::class, 'sendRequest']);

Route::apiResource('articles', PetsArticleController::class);

Route::apiResource('places', PlaceController::class);
Route::get('sales', [PlaceController::class, 'sales']);
Route::get('clinics', [PlaceController::class, 'clinics']);
Route::get('filterPlacesByType', [PlaceController::class, 'filterByType']);


// Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('/ahed/needies', NeediesController::class);
    Route::apiResource('/ahed/transactions', TransactionsController::class);
// });
