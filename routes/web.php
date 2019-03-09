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

Route::get('login', 'Auth\LoginController@index')->name('login');
Route::domain('traxit.pro')->group(function () { 
    Route::prefix('web')->group(function () {
        Route::post('/login', 'Auth\LoginController@login');
        Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
        
        Route::post('/register', 'System\CompaniesController@register');
        Route::get('/companies', 'System\CompaniesController@index')->name('companies');
        Route::get('/company/{uuid}', 'System\CompaniesController@show')->name('company');
        Route::get('/companyAccount/{uuid}', 'System\CompaniesController@showAccount');
        Route::get('/companyToUpdate/{uuid}', 'System\CompaniesController@showCompanyToUpdate');
        Route::patch('/company/{uuid}', 'System\CompaniesController@update');
        Route::delete('/company/{uuid}', 'System\CompaniesController@destroy');
        Route::patch('/updateCompanyAccount/{uuid}', 'System\CompaniesController@updateCompanyAccount');
        
        Route::get('/subscriptions', 'System\SubscriptionsController@index');
        Route::get('/subscriptions/{id}', 'System\SubscriptionsController@show');
        Route::post('/upgrade-subscription', 'System\SubscriptionsController@upgrade');
        Route::post('/resume-subscription/{id}', 'System\SubscriptionsController@resumeByAdmin');
        Route::post('/subscriptions', 'System\SubscriptionsController@subscribe');
        Route::patch('/subscriptions/{id}', 'System\SubscriptionsController@update');
        Route::delete('/subscriptions/{subscription}', 'System\SubscriptionsController@destroy');
        Route::delete('/cancel-subscription/{id}', 'System\SubscriptionsController@cancelByAdmin');
        Route::get('/plans', 'System\SubscriptionsController@subscriptionPlans');
    });
    Route::get('/{any}', 'System\SystemController@index')->where('any', '.*');
});
