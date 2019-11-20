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


//handle all private access to the data

//handle public access to the data
Route::get('public/streams/{token}', 'StorageController@streamAsset');
Route::group(['middleware' => 'cors'], function () {

    Route::get('public/data/tag/{tag}', 'AccessPublicData@getPublicWithTag');
    Route::get('public/data/tag/latest/{tag}', 'AccessPublicData@getLatestPublicWithTag');
    Route::get('public/data/{id}', 'AccessPublicData@getPublic');

});

//projects

Route::group(['middleware' => ['cors', 'auth:api', 'active'] ], function() {
    Route::get('projects/{project_id}/data/latest', 'AccessPrivateData@getLatestData');
    Route::get('projects/{project_id}/data/all', 'AccessPrivateData@getAll');
    Route::get('projects/{project_id}/data/count', 'AccessPrivateData@getDataCount');
    Route::get('projects/{project_id}/data/search', 'AccessPrivateData@search');
    Route::get('projects/{project_id}/data/search/{action}', 'AccessPrivateData@search');
    //added in: v 1.1 i.e added pagination to search in 1.1
    Route::get('projects/{project_id}/data/search/{action}/{length}', 'AccessPrivateData@search'); //done
    Route::get('projects/{project_id}/data/{id}', 'AccessPrivateData@getDataContent');
    Route::get('projects/{project_id}/data/fixed/{length}', 'AccessPrivateData@getFixedLength');
    Route::get('projects/{project_id}/data/fixed/{length}/{order}', 'AccessPrivateData@getFixedLength');
    //added in: v 1.1
    Route::get('projects/{project_id}/data/paginate/{length}', 'AccessPrivateData@paginate');
    //added in: v 1.1
    Route::get('projects/{project_id}/data/paginate/{length}/{order}', 'AccessPrivateData@paginate');
    
    //manipulate data
    
    Route::post('projects/{project_id}/data/new', 'DataModificationController@createNewData');
    Route::post('projects/{project_id}/data/{id}/change_listener', 'DataModificationController@changeListener');
    Route::post('projects/{project_id}/data/{id}/delete', 'DataModificationController@deleteData'); //done
    Route::post('projects/{project_id}/data/search/{action}', 'DataModificationController@getByConstraint'); //done
    Route::post('projects/{project_id}/data/{id}/update', 'DataModificationController@update');
    Route::post('projects/{project_id}/data/{id}/replace', 'DataModificationController@replaceObject');
    
    //storage
    Route::post('projects/{project_id}/storage/new', 'StorageController@createNewFile');
    Route::post('projects/{project_id}/storage/delete/{token}', 'StorageController@deleteFileFromAPI');
    Route::get('projects/{project_id}/storage/{token}', 'StorageController@downloadAssetFromAPI');

    //authorization

    Route::post('projects/{id}/auths/verify', 'Project\AuthController@verify');
    Route::post('projects/{id}/auths/create', 'Project\AuthController@makeAuth');
    Route::post('projects/{project_id}/auths/delete', 'Project\AuthController@delete_auth');

    //for channel now
    //trigger an event on a specific channel

    Route::post('projects/{project_id}/channels/{channel}/broadcast/{event}', 'Project\EventsController@triggerEvent');
    Route::post('projects/{project_id}/channels/{channel}/trigger/{event}', 'Project\EventsController@triggerEvent');
    Route::post('projects/{project_id}/channels/{channel}/delete', 'Project\EventsController@deleteChannel');
    //Route::post('projects/{id}/channels/{channel}/authorize', 'Project\EventsController@authorizeClient');

    //app data
    Route::post('projects/{project_id}/user/{id}/data/new', 'AppUserController@createNewDataEntry');

    Route::post('projects/{project_id}/channels/{channel}/{event}/publish', 'Services\BroadcastServicesController@publish');

    //indexes

    Route::post('projects/{project_id}/indexes/{index}', 'IndexController@accessByIndex');

    Route::get('projects/{id}/pusher_auth', 'PusherController@authorizeClient');

});

