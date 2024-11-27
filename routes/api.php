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

Route::prefix('v1')->group(function () { 
    // public
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('reset/password', 'AuthController@callResetPassword');

    Route::get('commonImage/slider', 'CommonImageController@slider');


    // auth
    Route::middleware(['auth:api'])->group(function () {
       
        Route::prefix('categories')->group(function () {
            Route::get('slug/{slug}', 'CategoryController@slug');
        });

        Route::post('logout', 'AuthController@logout');

        Route::post('profile', 'CustomerController@profile');
        
        Route::get('productsMakanan', 'ProductController@indexMakanan');
        Route::get('productsMinuman', 'ProductController@indexMinuman');
        Route::get('productsSnack', 'ProductController@indexSnack');
        Route::prefix('products')->group(function () {
            Route::get('searchMakanan/{keyword}', 'ProductController@searchMakanan');
            Route::get('searchMinuman/{keyword}', 'ProductController@searchMinuman');
            Route::get('searchSnack/{keyword}', 'ProductController@searchSnack');
            Route::post('cart', 'ProductController@cart');
            Route::post('store', 'ProductController@store');
            Route::post('update', 'ProductController@update');
            Route::post('delete-permanent', 'ProductController@deletePermanent');
            
        });

        Route::get('orders', 'ShopController@index');
        Route::get('orders-history', 'ShopController@indexHistory');
        Route::get('my-order', 'ShopController@myOrder');
        Route::prefix('orders')->group(function () {
            Route::get('search/{keyword}', 'ShopController@search');
            Route::post('delete-permanent', 'ShopController@deletePermanent');
            Route::post('report-order', 'ShopController@reportOrder');
            Route::post('approved', 'ShopController@approved');
            Route::get('download/{id}', 'ShopController@download');
            Route::get('search-history/{keyword}', 'ShopController@searchHistory');
            Route::get('search-history-my-order/{keyword}', 'ShopController@searchHistoryMyOrder'); 
        });
        Route::post('payment-pos', 'ShopController@payment');

        Route::get('store-setting', 'SettingController@index');
        Route::prefix('store-setting')->group(function () {
            Route::post('store', 'SettingController@store');
        });
        
        Route::get('informations', 'AdminController@index');
        Route::prefix('informations')->group(function () {
            Route::post('store', 'AdminController@store');
            Route::post('update', 'AdminController@update');
            Route::post('delete-permanent', 'AdminController@deletePermanent');
        });

        Route::get('chairs', 'ChairController@index');
        Route::prefix('chairs')->group(function () {
            Route::get('search/{keyword}', 'ChairController@search');
            Route::post('store', 'ChairController@store');
            Route::post('update', 'ChairController@update');
            Route::post('delete-permanent', 'ChairController@deletePermanent');
        });
        Route::get('chairs-customer', 'ChairController@indexCustomer');
        
    }); 

   
});

