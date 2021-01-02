<?php

Route::namespace('Api')->group(function () {

    Route::namespace('Auth')->group(function () {

        Route::post('/login', 'AuthController@login')->name('auth.login');
        Route::post('/signup', 'AuthController@signup')->name('auth.sign_up');

    });

     Route::group(['middleware' => ['auth:sanctum']], function () {

        Route::get('/', 'Tweets\TweetsController@index')->name('timeline');

        Route::group(['prefix' => 'tweets', 'namespace' => 'Tweets'], function () {
            Route::post('/', 'TweetsController@store')->name('tweet.create');
        });

        Route::post('/follow', 'Followers\FollowersController@follow')->name('follow');

    });

});
