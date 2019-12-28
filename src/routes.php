<?php

Route::group([
	'namespace' => 'Tupy\AddressesManager\Controllers',
	'prefix' => 'api/addresses'
], function() {
	Route::get('{typeService?}/{query}', 'AddressesController@executeApi');
});
