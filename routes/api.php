<?php

Route::post('loginUser', 'UserController@loginUser');

Route::post('createUser', 'UserController@createUser');

Route::post('rememberPassword', 'UserController@rememberPassword');

Route::group(['middleware' => ['auth']], function () {
    Route::post('showApps', 'ApplicationsController@showApps');
});



//https://img.utdstc.com/icons/whatsapp-messenger-android.png:s