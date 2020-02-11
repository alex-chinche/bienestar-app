<?php

Route::post('loginUser', 'UserController@loginUser');

Route::post('createUser', 'UserController@createUser');

Route::post('rememberPassword', 'UserController@rememberPassword');

Route::post('showApps', 'ApplicationsController@showApps');

Route::group(['middleware' => ['auth']], function () {
    
});
