<?php

use Illuminate\Http\Request;
use GuzzleHttp\Client;

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

Route::post('user', function(){

});

Route::put('user', function(){

});

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
        'kyiv, ukraine',
        'aberdeen, england',
        'birmingham, england',
        'bristol, england',
        'cambridge, england',
        'chester, england',
        'coventry, england',
        'ely, england',
        'leeds, england',
        'lincoln, england',
        'liverpool, england',
        'manchester, england',
        'newcastle, england',
        'london, england',
        'oxford, england',
        'preston, england',
        'salford, england',
        'sheffield, england',
        'wells, england',
        'winchester, england',
        'york, england',
        'berlin, germany',
        'hamburg, germany',
        'asddas, germany',
        'kharkiv,ukraine',
    ];
    $obj = new \App\Services\WeatherHandler\AerisWeather();
    $obj->setEndpoint('forecasts');
    $obj->setAction($locs);
    $obj->setSettings([
        'plimit' => 1,
        'from' => '2018-11-27',
        'to' => '2018-11-27',
        'filter' => 'day',
    ]);
    $obj->setFields([
        'loc',
        'periods.dateTimeISO',
        'periods.minTempC',
        'periods.maxTempC',
        'periods.windSpeedMaxKPH'
    ]);
    $collection = $obj->request()['response'];
});
