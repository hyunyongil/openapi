<?php


Route::get('data_save', 'ItemController@dataSave');

Route::group(['middleware'=>'valid.token'], function(){
    Route::get('item_event_from_oversea', 'ItemController@itemEventFromOversea');
    Route::get('item_list', 'ItemController@itemList');
});