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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
Route::middleware('auth:api')->post('/logout', 'AuthController@logout');

Route::get('/clients', 'ClientsController@index');
Route::get('/clients/{id}', 'ClientsController@show');
Route::post('/clients', 'ClientsController@store');
Route::patch('/clients/{client}', 'ClientsController@update');
Route::delete('/clients/{client}', 'ClientsController@destroy');

Route::get('/engagements', 'EngagementsController@index');
Route::get('/engagements/{id}', 'EngagementsController@clientindex');
Route::post('/engagements', 'EngagementsController@store');
