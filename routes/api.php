<?php

use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\api\AdoptionRequestController;
use App\Http\Controllers\api\ConsultationCommentController;
use App\Http\Controllers\api\ConsultationController;
use App\Http\Controllers\api\LikesController;
use App\Http\Controllers\api\FoodSharingMarkersController;
use App\Http\Controllers\api\MemoryController;
use App\Http\Controllers\api\NeediesController;
use App\Http\Controllers\api\OfflineTransactionsController;
use App\Http\Controllers\api\OnlineTransactionsController;
use App\Http\Controllers\api\PetController;
use App\Http\Controllers\api\PetsArticleController;
use App\Http\Controllers\api\PlaceController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\AtaaAchievementController;
use App\Http\Controllers\api\TokensController;
use App\Http\Controllers\api\AtaaPrizeController;
use App\Models\Pet;
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

//**      User Controllers      **//
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::patch('/profile/{id}/picture', [UserController::class, 'updateProfilePicture']);
Route::patch('/profile/{id}/cover', [UserController::class, 'updateCoverPicture']);
Route::patch('/profile/{id}/information', [UserController::class, 'updateinformation']);


//**      Ataa Controllers      **//
Route::apiResource('/ataa/markers', FoodSharingMarkersController::class);
Route::patch('/ataa/collect/{id}', [FoodSharingMarkersController::class, 'collect']);
Route::get('/ataa/achivement/{id}', [AtaaAchievementController::class, 'show']);


// Route::group(['middleware' => 'auth:api'], function () {
Route::get('/memory', [MemoryController::class, 'getAll']);
Route::post('/memory', [MemoryController::class, 'create']);
Route::delete('/memory', [MemoryController::class, 'delete']);
Route::post('/like', [LikesController::class, 'likeUnlike']);
// });

//TODO: Add This APIs to be auth by 2oauth token
// Route::get('/pet',[PetController::class, 'index']);
// Route::get('/pet/{id}',[PetController::class, 'show']);
// Route::post('/pet',[PetController::class, 'store']);
// Route::patch('/pet/{id}',[PetController::class, 'update']);
// Route::delete('/pet/{id}',[PetController::class, 'destroy']);

Route::apiResource('pets', PetController::class);
Route::get('/filterByType', [PetController::class, 'filterByType']);

Route::patch('/pet/{pet}', function (Pet $pet) {
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


//**      Ahed Controllers      **//

// Route::group(['middleware' => 'auth:api'], function () {
Route::apiResource('/ahed/needies', NeediesController::class);
Route::get('/ahed/urgentneedies', [NeediesController::class, 'urgentIndex']);
Route::get('/ahed/allNeedies', [NeediesController::class, 'allNeedies']);
Route::get('/ahed/neediesWithIDs', [NeediesController::class, 'getNeediesWithIDs']);
Route::post('/ahed/needies/addImages/{id}', [NeediesController::class, 'addAssociatedImages']);
Route::post('/ahed/needies/removeImage/{id}', [NeediesController::class, 'removeAssociatedImage']);
Route::apiResource('/ahed/onlinetransactions', OnlineTransactionsController::class);
Route::apiResource('/ahed/offlinetransactions', OfflineTransactionsController::class);
Route::get('/ahed/ahedachievement/{id}', [UserController::class, 'getAhedAchievementRecords']);
// });



//**      Admin Controllers      **//
Route::group(['middleware' => 'api_auth'], function () {
Route::get('/admin', [AdminController::class, 'generalAdminDashboard']);
Route::post('/admin/ahed/approve/{id}', [AdminController::class, 'approve']);
Route::post('/admin/ahed/disapprove/{id}', [AdminController::class, 'disapprove']);
Route::post('/admin/ahed/collect', [AdminController::class, 'collectOfflineTransaction']);
Route::post('/admin/ataa/freezeachievment', [AdminController::class, 'freezeUserAtaaAchievments']);
Route::post('/admin/ataa/defreezeachievment', [AdminController::class, 'defreezeUserAtaaAchievments']);
Route::post('/admin/ataa/addprize', [AdminController::class, 'addAtaaPrize']);
Route::post('/admin/ataa/prize/{id}/activate', [AdminController::class, 'activateAtaaPrize']);
Route::post('/admin/ataa/prize/{id}/deactivate', [AdminController::class, 'deactivateAtaaPrize']);
Route::get('/admin/ban', [AdminController::class, 'getUserBans']);
Route::post('/admin/ban', [AdminController::class, 'addUserBan']);
Route::patch('/admin/ban/activate/{id}', [AdminController::class, 'activateBan']);
Route::patch('/admin/ban/deactivate/{id}', [AdminController::class, 'deactivateBan']);
Route::apiResource('/ataa/prize',AtaaPrizeController::class);
});


Route::post('/token/refresh',[TokensController::class,'refresh']);
