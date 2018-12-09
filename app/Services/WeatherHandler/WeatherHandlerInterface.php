<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 05.12.2018
 *
 */

namespace App\Services\WeatherHandler;

interface WeatherHandlerInterface
{

    /**
     * Making a request to api.
     *
     * @return WeatherHandlerInterface
     */
    public function request() : WeatherHandlerInterface;

    /**
     * Parse response and return array of result items
     *
     * @return array
     */
    public function getResult() : array;
}
