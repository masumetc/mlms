<?php

use Illuminate\Support\Facades\Route;




//for laravel 8 we ar using laravel namespace
Route::group(['as'=>'user.','prefix'=>'user', 'namespace' => 'App\Http\Controllers\user',
'middleware'=>['user']], function()
{
    Route::get('dashboard', 'DashboardController@dashboard');
});
