<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::resource('blog', 'BlogController');
Route::resource('tsb', 'TsbController');
Route::get('company','CompanyController@index')->name('company.index');

Route::prefix('admin')->group(function () {
    Route::get('users', function () {
        return view();
    });
});

Route::prefix('user')->middleware(['auth'])->group(function (){
   Route::get('profile','UserController@profile')->name('user.profile');
   Route::get('advert','UserController@advert')->name('user.advert');
   Route::get('summary','UserController@summary')->name('user.summary');
   Route::get('vacant','UserController@vacant')->name('user.vacant');
   Route::get('blog','UserController@blog')->name('user.blog');
   Route::get('advertiments','UserController@advertiments')->name('user.advertiments');
   Route::get('favorites','UserController@favorites')->name('user.favorites');
   Route::get('messages','UserController@messages')->name('user.messages');
});

Route::prefix('advert')->group(function(){
    Route::get('show/{slug}','AdvertController@show')->name('advert.show');
    Route::post('favorite','AdvertController@favorite')->name('advert.favorite');
});

Route::prefix('company')->group(function(){
    Route::get('show/{id}','CompanyController@show')->name('company.show');
    Route::post('{id}/adverts','CompanyController@adverts')->name('company.adverts');
});
