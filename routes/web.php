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
    Route::get('show/{slug}','AdvertController@show')->name('advert.show');
    Route::post('favorite','AdvertController@favorite')->name('advert.favorite');
});

Route::prefix('company')->group(function(){
    Route::get('show/{id}','CompanyController@show')->name('company.show');
    Route::post('{id}/adverts','CompanyController@adverts')->name('company.adverts');
});
