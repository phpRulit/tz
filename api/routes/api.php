<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
    'middleware' => 'api'
], function ($router) {
    Route::group([
        'prefix' => 'auth',
        'namespace' => 'Auth'
    ], function () {
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');
        Route::post('refresh', 'AuthController@refresh');
        Route::get('verify/{hash}', 'AuthController@verifyRegistry');
        Route::get('resend/{email}', 'AuthController@resendMailVerificationRegistry');
        Route::post('reset-password', 'ResetController@sendPasswordResetLink');
        Route::post('reset/password', 'ResetController@callResetPassword');
    });
    Route::group([
    ], function () {
        Route::get('search', 'OpenController@search');
        Route::get('get-prompts', 'OpenController@getPrompts');
    });
    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::group([
            'namespace' => 'Auth',
        ], function () {
            Route::get('get-user', 'AuthController@getUserAuth');
            Route::post('logout', 'AuthController@logout');
        });
        Route::group([
        ], function () {
            Route::get('get-my-books', 'UserController@index');
            Route::post('add-new', 'UserController@store');
            Route::post('edit/{book}', 'UserController@update');
            Route::post('save-img/{book}', 'UserController@updatePhoto');
            Route::get('get-voices', 'UserController@getVoices');
            Route::post('to-vote/{book}', 'UserController@toVote');
            Route::delete('destroy/{book_id}', 'UserController@destroy');
        });
    });
});

