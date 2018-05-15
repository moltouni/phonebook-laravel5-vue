<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ContactController;

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::get('contacts/{contact}/phones', 'PhoneController@index');
Route::post('contacts/{contact}/phones', 'PhoneController@store');
Route::apiResource('contacts', 'ContactController');