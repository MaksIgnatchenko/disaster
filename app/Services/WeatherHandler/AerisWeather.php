<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Services\WeatherHandler;

use App\Services\WeatherHandler\Exceptions\AerisApiConnectErrorException;
use App\Services\WeatherHandler\Exceptions\AerisApiResponseErrorException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Log;

class AerisWeather implements WeatherHandlerInterface
{
    public const LIMIT_ITEMS_BATCH_REQUEST = 25;
    private $baseUrl;
    private $clientID;
    private $clientSecret;
    private $endpoint = null;
    private $action = null;
    private $settings = null;
    private $isBatchRequest = false;
    private $fields = null;
    private $client;
    private $httpResponse = null;

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
	 * @return AerisWeather
	 * @throws AerisApiConnectErrorException
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
    public function request() : WeatherHandlerInterface
    {
        $uri = $this->isBatchRequest ? $this->buildBatchURI() : $this->buildURI();
        try {
			$this->httpResponse = $this->getClient()
				->request(
					'GET',
					$uri
				);
		} catch (ClientException $e) {
			throw new AerisApiConnectErrorException('Aeris api connect error');
		}
        return $this;
    }

	/**
	 * @return array
	 * @throws AerisApiResponseErrorException
	 */
    public function getResult() : array
    {
        if ($this->httpResponse) {
            $result = json_decode(
                $this->httpResponse->getBody(),
                true
            );
            if ($result['error']) {
            	throw new AerisApiResponseErrorException('Aeris api error' . $result['error']['description']);
			};
            $this->httpResponse = null;
            if ($result['response']['responses'] ?? null) {
                return $result['response'];
            }
            return [
                'responses' => [$result]];
        }
        return [];
    }

    /**
     * @return Client
     */
    protected function getClient(): Client
    {
        if ($this->client == null) {
            $this->client = new Client(['base_uri' => $this->baseUrl]);
        }

        return $this->client;
    }

    /**
     * @return string
     */
    private function buildURI () : string
    {

        $uri = $this->getEndpoint()
            . '/'
            . ($this->getAction()[0] ?? '')
            . '?'
            . 'client_id='.$this->clientID.'&'
            . 'client_secret='.$this->clientSecret.'&'
            . $this->buildSettingsQuery()
            . $this->buildFieldsQuery();
        return $uri;
    }

    /**
     * @return string
     */
    private function buildBatchURI () : string
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
        $uri = 'batch'
            . '?requests='
            . $query
            . '&'
            . 'client_id='.$this->clientID.'&'
            . 'client_secret='.$this->clientSecret.'&'
            . $this->buildSettingsQuery()
            . $this->buildFieldsQuery();
        return $uri;
    }

    /**
     * @return string
     */
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

    /**
     * @return string
     */
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

