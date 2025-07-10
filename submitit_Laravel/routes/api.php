<?php

use App\Http\Controllers\Extra\ExtraServicesController;
use App\Models\ExtraServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Story\StoryController;
use App\Http\Controllers\Story\StoryScoreController;
use App\Http\Controllers\Story\TaskController;
use App\Http\Controllers\Journal\JournalController;
use App\Http\Controllers\Journal\JournalScoreController;
use App\Http\Controllers\Package\PackageController;
use App\Http\Controllers\submititController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\JournalStory\StorySubmissionController;
use App\Http\Controllers\JournalStory\JournalStoryController;
use App\Http\Controllers\Ckeditor\ckeditorController;
use App\Http\Controllers\Subscription\SubscriptionController;
use App\Http\Controllers\Subscription\WebhookController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/getAuthenticatedUser', [UserController::class, 'getAuthenticatedUser']);

Route::post('/getSingleClient', [UserController::class, 'getSingleClient']);

Route::post('/editClient', [UserController::class, 'editClient']);

Route::post('/updateClient', [UserController::class, 'updateClient']);

Route::get('/GetClients', [submititController::class, 'getClients']);

// ------Profile Route --------------

Route::post('/password/change', [submititController::class, 'changePassword']);

// ----submitit routes-------Post---------
Route::post('/login', [submititController::class, 'login']);
Route::post('/logout', [submititController::class, 'logout']);
Route::post('/passwordCreate', [submititController::class, 'passwordMail']);

Route::post('/getEmail', [submititController::class, 'getEmail']);

Route::get('/getAllData', [submititController::class, 'getAllData']);

Route::get('/getStoryJournalData', [submititController::class, 'getStoryJournalData']);

Route::get('/getData', [submititController::class, 'getData']);

Route::post('/password/reset', [submititController::class, 'forgotPasswordReset']);
Route::post('/registration', [submititController::class, 'registrationClient']);

//  Story Routes

Route::post('/getClientDoNotSubmitJournals', [StoryController::class, 'getClientDoNotSubmitJournals']);

Route::post('/storeStory', [StoryController::class, 'storeStory']);

Route::get('/getAllStories', [StoryController::class, 'getAllStories']);

Route::post('/getSingleStory', [StoryController::class, 'getSingleStory']);

Route::get('/getRecentStory', [StoryController::class, 'getRecentStory']);

Route::post('/editStory', [StoryController::class, 'editStory']);

Route::post('/updateStory', [StoryController::class, 'updateStory']);

Route::post('/deleteStory', [StoryController::class, 'deleteStory']);

Route::post('/getClientLimit', [StoryController::class, 'getClientLimit']);

Route::post('/getSelectedPackages', [StoryController::class, 'getSelectedPackages']);

Route::post('/getDoNotSubmitJournals', [StoryController::class, 'getDoNotSubmitJournals']);

Route::post('/putDoNotSubmitList', [StoryController::class, 'putDoNotSubmitList']);

Route::post('/addInDoNotSubmitList', [StoryController::class, 'addInDoNotSubmitList']);

Route::post('/removeSubmissionJournal', [StoryController::class, 'removeSubmissionJournal']);

Route::post('/rejectSubmissionJournal', [StoryController::class, 'rejectSubmissionJournal']);

Route::post('/approvePayment', [StoryController::class, 'approvePayment']);

// Story Score Routes

Route::post('/storeStoryScore', [StoryScoreController::class, 'storeStoryScore']);

//  Journal Routes

Route::post('/addJournal', [JournalController::class, 'addJournal']);

Route::get('/getJournals', [JournalController::class, 'getJournals']);

Route::get('/getAllJournals', [JournalController::class, 'getAllJournals']);

Route::post('/getJournalsWithScore', [JournalController::class, 'getJournalsWithScore']);

Route::post('/editJournal', [JournalController::class, 'editJournal']);

Route::post('/updateJournal', [JournalController::class, 'updateJournal']);

Route::post('/deleteJournal', [JournalController::class, 'deleteJournal']);

Route::post('/getSingleJournal', [JournalController::class, 'getSingleJournal']);

Route::get('/getRecentJournals', [JournalController::class, 'getRecentJournals']);

Route::post('/getJounralStories', [JournalController::class, 'getJounralStories']);

Route::post('/addDummyStory', [JournalController::class, 'addDummyStory']);

Route::post('/updateAllJournalStatus', [JournalController::class, 'updateAllJournalStatus']);

// Journal Score Routes


Route::post('/storeJournalScore', [JournalScoreController::class, 'storeJournalScore']);

//  Package Routes

Route::get('/getPackages', [PackageController::class, 'getPackages']);

