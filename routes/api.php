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
    \App\Jobs\WeatherApiParse::dispatch();
    return rand(1, 100);
});

Route::get('test-job', function() {
   return Cache::tags(['weather'])->get(1);
});

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
//    $locs = [
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
//    ];
//    $obj = new \App\Services\WeatherHandler\AerisWeather();
//    $obj->setEndpoint('forecasts');
//    $obj->setAction($locs);
//    $obj->setSettings([
//        'plimit' => 1,
//        'from' => '2018-11-29',
//        'to' => '2018-11-29',
//        'filter' => 'day',
//    ]);
//    $obj->setFields([
//        'loc',
//        'periods.dateTimeISO',
//        'periods.minTempC',
//        'periods.maxTempC',
//        'periods.windSpeedMaxKPH'
//    ]);
//    try {
//        $res = $obj->request();
//    } catch(\App\Services\WeatherHandler\Exceptions\AerisApiConnectErrorException $e) {
//        return $e->getMessage();
//    }
//    try {
//		$result =  $res->getResult();
//	} catch (\App\Services\WeatherHandler\Exceptions\AerisApiResponseErrorException $e) {
//		return $e->getMessage();
//	}
//	return $result;

    $apiHandler = new \App\Services\WeatherHandler\AerisWeather();
    $apiHandler->setEndpoint('forecasts');
    $apiHandler->setSettings([
        'plimit' => 1,
        'from' => \Carbon\Carbon::now()->toDateString(),
        'to' => \Carbon\Carbon::now()->toDateString(),
        'filter' => 'day',
    ]);
    $apiHandler->setFields([
        'loc',
        'periods.dateTimeISO',
        'periods.minTempC',
        'periods.maxTempC',
        'periods.windSpeedMaxKPH'
    ]);

    $resultSet = [];
    $locationModel = app()[\App\Location::class];
    $locations = $locationModel->all();
    $chunks = $locations->chunk(2);
    $resultSet = [];
    foreach ($chunks as $chunk) {
        $batchRequestLocations = [];
        foreach ($chunk as $location) {
            $batchRequestLocations[] = [
                'id' => $location->id,
                'query' => $location->lat . ',' . $location->long
            ];
        }

        $locationsCoords = collect($batchRequestLocations)
            ->transform(function ($item) {
                return $item['query'];
            })
            ->toArray();
        $apiHandler->setAction($locationsCoords);
        try {
            $response = $apiHandler->request();
        } catch(\App\Services\WeatherHandler\Exceptions\AerisApiConnectErrorException $e) {
            Log::error($e->getMessage());
            break;
        }
        try {
            $forecasts =  $response->getResult();
        } catch (\App\Services\WeatherHandler\Exceptions\AerisApiResponseErrorException $e) {
            Log::error($e->getMessage());
            continue;
        }
        for ($i = 0; $i < count($batchRequestLocations); $i++) {
            $key = $batchRequestLocations[$i]['id'];
            if ($forecasts[$i]['success']) {
                $resultSet[$key] = $forecasts[$i]['response'][0]['periods'][0];
            }
        }
    }
    Cache::tags(['weather'])->flush();
    Cache::tags(['weather'])->putMany($resultSet, 1500);
    return response()->json($resultSet);
});
