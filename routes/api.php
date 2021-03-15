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

/**
 * These routes are to handle things that do not require token
 */
Route::post('/files', 'Tenant\ShareFilesController@storeFiles');
Route::get('/account', 'Tenant\AccountsController@account');
Route::post('/login', 'Tenant\AuthController@login')->middleware('api.login');
Route::post('/guest-login', 'Tenant\GuestCLientLoginController@guestLogin');
Route::post('/guest-register', 'Tenant\GuestCLientLoginController@guestRegister');
Route::post('/free-trial-register', 'System\CompaniesController@freeTrialRegister');
Route::post('/create-code', 'Tenant\TwoFactorController@createCode');
Route::post('/create-new-code', 'Tenant\TwoFactorController@createNewCode');
Route::post('confirm-code', 'Tenant\TwoFactorController@confirmCode')->middleware('2fa.expired');

/**
 * These are for handling the resets of passwords
 */
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
    Route::get('/role', 'Tenant\AuthController@role');
    Route::get('/userProfile/', 'Tenant\AuthController@show');
    Route::post('/notify-client', 'Tenant\TasksController@notifyClient');
    Route::post('/logout', 'Tenant\AuthController@logout');
    
    Route::get('/tasks', 'Tenant\TasksController@index');
    Route::patch('/tasks/{task}', 'Tenant\TasksController@update');
    Route::patch('/batchUpdateTasks', 'Tenant\TasksController@batchUpdateTasks');

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
    Route::get('/user-history/{id}', 'Tenant\EngagementsHistoryController@getUserHistory');

    Route::get('/clients', 'Tenant\ClientsController@index');
    Route::get('/clientsWithBusinesses', 'Tenant\ClientsController@clientWithBusinesses');
    Route::get('/clients/{id}', 'Tenant\ClientsController@show');
    Route::post('/clients', 'Tenant\ClientsController@store');
    Route::patch('/clients/{client}', 'Tenant\ClientsController@update');
    Route::delete('/clients/{client}', 'Tenant\ClientsController@destroy');

    Route::get('/businesses', 'Tenant\BusinessesController@index');
    Route::get('/businesses/{id}', 'Tenant\BusinessesController@show');
    Route::get('/business-engagements/{id}', 'Tenant\BusinessesController@businessEngagements');
    Route::post('/businesses', 'Tenant\BusinessesController@store');
    Route::patch('/businesses/{business}', 'Tenant\BusinessesController@update');
    Route::delete('/businesses/{business}', 'Tenant\BusinessesController@destroy');

    Route::patch('/engagements/{engagement}', 'Tenant\EngagementsController@update');
    Route::delete('/engagements/{engagement}', 'Tenant\EngagementsController@destroy');
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
    Route::post('/engagements', 'Tenant\EngagementsController@store');

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
    Route::post('/questions', 'Tenant\QuestionsController@store');
    Route::post('/questionsEmail', 'Tenant\QuestionsController@sendMail');

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
    Route::post('/downloadengagements', 'Tenant\EngagementsController@downloadEngagements');
    Route::get('/downloadclients', function () {
        return Excel::download(new ClientsExport, 'clients.xlsx');
    });

    /**
     * This is for the customization routes
     */
    Route::post('/customization', 'Tenant\CustomizationController@create');
    Route::post('/customizations', 'Tenant\CustomizationController@get');
    Route::post('/customizations-delete', 'Tenant\CustomizationController@delete');

    /**
     * This is for the settings routes
     */
    Route::get('/settings', 'Tenant\SettingsController@get');
    Route::post('/setting', 'Tenant\SettingsController@update');



    /**
     * part of on boarding process deciding if user should see tour or not.
     */
    Route::get('/tours', 'Tenant\TourController@index');
    Route::post('/complete-setup-tour', 'Tenant\TourController@completeSetup');
    

    /*
    * This is for sharing files from client to there busines
    * requests come from inbox on business side
    */
    Route::get('/files', 'Tenant\ShareFilesController@getFiles');
    Route::get('/archived-files', 'Tenant\ShareFilesController@getArchivedFiles');
    Route::get('/number-of-files', 'Tenant\ShareFilesController@numberOfFiles');
    Route::post('/download-client-file', 'Tenant\ShareFilesController@getClientFile');
    Route::get('/download-client-files/{id}', 'Tenant\ShareFilesController@getClientFiles');
    Route::patch('/archive-client-files/{id}', 'Tenant\ShareFilesController@archiveClientFiles');
    Route::delete('/delete-files/{id}', 'Tenant\ShareFilesController@deleteFiles');


    /**
     * This is for managing documents from business to the client
     * Also related to portal on business side
     */
    Route::post('/portal-upload', 'Tenant\DocumentPortalController@storeDocument');
    Route::get('/portal-files/{id}', 'Tenant\DocumentPortalController@getPortalFiles');
    Route::get('/portal-file/{id}', 'Tenant\DocumentPortalController@getPortalFile');
    Route::patch('/update-portal-file/{doc}', 'Tenant\DocumentPortalController@updatePortalFile');
    Route::delete('/portal-file/{id}', 'Tenant\DocumentPortalController@deletePortalFile');
    Route::post('/guest-invite', 'Tenant\GuestCLientLoginController@guestInvite');
    Route::get('/invite-status/{id}', 'Tenant\GuestClientLoginController@guestExist');
    Route::get('/portal-users/{id}', 'Tenant\GuestClientLoginController@getPortalUsers');
    Route::delete('/delete-portal-users/{id}', 'Tenant\GuestClientLoginController@deletePortal');
    Route::post('/remove-portal-user', 'Tenant\GuestClientLoginController@removeGuestUser');

    /*
    *This is for performing crud on bookkeeping model
    */
    Route::get('/bookkeeping-accounts', 'Tenant\BookkeepingController@getBookkeepingAccounts');
    Route::post('/bookkeeping-account', 'Tenant\BookkeepingController@store');
    Route::post('/bookkeeping-account-name', 'Tenant\BookkeepingController@updateBookkeepingName');
    Route::post('/bookkeeping-account-new-year', 'Tenant\BookkeepingController@storeNewYear');
    Route::post('/delete-bookkeeping-year', 'Tenant\BookkeepingController@deleteYear');
    Route::patch('/bookkeeping-account/{bookkeeping}', 'Tenant\BookkeepingController@updateAccount');
    Route::patch('/update-bookkeeping-account-month/{bookkeeping}', 'Tenant\BookkeepingController@updateMonth');
    Route::delete('/bookkeeping-account/{id}', 'Tenant\BookkeepingController@delete');
    Route::delete('/all-bookkeeping-accounts/{name}', 'Tenant\BookkeepingController@deleteAll');


    /**
     * This for handling read access to the business' clients documents
     * Switching provider to guest to verify the client auth token
     * requests come from guest portal side
     */
    Route::group(['middleware' => 'guest-provider'], function (){
        Route::post('/guest-logout', 'Tenant\GuestCLientLoginController@guestLogout');
        Route::post('/get-guest-documents', 'Tenant\DocumentPortalController@getDocuments');
        Route::post('/get-guest-document', 'Tenant\DocumentPortalController@getDocument');
        Route::post('/get-guest-document-details', 'Tenant\DocumentPortalController@getDocumentDetails');
    });
});

