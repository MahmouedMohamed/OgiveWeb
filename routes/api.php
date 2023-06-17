<?php

use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\api\Ahed\NeedyController;
use App\Http\Controllers\api\Ahed\OfflineTransactionController;
use App\Http\Controllers\api\Ahed\OnlineTransactionController;
/* Breed Me */
use App\Http\Controllers\api\Ataa\AtaaAchievementController;
use App\Http\Controllers\api\Ataa\AtaaBadgeController;
use App\Http\Controllers\api\Ataa\AtaaPrizeController;
use App\Http\Controllers\api\Ataa\FoodSharingMarkersController;
use App\Http\Controllers\api\BreedMe\AdoptionRequestController;
/* Memory Wall */
use App\Http\Controllers\api\BreedMe\ConsultationCommentController;
use App\Http\Controllers\api\BreedMe\ConsultationController;
/* Ataa */
use App\Http\Controllers\api\BreedMe\PetController;
use App\Http\Controllers\api\BreedMe\PetsArticleController;
use App\Http\Controllers\api\MemoryWall\LikesController;
use App\Http\Controllers\api\MemoryWall\MemoryController;
/* Ahed */
use App\Http\Controllers\api\OptionsController;
use App\Http\Controllers\api\TokensController;
use App\Http\Controllers\api\UserAccountController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\UserSettingsController;
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
        Route::post('/account/deposit', [UserAccountController::class, 'deposit']);
        Route::post('/account/withdrawal', [UserAccountController::class, 'withdrawal']);
        Route::get('/settings', [UserSettingsController::class, 'show']);
        Route::post('/settings', [UserSettingsController::class, 'storeOrUpdate']);
        Route::put('/settings', [UserSettingsController::class, 'storeOrUpdate']);
    });

    //**      Ataa      **//
    Route::group(['prefix' => 'ataa', 'middleware' => ['Bindings']], function () {
        Route::group(['prefix' => 'markers', 'as' => 'anonymous'], function () {
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
        Route::apiResource('/needies', NeedyController::class);
        Route::get('/urgent-needies', [NeedyController::class, 'urgentIndex']);
        Route::get('/needies-with-ids', [NeedyController::class, 'getNeediesWithIDs']);
        Route::put('/needies/{needy}/add-images', [NeedyController::class, 'addAssociatedImages']);
        Route::put('/needies/medias/{needyMedia}/remove-image/', [NeedyController::class, 'removeAssociatedImage']);
        Route::get('/offlinetransactions', [OfflineTransactionController::class, 'index']);
        Route::post('offlinetransactions', [OfflineTransactionController::class, 'store']);
        Route::get('/offlinetransactions/{offlineTransaction}', [OfflineTransactionController::class, 'show']);
        Route::put('/offlinetransactions/{offlineTransaction}', [OfflineTransactionController::class, 'update']);
        Route::delete('/offlinetransactions/{offlineTransaction}', [OfflineTransactionController::class, 'destroy']);
        Route::get('/onlinetransactions', [OnlineTransactionController::class, 'index']);
        Route::post('/onlinetransactions', [OnlineTransactionController::class, 'store']);
        Route::get('/onlinetransactions/{onlineTransaction}', [OnlineTransactionController::class, 'show']);
        Route::get('/ahedachievement', [UserController::class, 'getAhedAchievementRecords']);
    });

    //**      Admin Controllers      **//
    Route::group(['prefix' => 'admin', 'middleware' => ['Bindings']], function () {
        Route::get('/', [AdminController::class, 'generalAdminDashboard']);
        //**      Ahed      **//
        Route::get('/pending-needies', [AdminController::class, 'getPendingNeedies']);
        Route::post('/ahed/approve/{needy}', [AdminController::class, 'approve']);
        Route::post('/ahed/disapprove/{needy}', [AdminController::class, 'disapprove']);
        Route::patch('/ahed/offlinetransactions/{offlineTransaction}/collect', [AdminController::class, 'collectOfflineTransaction']);
        //**      Ataa      **//
        Route::group(['prefix' => 'ataa'], function () {
            Route::get('/prizes', [AtaaPrizeController::class, 'index']);
            Route::post('/prizes', [AtaaPrizeController::class, 'store']);
            Route::patch('/prizes/{prize}/activate', [AtaaPrizeController::class, 'activate']);
            Route::patch('/prizes/{prize}/deactivate', [AtaaPrizeController::class, 'deactivate']);
            Route::get('/badges', [AtaaBadgeController::class, 'index']);
            Route::post('/badges', [AtaaBadgeController::class, 'store']);
            Route::patch('/badges/{badge}/activate', [AtaaBadgeController::class, 'activate']);
            Route::patch('/badges/{badge}/deactivate', [AtaaBadgeController::class, 'deactivate']);
            Route::get('/achievement', [AdminController::class, 'getAtaaAchievements']);
            Route::post('/users/{user}/freeze-achievement', [AdminController::class, 'freezeUserAtaaAchievements']);
            Route::post('/users/{user}/defreeze-achievement', [AdminController::class, 'defreezeUserAtaaAchievements']);
        });
        //**      Ban      **//
        Route::get('/users/{bannedUser}/ban', [AdminController::class, 'getUserBans']);
        Route::post('/users/{bannedUser}/ban', [AdminController::class, 'addUserBan']);
        Route::patch('/ban/activate/{userBan}', [AdminController::class, 'activateBan']);
        Route::patch('/ban/deactivate/{userBan}', [AdminController::class, 'deactivateBan']);
        //**      Import      **//
        //* * Not Optimized * */
        Route::post('/importCSV', [AdminController::class, 'importCSV']);
    });
});

Route::post('/token/refresh', [TokensController::class, 'refresh']);
