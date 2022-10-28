<?php

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

Route::post('login', 'AuthController@Login');
Route::post('register', 'AuthController@register');

Route::middleware(['auth:api-user'])->group(function () {
    Route::get('me', 'AuthController@me');
    Route::resource('sets', 'SetController');
    Route::post('logout', 'AuthController@logout');
});
