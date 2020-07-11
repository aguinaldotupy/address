<?php

use Illuminate\Support\Facades\Route;

Route::group([
	'namespace' => 'Tupy\AddressesManager\Controllers',
	'prefix' => 'api/addresses',
    'middleware' => config('addresses-manager.middleware')
], function() {
    Route::get('/states', 'AddressesController@states')->name('api.addresses.states');
    Route::get('/find-address/{address}', 'AddressesController@show')->name('api.addresses.show');
    Route::get('/cities/{stateId?}', 'AddressesController@cities')->name('api.addresses.cities');
    Route::get('/neighborhoods/{cityId?}', 'AddressesController@neighborhoods')->name('api.addresses.neighborhoods');
    Route::get('/{cep}', 'AddressesController@searchCep')->name('api.addresses.searchCep');
    Route::get('/{typeService?}/{query}', 'AddressesController@executeApi');

    Route::post('/', 'AddressesController@store')->name('api.addresses.store');
    Route::put('/{address}', 'AddressesController@update')->name('api.addresses.update');
    Route::delete('/{address}', 'AddressesController@destroy')->name('api.addresses.destroy');
});
