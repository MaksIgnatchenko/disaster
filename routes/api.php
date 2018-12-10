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
    \App\Jobs\ParseWeatherApi::dispatch();
//    \App\Jobs\CheckUsersSubscriptions::dispatch();
//    \App\Jobs\ParseDisasterApi::dispatch(new \App\Services\DisasterHandler\HiszRsoeApiHandler\HiszRsoeApiHandler(), \App\Disaster::class);
//    \App\Jobs\ParseDisasterApi::dispatch();
});

Route::get('users', function() {
    dd(\App\User::all()->toArray());
});

Route::get('locations', function() {
    dd(\App\Location::all()->toArray());
});

Route::get('disasters', function() {
    dd(\App\Disaster::all()->toArray());
});

Route::get('dis', function() {
    $cidAbove = \Illuminate\Support\Facades\DB::table('disasters')->max('cid');
    $this->apiHandler = new \App\Services\DisasterHandler\HiszRsoeApiHandler\HiszRsoeApiHandler($cidAbove);
    try {
        $this->apiHandler->request();
    } catch (\App\Services\DisasterHandler\Exceptions\DisasterApiConnectErrorException $e) {
        Log::error($e->getMessage());
        exit();
    }
    try {
        $response = $this->apiHandler->getResult();
    } catch(\App\Services\DisasterHandler\Exceptions\DisasterApiResponseErrorException $e) {
        Log::alert($e->getMessage());
        exit();
    }
    Log::alert(print_r($response, true));
});

Route::get('dis-del', function() {
    \Illuminate\Support\Facades\DB::table('disasters')->delete();
});

Route::get('x', function() {
	$tz = \App\User::first()->settings->timezone;
//	$string = \Carbon\Carbon::today()->toDateString() . ' ' . config('app_settings.morning_push_time');
	$string = \Carbon\Carbon::today()->toDateString() . ' ' . config('app_settings.evening_push_time');
	$pushTime = \Carbon\Carbon::createFromTimeString($string);
	$userTime = \Carbon\Carbon::now($tz);
	$diff = $userTime->diffInMinutes($pushTime, false);
});


