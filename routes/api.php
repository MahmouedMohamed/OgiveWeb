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
use App\Http\Controllers\api\OptionsController;

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
Route::post('/anonymous-login', [UserController::class, 'anonymousLogin']);
Route::post('/register', [UserController::class, 'register']);

Route::group(['middleware' => ['UserIsAuthorized']], function () {
    Route::group(['prefix' => 'options', 'as' => 'public'], function () {
        Route::get('/nationalities', [OptionsController::class, 'nationalities']);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::patch('/{user}/profile/picture', [UserController::class, 'updateProfilePicture']);
        Route::patch('/{user}/profile/cover', [UserController::class, 'updateCoverPicture']);
        Route::patch('/{user}/profile/information', [UserController::class, 'updateinformation']);
    });

    //**      Ataa      **//
    Route::group(['prefix' => 'ataa', 'middleware' => ['Bindings']], function () {
        Route::group(['prefix' => 'markers'], function () {
            Route::get('/', [FoodSharingMarkersController::class, 'index']);
            Route::get('/{foodSharingMarker}', [FoodSharingMarkersController::class, 'show']);
            Route::post('/', [FoodSharingMarkersController::class, 'store']);
            Route::patch('/{foodSharingMarker}', [FoodSharingMarkersController::class, 'update']);
            Route::delete('/{foodSharingMarker}', [FoodSharingMarkersController::class, 'destroy']);
            Route::post('/{foodSharingMarker}/collect', [FoodSharingMarkersController::class, 'collect']);
        });
        Route::get('/achievement', [AtaaAchievementController::class, 'show'])->name('anonymous');
        Route::get('/prizes', [AtaaPrizeController::class, 'getAcquired']);
        Route::get('/badges', [AtaaBadgeController::class, 'getAcquired']);
    });

    //**      Memory Wall      **//
    Route::group(['prefix' => 'memorywall', 'middleware' => ['Bindings']], function () {
        //**memories middleware in the controller **//
        Route::group(['prefix' => 'memories'], function () {
            Route::get('/', [MemoryController::class, 'index'])->name('public');
            Route::get('/top', [MemoryController::class, 'getTopMemories'])->name('public');
            Route::get('/{memory}', [MemoryController::class, 'show']);
            Route::post('/', [MemoryController::class, 'store']);
            Route::patch('/{memory}', [MemoryController::class, 'update']);
            Route::delete('/{memory}', [MemoryController::class, 'destroy']);
            Route::post('/{memory}/like', [LikesController::class, 'store']);
            Route::delete('/{memory}/unlike', [LikesController::class, 'destroy']);
        });

        Route::group(['prefix' => 'likes'], function () {
            Route::get('/', [LikesController::class, 'index'])->name('public');
        });
    });

    //**      Breed Me      **//
    Route::group(['prefix' => 'breedme', 'middleware' => ['Bindings']], function () {
        Route::apiResource('pets', PetController::class);
        Route::get('/filterByType', [PetController::class, 'filterByType']);

        Route::apiResource('consultations', ConsultationController::class);
        Route::apiResource('comments', ConsultationCommentController::class);
        Route::apiResource('requests', AdoptionRequestController::class);

        Route::post('myRequests', [AdoptionRequestController::class, 'getRequests']);
        Route::post('sendRequest', [AdoptionRequestController::class, 'sendRequest']);

        Route::apiResource('articles', PetsArticleController::class);


        Route::group(['prefix' => 'places'], function () {
            Route::get('/', [MemoryController::class, 'index'])->name('public');
            Route::get('/{place}', [MemoryController::class, 'show']);
            Route::post('/', [MemoryController::class, 'store']);
            Route::patch('/{place}', [MemoryController::class, 'update']);
            Route::delete('/{place}', [MemoryController::class, 'destroy']);
        });
    });
    //**      Ahed      **//
    Route::group(['prefix' => 'ahed', 'middleware' => ['Bindings']], function () {
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
    Route::group(['prefix' => 'admin', 'middleware' => ['Bindings']], function () {
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
});













Route::post('/token/refresh', [TokensController::class, 'refresh']);
