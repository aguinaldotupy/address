<?php

namespace Tupy\AddressesManager\Models;

use Tupy\AddressesManager\Traits\HasZone;

/**
 * Class Address
 * @package Tupy\AddressesManager\Models
 * @mixin \Eloquent
 * @property int $id
 * @property string $addressable_type
 * @property int $addressable_id
 * @property string|null $tag
 * @property string|null $address_street_1
 * @property string|null $address_street_2
 * @property string $zip_code
 * @property string|null $number
 * @property string|null $complement
 * @property string|null $country
 * @property string|null $city
 * @property string|null $state
 * @property string|null $state_prefix
 * @property string|null $neighborhood
 * @property string|null $observation
 * @property string|null $latitude
 * @property string|null $longitude
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read Model|\Eloquent $addressable
 * @property-read string|null $coordinate
 * @property-read string $mapPopupContent
 * @property-read string $street
 */
class Address extends \Illuminate\Database\Eloquent\Model
{
    use HasZone;

	protected $table = 'addresses';

	protected $fillable = [
        'zone_id', 'addressable_type', 'addressable_id', 'tag', 'address_street_1', 'address_street_2', 'zip_code', 'number', 'complement', 'city', 'state', 'neighborhood', 'country', 'observation', 'latitude', 'longitude',
        'people_contact', 'phone'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = [
        'coordinate', 'street',
    ];

    public function addressable()
    {
        return $this->morphTo();
    }

    /**
     * Get outlet coordinate attribute.
     *
     * @return string|null
     */
    public function getCoordinateAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return $this->latitude.', '.$this->longitude;
        } else {
            return '';
        }

    }

    /**
     * Get outlet map_popup_content attribute.
     *
     * @return string
     */
    public function getMapPopupContentAttribute()
    {
        $mapPopupContent = '';
        $mapPopupContent .= '<div class="my-2"><strong>Name:</strong><br>'.$this->addressable->name ?? null.'</div>';
        $mapPopupContent .= '<div class="my-2"><strong>Coordinate:</strong><br>'.$this->coordinate.'</div>';

        return $mapPopupContent;
    }

    public function getUrlGoogleMapsAttribute()
    {
        $url = "https://www.google.pt/maps/dir//";

        $addressString = str_replace(' ', '+', $this->address);
        $countyString = str_replace(' ', '+', $this->county);

        if(isset($this->addressable->name) && $this->addressable->name){
            $name = $this->addressable->name;
        } else {
            $name = '';
        }

        return "{$url}{$name}+{$addressString},+{$this->zip_code}+{$countyString}/";
    }

    public function getStreetAttribute()
    {
        if ($this->address_street_1 && $this->address_street_2) {
            return "{$this->address_street_1}, {$this->address_street_2}";
        }

        if ($this->address_street_1) {
            return $this->address_street_1;
        }

        if ($this->address_street_2) {
            return $this->address_street_2;
        }

        return null;
    }
}
