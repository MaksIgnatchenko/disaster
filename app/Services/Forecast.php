<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Services;

use App\Enums\TempUnitEnum;
use App\Enums\WindSpeedUnitEnum;
use App\Settings;

class Forecast
{
    /**
     * @var string
     */
    private $timestamp;

    /**
     * @var int|null
     */
    private $minTemp;

    /**
     * @var int|null
     */
    private $maxTemp;

    /**
     * @var string|null
     */
    private $tempUnit;

    /**
     * @var string|null
     */
    private $windSpeedUnit;

    /**
     * @var int|null
     */
    private $minTempCForecast;

    /**
     * @var int|null
     */
    private $maxTempCForecast;

    /**
     * @var int|null
     */
    private $windSpeedMaxKPHForecast;

    /**
     * @var int (m/s)
     */
    private $maxWindSpeed;

    /**
     * @var string|null
     */
    private $message;

    /**
     * Forecast constructor.
     * @param Settings $settings
     * @param array $data
     */
    public function __construct(Settings $settings, array $data)
    {
        $this->minTemp = $settings->minTemp;
        $this->maxTemp = $settings->maxTemp;
        $this->tempUnit = $settings->tempUnit;
        $this->windSpeedUnit = $settings->windSpeedUnit;
        $this->timestamp = $data['timestamp'];
        $this->minTempCForecast = $data['minTempC'] ?? null;
        $this->maxTempCForecast = $data['maxTempC'] ?? null;
        $this->windSpeedMaxKPHForecast = $data['windSpeedMaxKPH'] ?? null;
        $this->maxWindSpeed = 14;
        $this->message = null;
    }

    /**
     * @return bool
     */
    public function checkExceeding() : bool
    {
        return
            $this->isExceededMinTemp() ||
            $this->isExceededMaxTemp() ||
            $this->isExceededMaxWindSpeed();

    }

    /**
     * @return null|string
     */
    public function getMessage() : ?string
    {
        return $this->message;
    }

    /**
     * @return bool
     */
    public function isExceededMinTemp() : bool
    {
        if ($this->minTemp && $this->tempUnit && $this->minTempCForecast) {
            $minTempForecast = $this->convertTempToUsersUnit($this->minTempCForecast);
            $this->message .= '<br>' . 'Min temperature ' . $minTempForecast . ' ' . $this->tempUnit;
            return $minTempForecast <= $this->minTemp;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isExceededMaxTemp() : bool
    {
        if ($this->maxTemp && $this->tempUnit && $this->maxTempCForecast) {
            $maxTempForecast = $this->convertTempToUsersUnit($this->maxTempCForecast);
            $this->message .= '<br>' . 'Max temperature: ' . $maxTempForecast . ' ' . $this->tempUnit;
            return $maxTempForecast >= $this->maxTemp;
        }
        return false;
    }

    /**
     * @return int
     */
    private function convertTempToUsersUnit(int $temp) : int
    {
        switch ($this->tempUnit) {
            case TempUnitEnum::CELSIUS :
                return $temp;
            case TempUnitEnum::FAHRENHEIT :
                return ($temp * 1.8) + 32;
        }
    }

    /**
     * @return bool
     */
    public function isExceededMaxWindSpeed() : bool
    {
        if ($this->windSpeedUnit && $this->windSpeedMaxKPHForecast) {
            $maxWindSpeedForecast = $this->convertWindSpeedForecastToUsersUnit();
            $this->message .= '<br>' . 'Max wind speed: ' . $maxWindSpeedForecast . ' ' . $this->windSpeedUnit;
            return $this->getForecastWindSpeedMs() <= $this->maxWindSpeed;
        }
        return false;
    }

    /**
     * @return int
     */
    public function getTimestamp() : int
    {
        return $this->timestamp;
    }

    /**
     * @return int
     */
    private function getForecastWindSpeedMs() : int
    {
        return $this->windSpeedMaxKPHForecast * 1000 / 3600;
    }

    /**
     * @return int
     */
    private function convertWindSpeedForecastToUsersUnit() : int
    {
        switch ($this->windSpeedUnit) {
            case WindSpeedUnitEnum::MS :
                return $this->windSpeedMaxKPHForecast * 1000 / 3600;
            case WindSpeedUnitEnum::KPH :
                return $this->windSpeedMaxKPHForecast;
            case WindSpeedUnitEnum::MPH :
                return $this->windSpeedMaxKPHForecast * 0.621;
        }
    }
}
