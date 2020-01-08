<?php


Route::post('loginUser', 'UserController@loginUser');

Route::post('createUser', 'UserController@createUser');

Route::get('showApps', 'ApplicationsController@showApps')->middleware('check_token');
