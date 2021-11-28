<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\TokensController;

/* Breed Me */
use App\Http\Controllers\api\BreedMe\AdoptionRequestController;
use App\Http\Controllers\api\BreedMe\ConsultationCommentController;
use App\Http\Controllers\api\BreedMe\ConsultationController;
use App\Http\Controllers\api\BreedMe\PetController;
use App\Http\Controllers\api\BreedMe\PetsArticleController;
use App\Http\Controllers\api\BreedMe\PlaceController;

/* Memory Wall */
use App\Http\Controllers\api\MemoryWall\MemoryController;
use App\Http\Controllers\api\MemoryWall\LikesController;

/* Ataa */
use App\Http\Controllers\api\Ataa\FoodSharingMarkersController;
use App\Http\Controllers\api\Ataa\AtaaAchievementController;
use App\Http\Controllers\api\Ataa\AtaaPrizeController;
use App\Http\Controllers\api\Ataa\AtaaBadgeController;

/* Ahed */
use App\Http\Controllers\api\Ahed\NeediesController;
use App\Http\Controllers\api\Ahed\OfflineTransactionsController;
use App\Http\Controllers\api\Ahed\OnlineTransactionsController;

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
// Route::middleware(['api_auth'])->prefix('ataa')->group(function () {
Route::apiResource('/markers', FoodSharingMarkersController::class);
Route::patch('/collect/{id}', [FoodSharingMarkersController::class, 'collect']);
Route::get('/achievement/{id}', [AtaaAchievementController::class, 'show']);
Route::apiResource('/prize', AtaaPrizeController::class);
Route::post('/prize/{id}/activate', [AtaaPrizeController::class, 'activate']);
Route::post('/prize/{id}/deactivate', [AtaaPrizeController::class, 'deactivate']);
Route::apiResource('/badge', AtaaBadgeController::class);
Route::post('/badge/{id}/activate', [AtaaBadgeController::class, 'activate']);
Route::post('/badge/{id}/deactivate', [AtaaBadgeController::class, 'deactivate']);
// });


// Route::group(['middleware' => 'auth:api'], function () {
Route::get('/memory', [MemoryController::class, 'getAll']);
Route::post('/memory', [MemoryController::class, 'create']);
Route::delete('/memory', [MemoryController::class, 'delete']);
Route::post('/like', [LikesController::class, 'likeUnlike']);
// });

//TODO: Add This APIs to be auth by 2oauth token

Route::apiResource('pets', PetController::class);
Route::get('/filterByType', [PetController::class, 'filterByType']);

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

Route::middleware(['api_auth'])->prefix('ahed')->group(function () {
Route::apiResource('/needies', NeediesController::class);
Route::get('/urgentneedies', [NeediesController::class, 'urgentIndex']);
Route::get('/allNeedies', [NeediesController::class, 'getAllNeedies']);
Route::get('/neediesWithIDs', [NeediesController::class, 'getNeediesWithIDs']);
Route::post('/needies/addImages/{id}', [NeediesController::class, 'addAssociatedImages']);
Route::post('/needies/removeImage/{id}', [NeediesController::class, 'removeAssociatedImage']);
Route::apiResource('/onlinetransactions', OnlineTransactionsController::class);
Route::apiResource('/offlinetransactions', OfflineTransactionsController::class);
Route::get('/ahedachievement/{id}', [UserController::class, 'getAhedAchievementRecords']);
});



//**      Admin Controllers      **//
Route::middleware(['api_auth'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'generalAdminDashboard']);
    Route::post('/ahed/approve/{id}', [AdminController::class, 'approve']);
    Route::post('/ahed/disapprove/{id}', [AdminController::class, 'disapprove']);
    Route::post('/ahed/collect', [AdminController::class, 'collectOfflineTransaction']);
    Route::post('/ataa/freezeachievment', [AdminController::class, 'freezeUserAtaaAchievements']);
    Route::post('/ataa/defreezeachievment', [AdminController::class, 'defreezeUserAtaaAchievements']);
    Route::get('/ban', [AdminController::class, 'getUserBans']);
    Route::post('/ban', [AdminController::class, 'addUserBan']);
    Route::patch('/ban/activate/{id}', [AdminController::class, 'activateBan']);
    Route::patch('/ban/deactivate/{id}', [AdminController::class, 'deactivateBan']);
    Route::post('/importCSV',[AdminController::class,'importCSV']);
});


Route::post('/token/refresh', [TokensController::class, 'refresh']);
