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
Route::get('/engagementquestions/{id}', 'EngagementsController@questionindex');
Route::get('/clientengagement/{id}', 'EngagementsController@show');
Route::post('/engagements', 'EngagementsController@store');
Route::patch('/engagements/{engagement}', 'EngagementsController@update');
Route::delete('/engagements/{engagement}', 'EngagementsController@destroy');

Route::get('/questions/{id}', 'QuestionsController@show');
Route::post('/questions', 'QuestionsController@store');
Route::patch('/questions/{question}', 'QuestionsController@update');
Route::delete('/questions/{question}', 'QuestionsController@destroy');

Route::get('/dependents/{id}', 'DependentsController@show');
Route::post('/dependents', 'DependentsController@store');
Route::patch('/dependents/{dependent}', 'DependentsController@update');
Route::delete('/dependents/{dependent}', 'DependentsController@destroy');

Route::get('/clientnotes/{id}', 'NotesController@index');
Route::get('/notes/{id}', 'NotesController@show');
Route::post('/notes', 'NotesController@store');
Route::patch('/notes/{note}', 'NotesController@update');
Route::delete('/notes/{note}', 'NotesController@destroy');