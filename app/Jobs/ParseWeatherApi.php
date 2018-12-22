<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Jobs;

use App\Services\Forecast;
use App\Services\WeatherHandler\AerisWeather;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ParseWeatherApi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $locations;
    private $apiHandler;
    private $user;
    private $cacheTtl;

    /**
     * Create a new job instance.
     *
     * @param AerisWeather $apiHandler
     * @param Collection $locations
     * @param User $user
     */
    public function __construct(AerisWeather $apiHandler, Collection $locations, User $user)
    {
        $this->apiHandler = $apiHandler;
        $this->locations = $locations;
        $this->user = $user;
        $this->cacheTtl = config('app_settings.cache_api_result_ttl');
    }

    public function handle()
    {
        $this->apiHandler->setEndpoint('forecasts');

        $today = Carbon::now()->toDateString();
        $this->apiHandler->setSettings([
            'plimit' => 1,
            'from' => $today,
            'to' => $today,
            'filter' => 'day',
        ]);
        $this->apiHandler->setFields([
            'timestamp',
            'periods.minTempC',
            'periods.maxTempC',
            'periods.windSpeedMaxKPH'
        ]);

        $chunks = $this->locations->chunk(AerisWeather::LIMIT_ITEMS_BATCH_REQUEST);
        $resultSet = [];
        foreach ($chunks as $chunk) {
            $batchRequestLocations = [];
            foreach ($chunk as $location) {
                $batchRequestLocations[] = [
                    'id' => $location->id,
                    'query' => $location->place . ',' . $location->country
                ];
            }
            $locationsCoords = collect($batchRequestLocations)
                ->transform(function ($item) {
                    return $item['query'];
                })
                ->toArray();
            $this->apiHandler->setAction($locationsCoords);
            try {
                $this->apiHandler->request();
            } catch(\App\Services\WeatherHandler\Exceptions\AerisApiConnectErrorException $e) {
                Log::error($e->getMessage());
                break;
            }
            try {
                $forecasts =  $this->apiHandler->getResult();
            } catch (\App\Services\WeatherHandler\Exceptions\AerisApiResponseErrorException $e) {
                Log::error($e->getMessage());
                continue;
            }
            for ($i = 0; $i < count($batchRequestLocations); $i++) {
                $key = $batchRequestLocations[$i]['id'];
                if ($forecasts['responses'][$i]['success']) {
                    $resultSet[$key] = $forecasts['responses'][$i]['response'][0]['periods'][0];
                }
            }
        }
        $settings = $this->user->settings;
        foreach ($this->locations as $location) {
            $forecast = new Forecast($settings, $resultSet[$location->id]);
            if ($forecast->checkExceeding()) {
                SendPushJob::dispatch(
                    $this->user->pushToken,
                    $location->place . ' ' . $location->country . ' ' . Carbon::createFromTimestamp($forecast->getTimestamp())->toDateString(),
                    $forecast->getMessage()
                );
            }
        }
    }
}
