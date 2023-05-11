<?php

use Illuminate\Support\Facades\Route;
use Modules\User\src\Http\Controllers\UserController;

Route::middleware('demo')->get('/user',function(){
    return config('demo.test');
});

//Modules User
Route::group(['namespace' => 'Modules\User\src\Http\Controllers'], function(){

    Route::prefix('users')->group(function(){
        Route::get('/','UserController@index');

        Route::get('/detail/{id}','UserController@detail');

        Route::get('/create','UserController@create');

        
       
    });

});

Route::group(['namespace' => 'Modules\User\src\Http\Controllers'], function(){

    Route::prefix('users')->group(function(){
        Route::get('/thanhtoan','UserController@thanhtoan');
        Route::get('/vnpay_php/vnpay_pay.php','VnpayController@vnpay_pay');
        Route::post('/vnpay_create_payment','VnpayController@vnpay_create_payment');
        
        Route::get('/return','VnpayController@return');
    });

});

// Route::get('/users',[UserController::class, 'index']);