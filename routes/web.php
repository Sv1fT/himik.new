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

Route::prefix('user')->group(function (){
   Route::get('profile','UserController@profile')->name('user.profile');
   Route::get('adverts','UserController@adverts')->name('user.adverts');
   Route::get('jobs','UserController@jobs')->name('user.jobs');
   Route::get('vacancy','UserController@vacancy')->name('user.vacancy');
});
