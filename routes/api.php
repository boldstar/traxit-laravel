<?php

use Illuminate\Http\Request;
use App\Exports\EngagementsExport;
use App\Exports\ClientsExport;
use Maatwebsite\Excel\Facades\Excel;
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
Route::post('/free-trial-register', 'System\CompaniesController@freeTrialRegister');
Route::get('/account', 'Tenant\AccountsController@account');
Route::post('/login', 'Tenant\AuthController@login')->middleware('api.login');
Route::group([    
    'namespace' => 'Auth',    
    'middleware' => 'api',    
    'prefix' => 'password'
], function () {    
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});

//auth:api requires access token to access controllers
Route::group(['middleware' => 'auth:api'], function () {   
    Route::get('/tasks', 'Tenant\TasksController@index');
    Route::get('/role', 'Tenant\AuthController@role');
    Route::get('/userProfile/', 'Tenant\AuthController@show');
    Route::patch('/tasks/{task}', 'Tenant\TasksController@update');
    Route::post('/notify-client', 'Tenant\TasksController@notifyClient');
    Route::patch('/batchUpdateTasks', 'Tenant\TasksController@batchUpdateTasks');
    Route::post('/engagements', 'Tenant\EngagementsController@store');
    Route::post('/questions', 'Tenant\QuestionsController@store');
    Route::post('/questionsEmail', 'Tenant\QuestionsController@sendMail');
    Route::patch('/engagements/{engagement}', 'Tenant\EngagementsController@update');
    Route::delete('/engagements/{engagement}', 'Tenant\EngagementsController@destroy');
    Route::post('/logout', 'Tenant\AuthController@logout');

    Route::post('/account', 'Tenant\AccountsController@store');
    Route::post('/accountsetup', 'Tenant\AccountsController@storeOnSetup');
    Route::get('/subscription', 'System\SubscriptionsController@invoices');
    Route::get('/plans', 'System\SubscriptionsController@plans');
    Route::get('/plans-only', 'System\SubscriptionsController@subscriptionPlans');
    Route::get('/grace', 'System\SubscriptionsController@gracePeriod')->middleware('grace.period');
    Route::get('/trial-date', 'System\SubscriptionsController@trialPeriod');
    Route::get('/stripe-key', 'System\SubscriptionsController@stripeKey');
    Route::post('/update-card', 'System\SubscriptionsController@updateCard');
    Route::post('/start-subscription', 'System\SubscriptionsController@start');
    Route::post('/upgrade-subscription', 'System\SubscriptionsController@upgrade');
    Route::post('/resume-subscription', 'System\SubscriptionsController@resume');
    Route::post('/cancel-subscription', 'System\SubscriptionsController@cancel');
    Route::post('/uploadLogo', 'Tenant\AccountsController@uploadLogo');
    Route::patch('/account/{id}', 'Tenant\AccountsController@update');
    
    Route::post('/register', 'Tenant\AuthController@register');
    Route::get('/users', 'Tenant\AuthController@index');
    Route::get('/userToUpdate/{id}', 'Tenant\AuthController@userToUpdate');
    Route::patch('/users/{user}', 'Tenant\AuthController@update');
    Route::delete('/users/{user}', 'Tenant\AuthController@destroy');

    Route::get('/clients', 'Tenant\ClientsController@index');
    Route::get('/clientsWithBusinesses', 'Tenant\ClientsController@clientWithBusinesses');
    Route::get('/clients/{id}', 'Tenant\ClientsController@show');
    Route::post('/clients', 'Tenant\ClientsController@store');
    Route::patch('/clients/{client}', 'Tenant\ClientsController@update');
    Route::delete('/clients/{client}', 'Tenant\ClientsController@destroy');

    Route::get('/businesses', 'Tenant\BusinessesController@index');
    Route::get('/businesses/{id}', 'Tenant\BusinessesController@show');
    Route::post('/businesses', 'Tenant\BusinessesController@store');
    Route::patch('/businesses/{business}', 'Tenant\BusinessesController@update');
    Route::delete('/businesses/{business}', 'Tenant\BusinessesController@destroy');

    Route::get('/engagements-history', 'Tenant\EngagementsHistoryController@index');
    Route::get('/engagements', 'Tenant\EngagementsController@index');
    Route::get('/engagements/{id}', 'Tenant\EngagementsController@clientindex');
    Route::get('/engagementhistory/{id}', 'Tenant\EngagementsController@historyindex');
    Route::get('/clientengagement/{id}', 'Tenant\EngagementsController@show');
    Route::get('/engagementquestions/{id}', 'Tenant\EngagementsController@questionindex');
    Route::get('/engagementReturnTypes', 'Tenant\EngagementsController@returnType_index');
    Route::get('/engagementaverage', 'Tenant\EngagementsHistoryController@average');
    Route::patch('/engagementsarray', 'Tenant\EngagementsController@updateCheckedEngagements');
    Route::patch('/updatereceiveddate', 'Tenant\EngagementsHistoryController@updateReceivedDate');
    Route::post('/archive', 'Tenant\EngagementsController@archiveEngagement');
    Route::patch('/engagement-progress/{engagement}', 'Tenant\EngagementsController@engagementProgress');

    Route::get('/e-notes/{id}', 'Tenant\EngagementNotesController@index');
    Route::get('/show-e-note/{id}', 'Tenant\EngagementNotesController@show');
    Route::post('/add-e-note', 'Tenant\EngagementNotesController@store');
    Route::patch('/edit-e-note', 'Tenant\EngagementNotesController@update');
    Route::delete('/delete-e-note/{id}', 'Tenant\EngagementNotesController@destroy');

    Route::get('/questions/{id}', 'Tenant\QuestionsController@show');
    Route::patch('/questions/{question}', 'Tenant\QuestionsController@update');
    Route::patch('/questionsanswer/{question}', 'Tenant\QuestionsController@updateanswer');
    Route::patch('/editquestionsanswer/{question}', 'Tenant\QuestionsController@editanswer');
    Route::delete('/questions/{question}', 'Tenant\QuestionsController@destroy');

    Route::get('/dependents/{id}', 'Tenant\DependentsController@show');
    Route::post('/dependents', 'Tenant\DependentsController@store');
    Route::patch('/dependents/{dependent}', 'Tenant\DependentsController@update');
    Route::delete('/dependents/{dependent}', 'Tenant\DependentsController@destroy');

    Route::get('/clientnotes/{id}', 'Tenant\NotesController@index');
    Route::get('/notes/{id}', 'Tenant\NotesController@show');
    Route::post('/notes', 'Tenant\NotesController@store');
    Route::patch('/notes/{note}', 'Tenant\NotesController@update');
    Route::delete('/notes/{note}', 'Tenant\NotesController@destroy');

    Route::get('/workflowstatuses', 'Tenant\WorkflowsController@index');
    Route::get('/workflowstatuses/{id}', 'Tenant\WorkflowsController@show');
    Route::post('/workflowstatuses', 'Tenant\WorkflowsController@store');
    Route::post('/createworkflowonsetup', 'Tenant\WorkflowsController@storeOnSetup');
    Route::post('/message', 'Tenant\WorkflowsController@message');
    Route::put('/workflowstatuses', 'Tenant\WorkflowsController@updateWorkflowStatuses');
    Route::patch('/workflowstatuses/{workflow}', 'Tenant\WorkflowsController@workflowStatuses');
    Route::patch('/workflow-activity/{workflow}', 'Tenant\WorkflowsController@changeActivity');
    Route::delete('/workflowstatuses/{status}', 'Tenant\WorkflowsController@destroyStatus');
    Route::delete('/workflow/{workflow}', 'Tenant\WorkflowsController@destroyWorkflow');

    Route::get('/templates', 'Tenant\EmailTemplatesController@index');
    Route::post('/send-test-mail', 'Tenant\EmailTemplatesController@sendTest');

    Route::post('/search', 'Tenant\SearchController@search');
    Route::post('/reports', 'Tenant\ReportsController@excelReport');
    Route::post('/importclients', 'Tenant\ClientsController@importExcel');
    Route::post('/importclientsonsetup', 'Tenant\ClientsController@importExcelOnSetup');
    Route::get('/downloadengagements', function () {
        return Excel::download(new EngagementsExport, 'engagements.xlsx');
    });
    Route::get('/downloadclients', function () {
        return Excel::download(new ClientsExport, 'clients.xlsx');
    });

    Route::get('/tours', 'Tenant\TourController@index');
    Route::post('/complete-setup-tour', 'Tenant\TourController@completeSetup');
});

