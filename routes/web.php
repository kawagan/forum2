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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/threads/create','ThreadController@create');

Route::get('/threads/search','SearchController@show'); 
// we handle when we delete id from url: /threads/esse/48, after delete: /threads/esse/
Route::get('/threads/{channel?}','ThreadController@index');

//Route::get('threads/{channel}','ThreadController@index');

Route::get('/threads/{channel}/{thread}','ThreadController@show');
Route::delete('/threads/{channel}/{thread}','ThreadController@destroy')->name('delete.thread');
Route::post('/threads','ThreadController@store');
Route::patch('/threads/{channel}/{thread}','ThreadController@update')->name('thread.update');

//locked thread
Route::post('/locked-threads','LockedThreadController@store')->name('locked-thread.store');
Route::delete('/locked-threads','LockedThreadController@destroy')->name('locked-thread.delete');

Route::post('/threads/{channel}/{thread}/replies','ReplyController@store');
Route::post('/replies/{reply}/favoraite','FavoriteController@store');
Route::delete('/replies/{reply}','ReplyController@destroy');

// subscripionss
Route::post('/threads/{channel}/{thread}/subscribtions','ThreadSubscriptionController@store');
Route::delete('/threads/{channel}/{thread}/subscribtions','ThreadSubscriptionController@destroy');
//profile
Route::get('/profile/{user}','ProfileController@show')->name('profile');
Route::get('/profile/{user}/notifications','UserNotificationController@index');
Route::delete('/profile/{user}/notifications/{notification}','UserNotificationController@destroy')
        ->name('profile.notifications.delete');

Route::post('/profile/{users}/avatar','Api\UserAvatarController@store')->name('avatar');

//email confirmation
Route::get('register/confirm/{token}','RegisterConfiramtionController@index');


Route::group([
    'prefix' => 'admin',
    'middleware' => 'is-admin',
    'namespace' => 'Admin'
], function() {
    Route::get('/', 'DashboardController@index')->name('admin.dashboard.index');
    Route::post('/channels', 'ChannelsController@store')->name('admin.channels.store');
    Route::get('/channels', 'ChannelsController@index')->name('admin.channels.index');
    Route::get('/channels/create', 'ChannelsController@create')->name('admin.channels.create');
});

