<?php

namespace Tupy\AddressesManager\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
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
}
