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

Route::middleware('auth:api')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/tasks', 'TasksController@index');
    Route::get('/role', 'AuthController@role');
    Route::patch('/tasks/{task}', 'TasksController@update');
    Route::post('/logout', 'AuthController@logout');
});


Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');

Route::get('/users', 'AuthController@index');

Route::get('/clients', 'ClientsController@index');
Route::get('/clients/{id}', 'ClientsController@show');
Route::post('/clients', 'ClientsController@store');
Route::patch('/clients/{client}', 'ClientsController@update');
Route::delete('/clients/{client}', 'ClientsController@destroy');

Route::get('/engagements', 'EngagementsController@index');
Route::get('/engagements/{id}', 'EngagementsController@clientindex');
Route::get('/clientengagement/{id}', 'EngagementsController@show');
Route::get('/engagementquestions/{id}', 'EngagementsController@questionindex');
Route::get('/engagementsdata', 'EngagementsController@chartdata');
Route::post('/engagements', 'EngagementsController@store');
Route::patch('/engagements/{engagement}', 'EngagementsController@update');
Route::patch('/engagementsarray', 'EngagementsController@updateCheckedEngagements');
Route::delete('/engagements/{engagement}', 'EngagementsController@destroy');

Route::get('/questions/{id}', 'QuestionsController@show');
Route::post('/questions', 'QuestionsController@store');
Route::patch('/questions/{question}', 'QuestionsController@update');
Route::patch('/questionsanswer/{question}', 'QuestionsController@updateanswer');
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

Route::get('/workflowstatuses', 'WorkflowsController@index');
Route::get('/workflowstatuses/{id}', 'WorkflowsController@show');
Route::post('/workflowstatuses', 'WorkflowsController@store');
Route::put('/workflowstatuses', 'WorkflowsController@updateWorkflowStatuses');
Route::patch('/workflowstatuses/{workflow}', 'WorkflowsController@workflowStatuses');
Route::delete('/workflowstatuses/{status}', 'WorkflowsController@destroy');
Route::delete('/workflow/{workflow}', 'WorkflowsController@destroyWorkflow');

Route::post('/search', 'SearchController@search');