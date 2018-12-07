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

Route::get('jobs', function() {
    \App\Jobs\ParseWeatherApi::dispatch(new \App\Location(), new \App\Services\WeatherHandler\AerisWeather());
    return rand(1, 100);
});

Route::get('test-job', function() {
   return Cache::tags(['weather'])->get(1);
});

Route::get('test1', function() {
    \App\Jobs\CheckUsersSubscriptions::dispatch()->delay(now()->addMinutes(3));
    return rand(1, 100);
});

Route::get('user', 'UserController@get');

Route::post('user', 'UserController@store');

Route::put('user', 'UserController@update');

Route::get('aeris', function() {
    $obj = new \App\Services\LocationBuilder('Kharkiv, Ukraine');
    $response = $obj->getLocation();
    dd($response);
});


Route::get('test-sub', function() {
//    \App\Jobs\CheckSubscription::dispatch();
//    \App\Jobs\ParseWeatherApi::dispatch();
//    \App\Jobs\CheckUsersSubscriptions::dispatch();
    \App\Jobs\ParseDisasterApi::dispatch();
});



