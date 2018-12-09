<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 05.12.2018
 *
 */

namespace App\Services\DisasterHandler\HiszRsoeApiHandler;

use App\Services\DisasterHandler\DisasterHandlerInterface;
use App\Services\DisasterHandler\HiszRsoeApiHandler\Exceptions\HiszRsoeApiConnectErrorException;
use App\Services\DisasterHandler\HiszRsoeApiHandler\Exceptions\HiszRsoeApiResponseErrorException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;

class HiszRsoeApiHandler implements DisasterHandlerInterface
{
	private $baseUrl;
	private $apiKey;
	private $client;
	private $responseResultsLimit;
	private $minCid;
	private $httpResponse;

	public function __construct ()
	{
		$this->baseUrl = 'https://hisz.rsoe.hu/ws/event';
		$this->responseResultsLimit = 1000;
		$this->apiKey = env('HISZ_RSOE_API_KEY');
	}

	/**
	 * @return HiszRsoeApiHandler
	 * @throws HiszRsoeApiConnectErrorException
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function request() : DisasterHandlerInterface
	{
		try {
			$this->httpResponse = $this->getClient()->request(
				'GET',
				$this->buildQuery()
			);

		} catch (ConnectException $e) {
			throw new HiszRsoeApiConnectErrorException('https://hisz.rsoe.hu/ws/event api connect error');
		}
		return $this;
	}

    /**
     * @return array
     * @throws \Throwable
     */
	public function getResult() : array
	{
		if ($this->httpResponse) {
			$result = json_decode(
				$this->httpResponse->getBody(),
				true
			);
			$error = $result['error_report'] ?? null;
			throw_if($error, new HiszRsoeApiResponseErrorException('https://hisz.rsoe.hu/ws/event response error: ' . $error));
			return $result['result'] ?? [];
		}
		return [];
	}

    /**
     * Set additional options for api parser.
     *
     * @param array $options
     */
    public function setOptions(array $options) : void
    {
        $this->minCid = $options['minCid'];
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
	protected function buildQuery() : string
	{
		$query = '?'
			. 'api_key' . '=' . $this->apiKey
			. ($this->minCid ? ('&' . 'min_cid' . '=' . $this->minCid) : '')
			. '&' . 'limit' . '=' . $this->responseResultsLimit;
		return $query;

	}
}
