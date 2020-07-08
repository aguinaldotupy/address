<?php

namespace Tupy\AddressesManager\Traits;

use Tupy\AddressesManager\Models\Address;

/**
 * Trait HasAddress
 * @package Tupy\AddressesManager\Traits
 */
trait HasAddress
{

	/**
	 * Create Relation
	 */
	public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Retrieve address
     * @param string $tag
     * @return mixed
     */
    public function getAddress($tag = 'Home')
    {
        return $this->addresses()->firstWhere('tag', $tag);
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    /**
     * Create address
     * @param array $data
     * @return \Tupy\AddressesManager\Models\Address
     */
    public function createAddress(array $data)
    {
        return $this->addresses()->create($data);
    }

    /**
     * Update address
     * @param array $data
     * @param string $tag *Default morada
     * @return \Tupy\AddressesManager\Models\Address
     */
    public function updateAddress(array $data, $tag = 'Home')
    {
        $this->addresses()->updateOrCreate(['tag' => $tag], $data);

        return $this->address;
    }
}
