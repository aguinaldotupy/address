<?php


namespace Tupy\AddressesManager\Traits;

use Tupy\AddressesManager\Models\Zone;

/**
 * Trait HasZone
 * @package Tupy\AddressesManager\Traits
 */
trait HasZone
{
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
