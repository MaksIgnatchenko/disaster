<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Services;

use maciejkrol\AerisWeather\AerisWeather;

class LocationBuilder
{
    private $apiHandler;
    private $endpoint = 'places';

    public function __construct($searchedLocation)
    {
        $this->apiHandler = new AerisWeather(
            env('AERIS_WEATHER_CLIENT_ID'),
            env('AERIS_WEATHER_CLIENT_SECRET')
        );
        $this->apiHandler->endpoint($this->endpoint);
        $this->apiHandler->action($searchedLocation);
    }

    public function getLocation()
    {
        return $this->apiHandler->request(['format' => 'json', 'limit' => 1]);
    }
}