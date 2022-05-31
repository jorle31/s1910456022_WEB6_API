<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TimeslotAgreementController;
use App\Http\Controllers\TimeslotController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//nur nach login
Route::group(['middleware' => ['api', 'auth.jwt']], function(){
    //Post a new service
    Route::post('services', [ServiceController::class, 'save']);
    //Update a Service
    Route::put('services/{id}', [ServiceController::class,'update']);
    //Delete a service
    Route::delete('services/{id}', [ServiceController::class,'delete']);
    //Create a new user
    Route::post('users', [UserController::class, 'save']);
    //Update user information
    Route::put('users/{id}', [UserController::class,'update']);
    //Create a new timeslot
    Route::post('timeslots', [TimeslotController::class, 'save']);
    //Update timeslot information
    Route::put('timeslots/{id}', [TimeslotController::class,'update']);
    //Delete a timeslot
    Route::delete('timeslots/{id}', [TimeslotController::class,'delete']);
    //Create a new comment
    Route::post('comments', [CommentController::class, 'save']);
    //Update comment
    Route::put('comments/{id}', [CommentController::class,'update']);
    //Delete a comment
    Route::delete('comments/{id}', [CommentController::class,'delete']);
    //Log the user out
    Route::post('auth/logout', [AuthController::class, 'logout']);
    //Get the currently logged-in user
    Route::get('users/current', [AuthController::class, 'me']);
    //Get a specific timeslot
    Route::get('subject/exists/{id}', [SubjectController::class, 'subjectExists']);
});

//Get a specific timeslot
Route::get('timeslots/{id}', [TimeslotController::class, 'getSpecificTimeslotById']);
//Get a specific timeslot
Route::get('timeslots/pending/{id}', [TimeslotController::class, 'getAllPendingTimeslotAgreementsOfTutor']);
//Get a specific timeslot
Route::get('timeslots/accepted/{id}', [TimeslotController::class, 'getAllAcceptedTimeslotAgreementsOfTutor']);
//Get a specific timeslot
Route::get('timeslots/student/accepted/{id}', [TimeslotController::class, 'getAllAcceptedTimeslotAgreementsOfStudent']);
//Get a specific timeslot
Route::get('timeslots/student/pending/{id}', [TimeslotController::class, 'getAllPendingTimeslotAgreementsOfStudent']);
//--------------------------------------------------------------------------------------

//Get all services
Route::get('services', [ServiceController::class, 'index']);
//Get all services
Route::get('services/latest', [ServiceController::class, 'getLatest']);
//Get a specific service
Route::get('services/{id}', [ServiceController::class, 'getSpecificServiceById']);
//Get a specific user's services
Route::get('services/user/{id}', [ServiceController::class, 'getSpecificUsersServiceById']);
//Get a service by availability
Route::get('services/pending/{id}', [ServiceController::class, 'getServicesWithPending']);
//Get a service by availability
Route::get('services/accepted/{id}', [ServiceController::class, 'getServicesWithAccepted']);

//--------------------------------------------------------------------------------------

//Get all subjects
Route::get('subjects', [SubjectController::class, 'getSubjects']);
//Get a specific subject
Route::get('subjects/{id}', [SubjectController::class, 'getSpecificSubjectsById']);

//--------------------------------------------------------------------------------------

//Get all users
Route::get('users', [UserController::class, 'getUsers']);
//Get a specific user
Route::get('users/{id}', [UserController::class, 'getSpecificUserById']);

//--------------------------------------------------------------------------------------

//Get all comments of service
Route::get('comments/services/{id}', [CommentController::class, 'getCommentsOfService']);
//Get all comments of user
Route::get('comments/{id}', [CommentController::class, 'getSpecificCommentById']);

//--------------------------------------------------------------------------------------

//Get specific timeslotAgreement with id
Route::get('timeslotagreements/{id}', [TimeslotAgreementController::class, 'getSpecificTimeslotAgreement']);
//Get specific timeslotAgreement with id
Route::get('timeslotagreements', [TimeslotAgreementController::class, 'getAllTimeslotAgreements']);
//Create timeslotAgreement
Route::post('timeslotagreements', [TimeslotAgreementController::class, 'save']);
//Update timeslotAgreement
Route::put('timeslotagreements/{id}', [TimeslotAgreementController::class, 'update']);
//Delete timeslotAgreement
Route::delete('timeslotagreements/{id}', [TimeslotAgreementController::class, 'delete']);
//Get specific timeslotAgreement with id
Route::get('timeslotagreements/user/{id}', [TimeslotAgreementController::class, 'getAllTimeslotAgreementsOfUser']);
//Get specific timeslotAgreement with id
Route::get('timeslotagreements/user/pending/{id}', [TimeslotAgreementController::class, 'getAllTimeslotAgreementsOfUserWithPending']);
//--------------------------------------------------------------------------------------

/*auth*/
Route::post('auth/login', [AuthController::class, 'login']);

