<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'phoneRegistrationRecord'], function () {
    Route::post('/', 'PhoneRegistrationRecordController@create');
});

Route::group(['prefix' => 'storeCode'], function () {
    Route::post('/', 'StoreCodeController@create');
});
