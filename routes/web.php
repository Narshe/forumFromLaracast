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

Route::get('/', 'ThreadsController@index');

Auth::routes();

Route::get('/threads', 'ThreadsController@index');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
Route::get('/threads/create', 'ThreadsController@create');
Route::get('/threads/{channel}', 'ThreadsController@index');
Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index');

Route::post('/threads', 'ThreadsController@store')->middleware('must-be-confirmed');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store');
Route::post('/replies/{reply}/best', 'BestRepliesController@store')->name('best-reply.store');


Route::post("/locked-threads/{thread}", 'LockedThreadsController@store')->middleware('must-be-admin');
Route::delete("/locked-threads/{thread}", 'LockedThreadsController@delete')->middleware('must-be-admin');

Route::patch('/replies/{reply}', 'RepliesController@update');
Route::patch('/threads/{thread}', 'ThreadsController@update');

Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::delete('/replies/{reply}', 'RepliesController@destroy')->name('delete_reply');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');


Route::get('/api/users', 'Api\UsersController@index');
Route::post('/api/users/{user}/avatar', 'Api\UsersAvatarController@store')->middleware('auth');

Route::get('/register/confirm', 'Auth\RegisterConfirmationController@index');
