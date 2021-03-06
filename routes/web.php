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

Route::get('/', 'StaticController@home')->name('home');
Route::get('/about', 'StaticController@about')->name('about');
Route::get('/help', 'StaticController@help')->name('help');
Route::get('signup' ,'UsersController@create')->name('signup');
Route::resource('users', 'UsersController');
Route::get('login', 'SessionController@create')->name('login');
Route::post('login', 'SessionController@store')->name('login');
Route::delete('logout', 'SessionController@destroy')->name('logout');
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');
Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset','Auth\ResetPasswordController@reset')->name('password.update');
Route::resource('statuses', 'StatusesController', ['only' => ['store', 'destroy']]);
Route::get('users/{user}/followers','UsersController@followers')->name('users.followers');
Route::get('users/{user}/followings','UsersController@followings')->name('users.followings');
Route::post('users/followers/{user}','FollowersController@store')->name('followers.store');
Route::delete('users/followers/{user}','FollowersController@destroy')->name('followers.destroy');