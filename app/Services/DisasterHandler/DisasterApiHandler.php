<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 05.12.2018
 *
 */

namespace App\Services\DisasterHandler;

use App\Services\DisasterHandler\Exceptions\HiszRsoeApiConnectErrorException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Collection;

class DisasterApiHandler
{
	private $baseUrl;
	private $apiKey;
	private $client;
	private $minCid;
	private $resultLimit;
	private $httpResponse;

	public function __construct (int $minCid = null, int $resultLimit = 100)
	{
		$this->baseUrl = 'https://hisz.rsoe.hu/ws/event';
		$this->apiKey = env('HISZ_RSOE_API_KEY');
		$this->minCid = $minCid;
		$this->resultLimit = $resultLimit;
	}

	/**
	 * @return DisasterApiHandler
	 * @throws HiszRsoeApiConnectErrorException
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function request() : self
	{
		try {
			$this->httpResponse = $this->getClient()->request(
				'GET',
				$this->buildQuery()
			);

		} catch (ConnectException $e) {
			throw new HiszRsoeApiConnectErrorException('Hisz Rsoe api connect error');
		}
		return $this;
	}

    /**
     * @return array
     */
	public function getResult() : array
	{
		if ($this->httpResponse) {
			$result = json_decode(
				$this->httpResponse->getBody(),
				true
			);
			return $result['result'] ?? [];
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
	protected function buildQuery() : string
	{
		$query = '?'
			. 'api_key' . '=' . $this->apiKey
			. ($this->minCid ? ('&' . 'min_cid' . '=' . $this->minCid) : '')
			. ($this->resultLimit ? ('&' . 'limit' . '=' . $this->resultLimit) : '');
		return $query;

	}
}