Route::get('/getPendingPayments', [PackageController::class, 'getPendingPayments']);


// Task Routes

Route::get('/getAllTasks', [TaskController::class, 'getAllTasks']);

Route::get('/getEmailTemplates', [TaskController::class, 'getEmailTemplates']);

Route::post('/addTask', [TaskController::class, 'addTask']);

Route::post('/getTasks', [TaskController::class, 'getTasks']);

Route::post('/getClientStoryTasks', [TaskController::class, 'getClientStoryTasks']);

Route::post('/editTask', [TaskController::class, 'editTask']);

Route::post('/updateTask', [TaskController::class, 'updateTask']);

Route::post('/deleteTask', [TaskController::class, 'deleteTask']);

Route::post('/changeTaskOrder', [TaskController::class, 'changeTaskOrder']);

Route::get('/getDefaultTask', [TaskController::class, 'getDefaultTask']);

Route::post('/changeDefaultTaskOrder', [TaskController::class, 'changeDefaultTaskOrder']);

Route::post('/editDefaultTask', [TaskController::class, 'editDefaultTask']);

Route::post('/updateDefaultTask', [TaskController::class, 'updateDefaultTask']);

Route::post('/runTask', [TaskController::class, 'runTask']);


//  Client Portal Routes

Route::post('/signUp', [ClientController::class, 'signUp']);

Route::post('/clientStories', [ClientController::class, 'clientStories']);

Route::post('/editClientStory', [clientController::class, 'editClientStory']);

Route::post('/updateClientStory', [clientController::class, 'updateClientStory']);

Route::post('/checkEmail', [ClientController::class, 'checkEmail']);

Route::post('/getAllInvoices', [ClientController::class, 'getAllInvoices']);

Route::post('/downloadInvoice', [ClientController::class, 'downloadInvoice']);

Route::post('/getPendingPayment', [ClientController::class, 'getPendingPayment']);

Route::post('/getActiveSubscription', [ClientController::class, 'getActiveSubscription']);

//  Journal Story Routes

Route::post('/journalStoryStore', [JournalStoryController::class, 'journalStoryStore']);

Route::post('/getJournalStory', [JournalStoryController::class, 'getJournalStory']);

Route::post('/deleteJournalStory', [JournalStoryController::class, 'deleteJournalStory']);

Route::post('/editJournalStory', [JournalStoryController::class, 'editJournalStory']);

Route::post('/updateJournalStory', [JournalStoryController::class, 'updateJournalStory']);

Route::post('/storySubmission', [StorySubmissionController::class, 'storySubmission']);

Route::post('/editSubmissionData', [StorySubmissionController::class, 'editSubmissionData']);

Route::post('/updateSubmissionData', [StorySubmissionController::class, 'updateSubmissionData']);

Route::post('/getStorySubmissionResult', [StorySubmissionController::class, 'getStorySubmissionResult']);

Route::get('/storyInReview', [StorySubmissionController::class, 'storyInReview']);

Route::get('/getSubmissionResult', [StorySubmissionController::class, 'getSubmissionResult']);

Route::post('/getClientStorySubmissionResult', [StorySubmissionController::class, 'getClientStorySubmissionResult']);

Route::post('/getStoryStatus', [StorySubmissionController::class, 'getStoryStatus']);

//  Ck-Editor

Route::post('/updateEmailTemplate', [ckeditorController::class, 'updateEmailTemplate']);

Route::post('/editEmailTemplate', [ckeditorController::class, 'editEmailTemplate']);

Route::get('/getEmailTemplate', [ckeditorController::class, 'getEmailTemplate']);

// Stripe 

Route::get('/setup-intent', [SubscriptionController::class, 'getSetupIntent']);

Route::post('/createSubscription', [SubscriptionController::class, 'createSubscription']);

Route::post('/cancelSubscription', [SubscriptionController::class, 'cancelSubscription']);

Route::post('/handleWebhook', [WebhookController::class, 'handleWebhook']);


// Extra Services

Route::post('/addRound3', [ExtraServicesController::class, 'addRound3']);

Route::get('/getServices', [ExtraServicesController::class, 'getServices']);

Route::post('/extraService', [ExtraServicesController::class, 'extraService']);

Route::post('/addExtraService', [ExtraServicesController::class, 'createExtraAddOn']);

Route::post('/confirm-payment-intent', [ExtraServicesController::class, 'confirmPaymentIntent']);


//      Add More JOurnals 

Route::post('/addMoreJournal',[JournalController::class,'addMoreJournal']);

Route::post('/removeAddMoreJournal',[JournalController::class,'removeAddMoreJournal']);

Route::post('/getAddMoreJournal',[JournalController::class,'getAddMoreJournal']);