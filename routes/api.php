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

Route::middleware(['UserIsAuthorized'])->prefix('users')->group(function () {
    Route::patch('/{user}/profile/picture', [UserController::class, 'updateProfilePicture']);
    Route::patch('/{user}/profile/cover', [UserController::class, 'updateCoverPicture']);
    Route::patch('/{user}/profile/information', [UserController::class, 'updateinformation']);
});

//**      Ataa Controllers      **//
//* * Optimized * */
Route::middleware(['UserIsAuthorized'])->prefix('ataa')->group(function () {
    Route::apiResource('/markers', FoodSharingMarkersController::class);
    Route::patch('/collect/{id}', [FoodSharingMarkersController::class, 'collect']);
    Route::get('/achievement/{id}', [AtaaAchievementController::class, 'show']);
    Route::get('/prizes', [AtaaPrizeController::class, 'getAcquired']);
    Route::get('/badges', [AtaaBadgeController::class, 'getAcquired']);
});


//**      Memory Wall Controllers      **//
//* * Optimized * */
Route::prefix('memorywall')->group(function () {
    //**memories middleware in the controller **//
    Route::apiResource('/memories', MemoryController::class);
    Route::middleware(['UserIsAuthorized'])->apiResource('/likes', LikesController::class);
    Route::get('/top-memories', [MemoryController::class, 'getTopMemories']);
});

//TODO: Add This APIs to be auth by 2oauth token

//**      Breed Me Controllers      **//
Route::
    // middleware(['UserIsAuthorized'])->
    prefix('breedme')->group(function () {
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
    });


//**      Ahed Controllers      **//
//* * Optimized * */
Route::middleware(['UserIsAuthorized'])->prefix('ahed')->group(function () {
    Route::apiResource('/needies', NeediesController::class);
    Route::get('/urgent-needies', [NeediesController::class, 'urgentIndex']);
    Route::get('/needies-with-ids', [NeediesController::class, 'getNeediesWithIDs']);
    Route::post('/needies/add-images/{id}', [NeediesController::class, 'addAssociatedImages']);
    Route::post('/needies/remove-image/{id}', [NeediesController::class, 'removeAssociatedImage']);
    Route::apiResource('/onlinetransactions', OnlineTransactionsController::class);
    Route::apiResource('/offlinetransactions', OfflineTransactionsController::class);
    Route::get('/ahedachievement/{id}', [UserController::class, 'getAhedAchievementRecords']);
});



//**      Admin Controllers      **//
Route::middleware(['api_auth', 'Bindings'])->prefix('admin')->group(function () {
    //* * Optimized * */
    Route::get('/', [AdminController::class, 'generalAdminDashboard']);
    //**      Ahed      **//
    //* * Optimized * */
    Route::get('/pending-needies', [AdminController::class, 'getPendingNeedies']);
    Route::post('/ahed/approve/{id}', [AdminController::class, 'approve']);
    Route::post('/ahed/disapprove/{id}', [AdminController::class, 'disapprove']);
    Route::patch('/ahed/collect', [AdminController::class, 'collectOfflineTransaction']);
    //**      Ataa      **//
    //* * Optimized * */
    Route::apiResource('/ataa/prize', AtaaPrizeController::class);
    Route::patch('/ataa/prize/{id}/activate', [AtaaPrizeController::class, 'activate']);
    Route::patch('/ataa/prize/{id}/deactivate', [AtaaPrizeController::class, 'deactivate']);
    Route::apiResource('/ataa/badge', AtaaBadgeController::class);
    Route::patch('/ataa/badge/{id}/activate', [AtaaBadgeController::class, 'activate']);
    Route::patch('/ataa/badge/{id}/deactivate', [AtaaBadgeController::class, 'deactivate']);
    Route::get('/ataa/achievement', [AdminController::class, 'getAtaaAchievements']);
    Route::post('/ataa/freeze-achievement', [AdminController::class, 'freezeUserAtaaAchievements']);
    Route::post('/ataa/defreeze-achievement', [AdminController::class, 'defreezeUserAtaaAchievements']);
    //**      Ban      **//
    Route::get('/ban', [AdminController::class, 'getUserBans']);
    Route::post('/ban', [AdminController::class, 'addUserBan']);
    Route::patch('/ban/activate/{userBan}', [AdminController::class, 'activateBan']);
    Route::patch('/ban/deactivate/{userBan}', [AdminController::class, 'deactivateBan']);
    //**      Import      **//
    //* * Not Optimized * */
    Route::post('/importCSV', [AdminController::class, 'importCSV']);
});


Route::post('/token/refresh', [TokensController::class, 'refresh']);
