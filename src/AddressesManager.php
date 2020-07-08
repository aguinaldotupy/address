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

	protected $urlIbge = 'https://servicodados.ibge.gov.br/api/v1/localidades';

	public function __construct()
	{
		$handler = new CurlHandler();
		$stack = HandlerStack::create($handler);

		$stack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));

		$this->guzzle = new Guzzle(['handler' => $stack]);
	}

	public static function make()
    {
        return new AddressesManager();
    }

    public function setBaseUrl($value = null)
    {
        if (is_null($value)) {
            $value = config('addresses-manager.url_api');
        }

        $this->baseUrl = $value;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
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

    public function getStates()
    {
        try {
            $response = $this->guzzle->request('GET', sprintf('%s/estados', $this->urlIbge), [
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            var_dump($e->getMessage()); die;
        }
    }

    public function getCities($state_id = null)
    {
        if (is_null($state_id)) {
            $url = sprintf('%s/municipios', $this->urlIbge);
        } else {
            $url = sprintf('%s/estados/%s/municipios', $this->urlIbge, $state_id);
        }

        try {
            $response = $this->guzzle->request('GET', $url, [
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            var_dump($e->getMessage()); die;
        }
    }

    public function getCitiesByState($state_id)
    {
        return $this->getCities($state_id);
    }

    public function getNeighborhoods($city_id = null)
    {
        if (is_null($city_id)) {
            $url = sprintf('%s/subdistritos', $this->urlIbge);
        } else {
            $url = sprintf('%s/municipios/%s/subdistritos', $this->urlIbge, $city_id);
        }

        try {
            $response = $this->guzzle->request('GET', $url, [
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            var_dump($e->getMessage()); die;
        }
    }

    public function getNeighborhoodsByCity($city_id)
    {
        return $this->getNeighborhoods($city_id);
    }

    public function searchCep($cep)
    {
        try {
            $response = $this->guzzle->request('GET', sprintf('https://viacep.com.br/ws/%s/json/', $cep), [
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            var_dump($e->getMessage()); die;
        }
    }
}
