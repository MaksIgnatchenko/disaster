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

Route::get('forecast', function() {
    $obj = new \App\Services\Forecast('Kharkiv, Ukraine');
    $response = $obj->getForecast();
    dd($response);
});

Route::get('test', function() {
    $locs = [
//        'kyiv, ukraine',
//        'aberdeen, england',
//        'birmingham, england',
//        'bristol, england',
//        'cambridge, england',
//        'chester, england',
//        'coventry, england',
//        'ely, england',
//        'leeds, england',
//        'lincoln, england',
//        'liverpool, england',
//        'manchester, england',
//        'newcastle, england',
//        'london, england',
//        'oxford, england',
//        'preston, england',
//        'salford, england',
//        'sheffield, england',
//        'wells, england',
//        'winchester, england',
//        'york, england',
//        'berlin, germany',
//        'hamburg, germany',
//        'asddas, germany',
//        'kharkiv,ukraine',
    ];
    $obj = new \App\Services\WeatherHandler\AerisWeather();
    $obj->setEndpoint('forecasts');
    $obj->setAction($locs);
    $obj->setSettings([
        'plimit' => 1,
        'from' => '2018-11-29',
        'to' => '2018-11-29',
        'filter' => 'day',
    ]);
    $obj->setFields([
        'loc',
        'periods.dateTimeISO',
        'periods.minTempC',
        'periods.maxTempC',
        'periods.windSpeedMaxKPH'
    ]);
    try {
        $res = $obj->request();
    } catch(\App\Exceptions\AerisApiErrorException $e) {
        return 'error';
    }
    return $res->getResult();
});
