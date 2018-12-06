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
    \App\Jobs\Test::dispatch()->delay(now()->addMinutes(3));
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


Route::get('test', function() {
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
    $chunks = $locations->chunk(\App\Services\WeatherHandler\AerisWeather::LIMIT_ITEMS_BATCH_REQUEST);
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
    Cache::tags(['weather'])->putMany($resultSet, 2880);
    return response()->json($resultSet);
});

Route::get('test-cache', function () {
//	dd(Cache::tags(['weather'])->get(1));
//	dd(\Illuminate\Support\Facades\Redis::get(1));
    dd(Cache::tags(['disaster_meta'])->get('keys'));
});

Route::get('test-disaster', function() {
    $cidAbove = Cache::tags(['disaster_meta'])->get('cid_above', 1);
	$apiHandler = new \App\Services\DisasterHandler\DisasterApiHandler($cidAbove);
	try {
		$apiHandler->request();
	} catch (\App\Services\DisasterHandler\Exceptions\HiszRsoeApiConnectErrorException $e) {
		Log::error($e->getMessage());
	}
	$disasters = $apiHandler->getResult();
    $yesterday = \Carbon\Carbon::yesterday();
    $resultSet = [];
    $disastersMetaData = [];
	foreach($disasters as $disaster) {
	    $disasterDate = \Carbon\Carbon::parse($disaster['event_date']);
	    if ($disasterDate->greaterThanOrEqualTo($yesterday)) {
            $resultSet[$disaster['cid']] = $disaster;
            $cidAbove = ($disaster['cid'] > $cidAbove) ? $disaster['cid'] : $cidAbove;
            $disastersMetaData['keys'][] = $disaster['cid'];
        }
    }
    $disastersMetaData['cid_above'] = $cidAbove ?? 1;
    Cache::tags(['disaster_meta'])->flush();
    Cache::tags(['disaster_meta'])->putMany($disastersMetaData, 2880);
    Cache::tags(['disaster'])->flush();
    Cache::tags(['disaster'])->putMany($resultSet, 2880);
    return response()->json($resultSet);
});

Route::get('dis-job', function() {
    \App\Jobs\ParseDisasterApi::dispatch();
//    $x = [1, 2, 3];
//    $y = print_r($x, true);
//    echo $y;
});

