<?php

namespace Tupy\AddressesManager;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class AddressesManager
{
	protected $guzzle;
	protected $baseUrl;
	protected $timeToSleep = 0.4;

	public function __construct()
	{
		$handler = new CurlHandler();
		$stack = HandlerStack::create($handler);

		$stack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));

		$this->guzzle = new Guzzle(['handler' => $stack]);

		$this->baseUrl = config('addresses-manager.url_api');
	}

	protected function retryDecider()
	{
		return function ($retries, Request $request, Response $response = null, RequestException $exception = null) {
            // Limit the number of retries to 5
			if ($retries >= 5) {
				return false;
			}

            // Retry connection exceptions
			if ($exception instanceof ConnectException) {
				return true;
			}

			if ($response) {
                // Retry on server errors
				if ($response->getStatusCode() != 200 and $response->getStatusCode() != 404) {
					return true;
				}
			}

			return false;
		};
	}

	protected function retryDelay()
	{
		return function ($numberOfRetries) {
			return 1000 * $numberOfRetries;
		};
	}

	/**
	* @param string Type Service
	* @return array
 	*/
	protected function typeRequest($value)
	{
		switch ($value) {
            case 'districts':
				return [
					'uri' => 'place-by-type',
					'query' => 'type'
				];
			break;

			case 'counties':
				return [
					'uri' => 'portugal/county-by-district',
					'query' => 'id'
				];

			break;

			case 'parishes':
				return [
					'uri' => 'parish-by-county-and-district',
					'query' => 'id'
				];

			break;

			case 'localities':
				return [
					'uri' => 'locality-by-district-county',
					'query' => 'id'
				];
			break;

			default:
				return [
					'uri' => 'get-address',
					'query' => 'code'
				];
			break;
		}
	}

	public function getAddress(string $query, $typeService = 'zipCode')
	{
		$typeService = self::typeRequest($typeService);

		try {
			$response = $this->guzzle->request('GET', "{$this->baseUrl}/{$typeService['uri']}", [
				'query' => [
					$typeService['query'] => $query,
                    // 'page' => $page
				],
				'headers' => [
					'Accept' => 'application/json'
					// 'Authorization' => 'Bearer '.$this->token,
				]
			]);

			return $response->getBody()->getContents();

            // sleep($this->timeToSleep);
		} catch (GuzzleException $e) {
		    return $e->getMessage();
        }
    }
}
