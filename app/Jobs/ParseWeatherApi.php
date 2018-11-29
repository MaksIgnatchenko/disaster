<?php

namespace App\Jobs;

use App\Location;
use App\Services\WeatherHandler\AerisWeather;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ParseWeatherApi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $locationModel;
    private $apiHandler;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function handle()
    {
        $this->locationModel = app()[Location::class];
        $this->apiHandler = new AerisWeather();
        $this->apiHandler->setEndpoint('forecasts');
        $this->apiHandler->setSettings([
            'plimit' => 1,
            'from' => \Carbon\Carbon::now()->toDateString(),
            'to' => \Carbon\Carbon::now()->toDateString(),
            'filter' => 'day',
        ]);
        $this->apiHandler->setFields([
            'loc',
            'periods.dateTime',
            'periods.minTempC',
            'periods.maxTempC',
            'periods.windSpeedMaxKPH'
        ]);

        $locations = $this->locationModel->all();
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
            $this->apiHandler->setAction($locationsCoords);
            try {
                $response = $this->apiHandler->request();
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
        Cache::tags(['weather'])->flsush();
        Cache::tags(['weather'])->putMany($resultSet, 1500);
    }
}
