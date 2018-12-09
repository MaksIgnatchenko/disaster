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

Route::get('x', function(\App\Disaster $obj) {
    $user = \App\User::all()->first();
    $countries = $user->locations->pluck('country');
    $disasterCategories = $user->settings->disasterCategories;
    $disasters = \App\Disaster::whereIn('country', $countries)
        ->whereIn('category_code', $disasterCategories)
        ->get();
    foreach($disasters as $disaster) {
//        Fire event for push
    }

});


