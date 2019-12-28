<?php

namespace Tupy\AddressesManager\Models;

use Illuminate\Database\Eloquent\Model;
use Tupy\AddressesManager\Traits\HasZone;

/**
 * Class Address
 * @package Tupy\AddressesManager\Models
 * @mixin \Eloquent
 * @property int $id
 * @property string $addressable_type
 * @property int $addressable_id
 * @property string|null $address_id
 * @property string|null $tag
 * @property string|null $address
 * @property int|null $id_postal_code
 * @property string $zip_code
 * @property string|null $port
 * @property string|null $complement
 * @property int|null $parish_id
 * @property string|null $parish
 * @property string|null $district_id
 * @property string|null $district
 * @property string|null $county_id
 * @property string|null $county
 * @property string|null $locality_id
 * @property string|null $locality
 * @property string|null $country_id
 * @property string|null $country
 * @property string|null $observation
 * @property string|null $latitude
 * @property string|null $longitude
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read Model|\Eloquent $addressable
 * @property-read string|null $coordinate
 * @property-read string $mapPopupContent
 */
class Address extends Model
{
    use HasZone;

	protected $table = 'addresses';

	protected $fillable = [
        'addressable_type', 'addressable_id', 'type', 'id_postal_code', 'address', 'address_id', 'zip_code', 'port', 'complement', 'parish_id', 'parish', 'district_id', 'district', 'county_id', 'locality_id', 'locality', 'county', 'country_id', 'country', 'tag', 'observation', 'latitude', 'longitude'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = [
        'coordinate', 'map_popup_content', 'url_google_maps'
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

        if($this->addressable->name){
            $name = $this->addressable->name;
        } else {
            $name = '';
        }

        return "{$url}{$name}+{$addressString},+{$this->zip_code}+{$countyString}/";
    }
}
