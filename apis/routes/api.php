<?php

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

Route::post('login', 'AuthController@authenticate')->name('login');


Route::group(['middleware' => ['jwt.verify']], function() {

    Route::post('user/profile', 'AuthController@userProfile')->name('auth.userProfile');

/*--------------------------------User Controller----------------------------------------------------------------------------*/
    Route::post('users/list', 'UserController@list')->name('user.list');
    Route::post('user/getUserById', 'UserController@getUserById')->name('user.getUserById');
    Route::post('user/update', 'UserController@update')->name('user.update');
    Route::post('user/add', 'UserController@addUser')->name('user.create');
    Route::post('user/update-password', 'UserController@updatePassword')->name('user.updatePassword');

/*--------------------------------Record Controller----------------------------------------------------------------------------*/

    Route::post('records/list', 'RecordController@list')->name('record.list');
    Route::post('record/create', 'RecordController@create')->name('record.create');
    Route::post('record/getRecordById', 'RecordController@getRecordById')->name('record.getRecordById');
    Route::post('record/update', 'RecordController@updateRecord')->name('record.update');
    Route::post('record/delete', 'RecordController@deleteRecord')->name('record.delete');

/*--------------------------------Country Controller----------------------------------------------------------------------------*/
    
    Route::post('countries/list', 'CountryController@list')->name('country.list');
    Route::post('states/list', 'CountryController@getStateList')->name('country.stateList');


/*--------------------------------Account Controller----------------------------------------------------------------------------*/

    Route::post('accounts/list', 'AccountController@list')->name('account.list');
    Route::post('accounts/create', 'AccountController@create')->name('account.create');
    Route::post('accounts/getAccountById', 'AccountController@getAccountById')->name('account.getAccountById');
    Route::post('accounts/update', 'AccountController@updateAccount')->name('account.update');
    Route::post('accounts/delete', 'AccountController@deleteAccount')->name('account.delete');

/*--------------------------------Contact Controller----------------------------------------------------------------------------*/

    Route::post('contacts/list', 'ContactController@list')->name('contact.list');
    Route::post('contacts/create', 'ContactController@create')->name('contact.create');
    Route::post('contacts/getContactById', 'ContactController@getContactById')->name('contact.getContactById');
    Route::post('contacts/update', 'ContactController@updateContact')->name('contact.update');
    Route::post('contacts/delete', 'ContactController@deleteContact')->name('contact.delete');
  Route::post('contacts/listByAccId', 'ContactController@listByAccId')->name('contact.listByAccId');

/*--------------------------------Note Controller----------------------------------------------------------------------------*/

    Route::post('notes/add', 'NoteController@create')->name('note.create');
    Route::post('notes/list', 'NoteController@list')->name('note.list');
    Route::post('notes/addNotification', 'NoteController@createNotification')->name('note.createNotification');
    Route::post('notes/readNotification', 'NoteController@readNotification')->name('note.readNotification');
    Route::post('notes/readNotificationByID', 'NoteController@readNotificationByID')->name('note.readNotificationByID');
    Route::post('notes/notificationList', 'NoteController@notificationList')->name('note.notificationList');


/*--------------------------------Contact File Controller----------------------------------------------------------------------------*/

    Route::post('file/add', 'ContactFileController@create')->name('contact_file.create');
    Route::post('file/list', 'ContactFileController@list')->name('contact_file.list');

});
