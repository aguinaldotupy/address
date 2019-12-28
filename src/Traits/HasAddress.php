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
     * Retrive address
     * @param string $tag
     * @return mixed
     */
    public function getAddress($tag = 'morada')
    {
        return optional($this->addresses()->where('tag', $tag)->first());
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    /**
     * Create address
     * @param array $data
     */
    public function createAddress(array $data)
    {
        $this->addresses()->create($data);
    }

    /**
     * Update address
     * @param array $data
     * @param string $tag *Default morada
     */
    public function updateAddress(array $data, $tag = 'morada')
    {
        //dd($data, $this->getAddress());
        $this->addresses()->updateOrCreate(['tag' => $tag], $data);
    }
}
