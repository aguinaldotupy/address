<?php

namespace Tupy\AddressesManager;

use Illuminate\Support\Facades\Facade;

class AddressesManagerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'addressesmanager';
    }
}
