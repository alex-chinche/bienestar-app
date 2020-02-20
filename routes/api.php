<?php

Route::post('loginUser', 'UserController@loginUser');

Route::post('createUser', 'UserController@createUser');

Route::post('rememberPassword', 'UserController@rememberPassword');

Route::group(['middleware' => ['auth']], function () {
    Route::post('showApps', 'ApplicationsController@showApps');
    Route::post('postUseTimes', 'UsagesController@postUseTimes');
    Route::get('getApps', 'ApplicationsController@getApps');
    Route::post('getUseTimes', 'UsagesController@getUseTimes');
});
