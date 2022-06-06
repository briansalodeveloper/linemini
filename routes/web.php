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

Route::middleware('guest')->group(function () {
    /** AUTH */
    Route::get('/', 'AuthController@index')->name('home');
    Route::post('/login', 'AuthController@login')->name('login');
});

Route::middleware('auth')->group(function () {
    /** AUTH LOGOUT */
    Route::get('/logout', 'AuthController@logout')->name('logout');

    /** DASHBOARD */
    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'middleware' => 'can:manageDashboard'], function () {
        Route::get('/', 'DashboardController@index')->name('index');
    });

    Route::group(['middleware' => 'can:manageContents'], function () {
        /** NOTICE */
        Route::group(['prefix' => 'notice', 'as' => 'notice.'], function () {
            Route::get('/', 'NoticeController@index')->name('index');
            Route::get('/create', 'NoticeController@create')->name('create');
            Route::get('/{id}/edit', 'NoticeController@edit')->name('edit');
            Route::post('/store', 'NoticeController@store')->name('store');
            Route::post('/{id}/update', 'NoticeController@update')->name('update');
            Route::post('/{id}/delete', 'NoticeController@destroy')->name('destroy');
            Route::post('/upload', 'NoticeController@upload')->name('upload');
            Route::post('/uploadTrumbowygImage', 'NoticeController@uploadTrumbowygImage')->name('uploadTrumbowygImage');
        });

        /** RECIPE */
        Route::group(['prefix' => 'recipe', 'as' => 'recipe.'], function () {
            Route::get('/', 'RecipeController@index')->name('index');
            Route::get('/create', 'RecipeController@create')->name('create');
            Route::get('/{id}/edit', 'RecipeController@edit')->name('edit');
            Route::post('/store', 'RecipeController@store')->name('store');
            Route::post('/{id}/update', 'RecipeController@update')->name('update');
            Route::post('/{id}/delete', 'RecipeController@destroy')->name('destroy');
            Route::post('/upload', 'RecipeController@upload')->name('upload');
            Route::post('/uploadTrumbowygImage', 'RecipeController@uploadTrumbowygImage')->name('uploadTrumbowygImage');
        });

        /** PRODUCT INFORMATION */
        Route::group(['prefix' => 'product-information', 'as' => 'productInformation.'], function () {
            Route::get('/', 'ProductInformationController@index')->name('index');
            Route::get('/create', 'ProductInformationController@create')->name('create');
            Route::get('/{id}/edit', 'ProductInformationController@edit')->name('edit');
            Route::post('/store', 'ProductInformationController@store')->name('store');
            Route::post('/{id}/update', 'ProductInformationController@update')->name('update');
            Route::post('/{id}/delete', 'ProductInformationController@destroy')->name('destroy');
            Route::post('/upload', 'ProductInformationController@upload')->name('upload');
            Route::post('/uploadTrumbowygImage', 'ProductInformationController@uploadTrumbowygImage')->name('uploadTrumbowygImage');
        });

        /** COLUMN MANAGEMENT */
        Route::group(['prefix' => 'column', 'as' => 'column.'], function () {
            Route::get('/', 'ColumnController@index')->name('index');
            Route::get('/create', 'ColumnController@create')->name('create');
            Route::get('/{id}/edit', 'ColumnController@edit')->name('edit');
            Route::post('/store', 'ColumnController@store')->name('store');
            Route::post('/{id}/update', 'ColumnController@update')->name('update');
            Route::post('/{id}/delete', 'ColumnController@destroy')->name('destroy');
            Route::post('/upload', 'ColumnController@upload')->name('upload');
            Route::post('/uploadTrumbowygImage', 'ColumnController@uploadTrumbowygImage')->name('uploadTrumbowygImage');
        });
    });

    /** FLYER */
    Route::group(['prefix' => 'flyer', 'as' => 'flyer.', 'middleware' => 'can:manageFlyer'], function () {
        Route::get('/', 'FlyerController@index')->name('index');
        Route::get('/create', 'FlyerController@create')->name('create');
        Route::get('/{id}/edit', 'FlyerController@edit')->name('edit');
        Route::post('/store', 'FlyerController@store')->name('store');
        Route::post('/{id}/update', 'FlyerController@update')->name('update');
        Route::post('/{id}/delete', 'FlyerController@destroy')->name('destroy');
        Route::post('/upload', 'FlyerController@upload')->name('upload');
    });

    /** COUPON MANAGEMENT */
    Route::group(['prefix' => 'coupon', 'as' => 'coupon.', 'middleware' => 'can:manageCoupon'], function () {
        Route::get('/', 'CouponPlanController@index')->name('index');
        Route::get('/create', 'CouponPlanController@create')->name('create');
        Route::get('/{id}/edit', 'CouponPlanController@edit')->name('edit');
        Route::post('/store', 'CouponPlanController@store')->name('store');
        Route::post('/{id}/update', 'CouponPlanController@update')->name('update');
        Route::post('/{id}/destroy', 'CouponPlanController@destroy')->name('destroy');
        Route::post('/upload', 'CouponPlanController@upload')->name('upload');
        Route::post('/uploadTrumbowygImage', 'CouponPlanController@uploadTrumbowygImage')->name('uploadTrumbowygImage');
    });

    /** MESSAGE MANAGEMENT */
    Route::group(['prefix' => 'message', 'as' => 'message.', 'middleware' => 'can:manageMessage'], function () {
        Route::get('/', 'MessageController@index')->name('index');
        Route::get('/create', 'MessageController@create')->name('create');
        Route::get('/{id}/edit', 'MessageController@edit')->name('edit');
        Route::post('/store', 'MessageController@store')->name('store');
        Route::post('/{id}/update', 'MessageController@update')->name('update');
        Route::post('/{id}/delete', 'MessageController@destroy')->name('destroy');
        Route::post('/upload', 'MessageController@upload')->name('upload');
        Route::post('/uploadTrumbowygImage', 'MessageController@uploadTrumbowygImage')->name('uploadTrumbowygImage');
    });
    /** STAMP MANAGEMENT */
    Route::prefix('stamp/')->name('stamp.')->group(function () {
        Route::get('/', 'StampController@index')->name('index');
        Route::get('/create', 'StampController@create')->name('create');
        Route::get('/{stamp}/edit', 'StampController@edit')->name('edit');
        Route::get('/{stamp}/duplicate', 'StampController@storeDuplicate')->name('duplicate');
        Route::post('/store', 'StampController@store')->name('store');
        Route::get('/{id}/delete', 'StampController@destroy')->name('destroy');
        Route::post('/{id}/update', 'StampController@update')->name('update');
        Route::post('/storeCsv', 'StampController@storeCsv')->name('storeCsv');
        // Route::post('/storeCsvProductRedumption', 'StampController@storeCsvProductRedumption')->name('storeCsvProductRedumption');
        Route::post('/uploadTrumbowygImage', 'StampController@uploadTrumbowygImage')->name('uploadTrumbowygImage');
        Route::post('/upload', 'CouponPlanController@upload')->name('upload');
    });

    /** USER MANAGEMENT */
    Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => 'can:manageUser'], function () {
        Route::get('/', 'UserController@index')->name('index');
        Route::post('/updateIncident', 'UserController@updateIncident')->name('updateIncident');
    });

    /** BATCH LOG MANAGEMENT */
    Route::group(['prefix' => 'batch-log','as' => 'batchLog.', 'middleware' => 'can:manageBatchLog'], function () {
        Route::get('/', 'BatchLogController@index')->name('index');
        Route::post('/show', 'BatchLogController@show')->name('show');
    });

    /** ADMIN USER MANAGEMENT */
    Route::group(['prefix' => 'administrator','as' => 'admin.', 'middleware' => 'can:manageAdmin'], function () {
        Route::get('/', 'AdminController@index')->name('index');
        Route::get('/create', 'AdminController@create')->name('create');
        Route::get('/{id}/edit', 'AdminController@edit')->name('edit');
        Route::post('/store', 'AdminController@store')->name('store');
        Route::post('/{id}/update', 'AdminController@update')->name('update');
        Route::post('/{id}/delete', 'AdminController@destroy')->name('destroy');
    });
});
