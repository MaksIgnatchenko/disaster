<?php
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

Route::middleware('auth.api')->group(function() {
    Route::get('user', 'UserController@get');
    Route::put('user', 'UserController@update');
    Route::put('settings', 'SettingsController@update');
});

Route::post('user', 'UserController@store');

