<?php

use Illuminate\Support\Facades\Route;

Route::group([
	'namespace' => 'Tupy\AddressesManager\Controllers',
	'prefix' => 'api/addresses',
    'middleware' => config('addresses-manager.middleware')
], function() {
    Route::get('/states', 'AddressesController@states')->name('api.addresses.states');
    Route::get('cities/{stateId?}', 'AddressesController@cities')->name('api.addresses.cities');
    Route::get('neighborhoods/{cityId?}', 'AddressesController@neighborhoods')->name('api.addresses.neighborhoods');
    Route::get('/{cep}', 'AddressesController@searchCep')->name('api.addresses.searchCep');
    Route::get('{typeService?}/{query}', 'AddressesController@executeApi');
});
