<?php

use App\Http\Controllers\PetController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('/breedMe', function () {
//     return view('breedme.main');
// });

Route::get('/pets', function () {
    return view('breedme.pages.pets');
});
Route::get('/pets/add', function () {
    return view('breedme.pages.addpet');
})->name('pet-add');

Route::get('/', [PetController::class, 'index']);
Route::get('pet/{id}', [PetController::class, 'show']);
