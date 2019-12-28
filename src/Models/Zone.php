<?php


namespace Tupy\AddressesManager\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $table = 'zones';

    protected $fillable = [
        'name'
    ];
}
