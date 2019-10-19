<?php

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

//Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile/{user}', 'ProfileController@index')->name('profile.show');

Route::get('/p/create','PostsController@create');
Route::post('/p','PostsController@store');

//Route::post('/marker','MarkersController@createMarker');   //for creating product
Route::get('/marker/{id}','MarkersController@updateMarker'); //for updating product
Route::post('/marker/{id}','MarkersController@deleteMarker');  // for deleting product
//Route::get('/marker','MarkersController@get');

//Route::type('/where to go','which controller'@'which method');
