<?php

namespace Tupy\AddressesManager\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Tupy\AddressesManager\AddressesManager;

class AddressesController extends BaseController
{
    /**
     * @param Request $request
     * @param string $typeService
     * @param $query
     * @return string
     * @options
     *  'zipCode' = defaul
     *  'districts'
     *  'counties'
     *  'parishes'
     *  'localities'
     */
	public function executeApi(Request $request, $typeService, $query)
	{
		$api = new AddressesManager();
        return $api->getAddress($typeService, $query);
	}

	public function searchCep($cep)
    {
        try {
            return new JsonResource(AddressesManager::make()->searchCep($cep));
        } catch (\Exception $e) {
            return [];
        }
    }

	public function states()
    {
        try {
            return new JsonResource(Cache::remember('states_api_address', 3600, function() {
                return AddressesManager::make()->getStates();
            }));
        } catch (\Exception $e) {
            return [];
        }
    }

    public function cities($stateId = null)
    {
        try {
            return new JsonResource(Cache::remember('cities_api_address_' . $stateId, 360, function() use ($stateId) {
                return AddressesManager::make()->getCities($stateId);
            }));
        } catch (\Exception $e) {
            return [];
        }
    }

    public function neighborhoods($cityId = null)
    {
        try {
            return new JsonResource(Cache::remember('neighborhoods_api_address_' . $cityId, 3600, function() use ($cityId) {
                return AddressesManager::make()->getCities($cityId);
            }));
        } catch (\Exception $e) {
            return [];
        }
    }
}
