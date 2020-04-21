<?php

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | This file is where you may define all of the routes that are handled
    | by your application. Just tell Laravel the URIs it should respond
    | to using a Closure or controller method. Build something great!
    |
    */      #Главная страница

use Illuminate\Support\Facades\Artisan;
if (App::environment('production', 'staging')) {
    //URL::forceScheme('https');
}
Auth::routes();
    //Главная
Route::get('/', 'HomeController@index')->name('home');
Route::resource('blog', 'BlogController');
Route::get('company','CompanyController@index')->name('company.index');



Route::prefix('user')->middleware(['auth'])->group(function (){
   Route::get('{index}','UserController@index')->name('user.index');
   Route::get('{index}/{crud}','UserController@index')->name('user.index');
   Route::post('profile','UserController@profile')->name('user.profile');
   Route::post('advert','UserController@advert')->name('user.advert');
   Route::post('summary','UserController@summary')->name('user.summary');
   Route::post('vacant','UserController@vacant')->name('user.vacant');
   Route::post('blog','UserController@blog')->name('user.blog');
   Route::post('advertiments','UserController@advertiments')->name('user.advertiments');
   Route::post('favorites','UserController@favorites')->name('user.favorites');
   Route::post('messages','UserController@messages')->name('user.messages');
});

Route::prefix('advert')->group(function(){
    Route::get('/','AdvertController@index')->name('advert.index');
    Route::get('show/{advert:slug}','AdvertController@show')->name('advert.show');
    Route::post('favorite','AdvertController@favorite')->name('advert.favorite');
});

Route::prefix('company')->group(function(){
    Route::get('show/{id}','CompanyController@show')->name('company.show');
    Route::post('{id}/adverts','CompanyController@adverts')->name('company.adverts');
});

Route::resource('category','TsbController');


Route::get('vk-auth','UserController@vk_auth');
Route::get('vk-post','UserController@vk_post');
