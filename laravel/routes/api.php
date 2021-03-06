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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'map',
    'middleware' => ['cors'],
], function () {

    Route::get('/', function () {
        return 'hello';
    });

    Route::group(['prefix' => 'district'], function () {
        Route::get('/cities', 'MapController@cities');
    });

    Route::group(['prefix' => 'place'], function () {
        Route::get('/', 'MapController@places');
    });
});
