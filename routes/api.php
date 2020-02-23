<?php

Route::post('loginUser', 'UserController@loginUser');
Route::post('createUser', 'UserController@createUser');
Route::post('rememberPassword', 'UserController@rememberPassword');

Route::group(['middleware' => ['auth']], function () {
    Route::post('showApps', 'ApplicationsController@showApps');
    Route::post('postUseTimes', 'UsagesController@postUseTimes');
    Route::get('getApps', 'ApplicationsController@getApps');
    Route::get('getUseTimes', 'UsagesController@getUseTimes');
    Route::get('getTotalUsagesPerApp', 'UsagesController@getTotalUsagesPerApp');
    
});
