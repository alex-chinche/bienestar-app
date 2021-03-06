<?php

Route::post('loginUser', 'UserController@loginUser');
Route::post('createUser', 'UserController@createUser');
Route::post('rememberPassword', 'UserController@rememberPassword');

Route::group(['middleware' => ['auth']], function () {
    Route::post('showApps', 'ApplicationsController@showApps');
    Route::get('getApps', 'ApplicationsController@getApps');

    Route::post('postUseTimes', 'UsagesController@postUseTimes');
    Route::get('getUseTimes', 'UsagesController@getUseTimes');
    Route::get('getTotalUsagesPerApp', 'UsagesController@getTotalUsagesPerApp');
    Route::get('getAverageTimePerApp', 'UsagesController@getAverageTimePerApp');
    Route::get('getDailyUsagesPerApp', 'UsagesController@getDailyUsagesPerApp');

    Route::post('postRestrictions', 'RestrictsController@postRestrictions');
    Route::get('getRestrictions', 'RestrictsController@getRestrictions');

    Route::post('postLocations', 'StoresController@postLocations');
    Route::get('getLocations', 'StoresController@getLocations');
});
