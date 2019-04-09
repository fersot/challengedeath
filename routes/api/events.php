<?php

Route::group(['prefix' => 'events'], function () {
    Route::get('/', 'Api\EventController@all');
    Route::get('/getByDay/{day}', 'Api\EventController@getByDay');
    Route::get('/{id}', 'Api\EventController@read');
    Route::post('/', 'Api\EventController@create');
    Route::put('/{id}', 'Api\EventController@update');
    Route::delete('/{id}', 'Api\EventController@delete');
});