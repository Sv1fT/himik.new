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

Route::group(['prefix' => '/v1',
    'namespace' => 'Api\V1',
    'as' => 'api.'], function () {
    Route::get('/user/profile/{id}','UserController@profile')->name('api.user.profile');
    Route::get('/user/adverts/{id}','UserController@adverts')->name('api.user.adverts');
    Route::any('/user/advert/edit/{id}','UserController@advert_edit')->name('api.user.advert_edit');
    Route::post('/user/profile/save/{id}','UserController@store')->name('api.user.profile.save');

    Route::get('/adverts','AdvertController@index')->name('api.adverts');
    Route::get('/categories','CategoryController@index')->name('api.categories');
    Route::get('/categories/{category:id}','CategoryController@show')->name('api.categories.subcategory');

    Route::post('/city/{name}','CityController@show')->name('api.city.show');
});
