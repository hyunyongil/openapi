<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function(){
    return 'connection success';
});

Route::group(['prefix'=>'api/v1', 'namespace'=>'Api\v1'], function() {
    foreach (glob(__DIR__.'/Routes/v1/*.php') as $filename) {
        include_once $filename;
    }
});