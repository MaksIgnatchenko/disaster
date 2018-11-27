<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Services\WeatherHandler;

class AerisWeather
{
    private $baseUrl;
    private $clientID;
    private $clientSecret;
    private $endpoint = null;
    private $action = null;
    private $settings = null;
    private $isBatchRequest = false;
    private $fields = null;

    public function __construct ()
    {
        $this->baseUrl = 'https://api.aerisapi.com/';
        $this->clientID = env('AERIS_WEATHER_CLIENT_ID');
        $this->clientSecret = env('AERIS_WEATHER_CLIENT_SECRET');
    }

    /**
     * @param string $endpoint
     * @return AerisWeather
     */
    public function setEndpoint (string $endpoint) : self
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEndpoint () : ?string
    {
       return $this->endpoint;
    }

    /**
     * @param array $actions
     * @return AerisWeather
     */
    public function setAction (array $actions) : self
    {
        if (count($actions) > 1) {
            $this->isBatchRequest = true;
        }
        $this->action = $actions;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getAction () : ?array
    {
        return $this->action;
    }

    /**
     * @param array $settings
     * @return AerisWeather
     */
    public function setSettings(array $settings) : self
    {
        $this->settings = $settings;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getSettings() : ?array
    {
        return $this->settings;
    }

    /**
     * @param array $fields
     * @return AerisWeather
     */
    public function setFields(array $fields) : self
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getFields() : ?array
    {
        return $this->fields;
    }

    /**
     * @return array|null
     */
    public function request () : ?array
    {
        $url = $this->isBatchRequest ? $this->buildBatchURL() : $this->buildURL();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);

        $data = curl_exec($curl);
        curl_close($curl);

        $result = json_decode ($data, true);

        return $result;
    }

    /**
     * @return string
     */
    private function buildURL () : string
    {

        return $this->baseUrl
            . $this->getEndpoint()
            . '/'
            . ($this->getAction()[0] ?? '')
            . '?'
            . 'client_id='.$this->clientID.'&'
            . 'client_secret='.$this->clientSecret.'&'
            . $this->buildSettingsQuery()
            . $this->buildFieldsQuery();
    }

    /**
     * @return string
     */
    private function buildBatchURL () : string
    {
        $actions = $this->getAction();
        $lastElement = end($actions);
        reset($actions);
        $query = '';
        foreach ($actions as $key => $action) {

            $query .= '/'
                . $this->getEndpoint()
                . '/'
                . $action
                . (($action === $lastElement) ? '' : ',');
        }
        $url = $this->baseUrl
            . 'batch'
            . '?requests='
            . $query
            . '&'
            . 'client_id='.$this->clientID.'&'
            . 'client_secret='.$this->clientSecret.'&'
            . $this->buildSettingsQuery()
            . $this->buildFieldsQuery();
        return $url;
    }

    private function buildSettingsQuery()
    {
        $settingsQuery = '';
        if ($this->settings) {
            foreach ($this->settings as $key => $value) {
                $settingsQuery .= $key.'='.$value.'&';
            }
        }
        return $settingsQuery;
    }

    private function buildFieldsQuery()
    {
        $fieldsQuery = '';
        if ($this->fields) {
            $fieldsQuery .= 'fields=';
            foreach ($this->fields as $field) {
                $fieldsQuery .= $field
                    . (($field === end($this->fields)) ? '' : ',');
            }
        }
        return $fieldsQuery;
    }
}

