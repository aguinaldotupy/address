<?php

namespace Tupy\AddressesManager\Tests;

use Tupy\AddressesManager\AddressesManager;

class IbgeTest extends \PHPUnit\Framework\TestCase
{
    public function testGetStates()
    {
        $data = AddressesManager::make()->getStates();

        $this->assertNotNull($data);
    }

    public function testGetCities()
    {
        $cities = AddressesManager::make()->getCities();

        $this->assertNotNull($cities);
    }

    public function testGetCityByState()
    {
        $addressManager = new AddressesManager();
        $state = $addressManager->getStates();

        $this->assertIsArray($state);

        $city = $addressManager->getCitiesByState($state[0]['id']);

        $this->assertIsArray($city);
    }

    public function testGetNeighborhoods()
    {
        $neighborhoods = AddressesManager::make()->getNeighborhoods();

        $this->assertNotNull($neighborhoods);
        $this->assertIsArray($neighborhoods);
    }
}
