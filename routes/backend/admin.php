<?php

use Illuminate\Support\Facades\Route;




//for laravel 8 we ar using laravel namespace
Route::group(['as'=>'admin.','prefix'=>'admin', 'namespace' => 'App\Http\Controllers\admin',
'middleware'=>['admin']], function()
{
    Route::get('dashboard', 'DashboardController@dashboard');
});
