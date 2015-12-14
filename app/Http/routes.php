<?php

Route::get('/', 'HomeController@index');
Route::get('/about', 'HomeController@about');

Route::post('/submit', 'PublisherController@submit');
Route::post('/preview', 'ImageController@preview');
Route::get('/i/{key?}', 'ImageController@image');

Route::group(['prefix' => '/admin', 'middleware' => 'auth.very_basic'], function () {

    Route::get('/', 'Admin\AdminController@index');
    Route::get('/queue', 'Admin\AdminController@queue');
    Route::get('/block-guest', 'Admin\AdminController@blockGuest');
    Route::get('/block-keyword', 'Admin\AdminController@blockKeyword');
    Route::get('/setting', 'Admin\AdminController@setting');
    Route::post('/setting', 'Admin\AdminController@settingUpdate');

    Route::group(['prefix' => '/action'], function () {
        Route::post('/unpublish/{id?}', 'Admin\ActionController@unpublish');
        Route::post('/republish/{id?}', 'Admin\ActionController@republish');
        Route::post('/deny/{id?}', 'Admin\ActionController@deny');
        Route::post('/allow/{id?}', 'Admin\ActionController@allow');
        Route::post('/block/{id?}', 'Admin\ActionController@block');
        Route::post('/delete/{table?}/{id?}', 'Admin\ActionController@delete');
        Route::post('/add-keyword', 'Admin\ActionController@addKeyword');
        Route::post('/update-setting', 'Admin\ActionController@updateSetting');
    });

    Route::group(['prefix' => '/ajax'], function () {
        Route::post('/config.json', 'Admin\AjaxController@config');
        Route::post('/queue.json', 'Admin\AjaxController@queue');
        Route::post('/block-guest.json', 'Admin\AjaxController@blockGuest');
        Route::post('/block-keyword.json', 'Admin\AjaxController@blockKeyword');
        Route::post('/setting.json', 'Admin\AjaxController@setting');
    });

});

Route::get('/{key?}', 'PublisherController@check');
