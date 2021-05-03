<?php

use App\Http\Controllers\HomeController;
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
Route::get('/about-us', function () {
    return view('breedme.pages.about-us');
});
Route::get('/articles', function () {
    return view('breedme.pages.articles');
});

// Route::prefix('Al-Ahed')->group(function () {
Route::get('/ahed', [HomeController::class, 'ahedIndex'])->name('ahed');
Route::get('/ahed/cases', [NeediesController::class, 'index']);
Route::get('/ahed/cases/{id}', [CasesController::class, 'show']);
Route::post('/ahed/cases', [CasesController::class, 'store']);
Route::get('/ahed/cases/{id}/edit', [CasesController::class, 'edit']);
Route::put('/ahed/cases/{id}/edit', [CasesController::class, 'update']);
Route::delete('/ahed/cases/{id}', [CasesController::class, 'delete']);

Route::get('/ahed/transactions/{user}', [TransactionsController::class, 'index']);
Route::get('/ahed/transactions/{id}', [TransactionsController::class, 'show']);
Route::post('/ahed/transactions', [TransactionsController::class, 'store']);
Route::get('/ahed/transactions/{id}/edit', [TransactionsController::class, 'edit']);
Route::put('/ahed/transactions/{id}/edit', [TransactionsController::class, 'update']);
Route::delete('/ahed/transactions/{id}', [TransactionsController::class, 'delete']);
// });
// Route::get('/Al-Ahed/{path?}', function () {
//     return view('ahed.ahed');
// });

Route::view('/{path?}', 'ahed.ahed')
    ->where('path', '.*')
    ->name('react');
