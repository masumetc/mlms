<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});



Route::group(['namespace' => 'App\Http\Controllers', 'middleware'=>['login_check']], function()
{
    Route::get('login', 'LoginController@login');
    Route::get('u/{username}', 'LoginController@register');
    Route::get('login_store', 'LoginController@login_store');
    Route::get('register_store', 'LoginController@register_store');
    Route::get('code', 'LoginController@code');
    Route::get('code_verify', 'LoginController@code_verify');
    Route::get('password', 'LoginController@password');
    Route::get('recovery_mail', 'LoginController@recovery_mail');
    Route::get('password/change', 'LoginController@passwordChnage');
    Route::get('password_store', 'LoginController@password_store');


    Route::get('check', 'CheckController@check');
});


Route::group(['namespace' => 'App\Http\Controllers'], function()
{
    Route::get('check', 'CheckController@check');

    Route::get('logout', 'LoginController@logout');
});
