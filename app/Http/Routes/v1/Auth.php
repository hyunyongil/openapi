<?php

Route::post('auth', 'AuthController@auth');
Route::post('refresh_token', 'AuthController@refreshToken');