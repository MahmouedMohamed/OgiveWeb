<?php

use App\Http\Controllers\PetController;
use App\Http\Controllers\PetsArticleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::view('pets', 'breedMe.pets.pets');
 Route::resource('pets', PetController::class);
// ->middleware('auth');
// Route::get('pets', [PetController::class, 'index']);
// Route::post('add-pet', [PetController::class, 'store'])->middleware('auth');;



