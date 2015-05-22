<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


//landing page
Route::get('/', 'HomeController@index');
//not sure what this one's doing here
Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

//Resource routes
Route::resource('articles', 'ArticleController');
Route::resource('groups', 'GroupController');
Route::resource('leagues', 'LeagueController');
Route::resource('rewards', 'RewardController');
Route::resource('users', 'UserController');
Route::resource('tickets', 'TicketController');

//shhhh! Secret routes are not meant to be used by common mortals
Route::group(array('prefix' => 'secretRoute'), function()
{
    Route::get('migrateTicket','TicketController@firstMigrate');
    Route::get('CalculatePoints','TicketController@CalculatePoints');
    Route::get('CalculatePenalties','TicketController@CalculatePenalties');
});

//API routes, suitable to be called through ajax
Route::group(array('prefix' => 'api/v1'), function()
{
    Route::get('tickets/{start}&{end}', 'ApiController@validateInputs');
    Route::get('groups', 'ApiController@fetchGroupJson');
    Route::get('tickets', 'ApiController@fetchTicketJsonDefault');
});