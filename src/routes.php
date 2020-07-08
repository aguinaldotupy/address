<?php

use Illuminate\Support\Facades\Route;

Route::group([
	'namespace' => 'Tupy\AddressesManager\Controllers',
	'prefix' => 'api/addresses'
], function() {
	Route::get('{typeService?}/{query}', 'AddressesController@executeApi');
	Route::get('/{cep}', 'AddressesController@searchCep')->name('api.addresses.searchCep');
	Route::get('states', 'AddressesController@states')->name('api.addresses.states');
	Route::get('cities/{stateId?}', 'AddressesController@cities')->name('api.addresses.cities');
	Route::get('neighborhoods/{cityId?}', 'AddressesController@neighborhoods')->name('api.addresses.neighborhoods');
});
