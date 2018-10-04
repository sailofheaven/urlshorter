<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'namespace' => 'API\V1'], function () {

    Route::group(['prefix' => 'users'], function () {

        Route::group(['prefix' => 'me', 'middleware' => ['auth.basic']], function () {
            Route::get('/', 'UserController@show');

            Route::group(['prefix' => 'shorten_urls'], function () {
                Route::post('/', 'ShortUrlController@store');
                Route::get('/', 'ShortUrlController@index');


                Route::group(['prefix' => '{id}'], function () {
                    Route::get('/', 'ShortUrlController@show');
                    Route::delete('/', 'ShortUrlController@destroy');

                    Route::get('/referers', 'ReportController@referers');
                    Route::get('/{group}', 'ReportController@report')
                        ->where(['group' => 'days|hours|min']);
                });
            });
        });

        Route::post('/', 'UserController@register');
    });

    Route::get('/shorten_urls/{hash}', 'ShortUrlController@redirect');
});