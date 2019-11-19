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


//Auth routes
Route::post('/login', 'AuthController@login');
Route::get('/logout', 'AuthController@logout');
Route::post('/signup', 'AuthController@register')->name('register');
Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');


Route::get('/', function () {

    return redirect('/login');
});

Route::get('/user/dashboard', 'UserAccountsController@showDashboard')->name('user_home');

Route::get('/signup', function () {

    return view('auth.signup');
});

Route::get('/login', 'AuthController@showLogin')->name('login');


Route::get('/admin', function () {
    return redirect()->route('Dashboard');
});
Route::get('/admin/logout', 'AuthController@logout');
Route::get('/admin/dashboard', 'AdminController@home')->name('Dashboard');
Route::get('admin/users/active', 'AdminController@showActiveUsers')->name('Active Users');
Route::get('admin/users/suspended', 'AdminController@showSuspendedUsers')->name('Suspended Users');
Route::get('admin/users/active/{id}', 'AdminController@showActiveUser')->name('User Info');
Route::get('admin/users/suspended/{id}', 'AdminController@showSuspendedUser')->name('User Info');
Route::get('admin/users/new', 'AdminController@showNewUser');
Route::get('admin/change_profile', 'AdminSettingsController@showProfilePage')->name('Change Profile');
Route::post('admin/change_profile', 'AdminSettingsController@changeProfile');
Route::get('admin/change_password', 'AdminSettingsController@showPasswordPage');
Route::get('admin/plans', 'PlansController@showPlans')->name('Plans');
Route::post('admin/plans/new', 'PlansController@createPlan');
Route::post('admin/plans/delete', 'PlansController@deletePlan');
Route::get('admin/plans/{id}', 'PlansController@showEditPage');
Route::post('admin/plans/{id}/change', 'PlansController@change');
Route::get('admin/logs', 'AdminController@showLogs');
Route::post('admin/change_password', 'AdminSettingsController@changePassword');
Route::post('admin/delete/', 'AdminController@deleteUserAccount');
Route::post('admin/suspend/', 'AdminController@suspendUserAccount');
Route::post('admin/activate/', 'AdminController@activateUserAccount');

Route::post('/users/new/{action}', 'AuthController@createNewUser');
Route::get('/user/change_profile', 'UserAccountsController@showChangeProfilePage');
Route::post('/user/change_details', 'UserSettingsController@changeUserProfile');
Route::post('/user/change_password', 'UserSettingsController@changeUserPassword');
Route::get('assets/{token}', 'StorageController@downloadAssetFromAPI');


//projects route
Route::group(['middleware'=>['auth']], function () {
Route::get('/user/projects', 'Project\ProjectsController@showAllProjects');
Route::post('projects/new', 'Project\ProjectsController@createNewProject');
Route::get('projects/{project_id}', 'Project\ProjectsController@visitProject');
Route::get('projects/{project_id}/data', 'DataController@showAll')->name('data_home');
Route::get('projects/{project_id}/data/{id}', 'DataController@showDataProperties');
Route::post('projects/{project_id}/data/{id}/update', 'DataController@updateDataDetails');
Route::post('projects/{project_id}/data/new', 'DataController@createNewData');
Route::get('projects/{project_id}/data/{id}/delete', 'DataController@deleteData');
Route::get('projects/{project_id}/auths', 'Project\AuthController@showAllAuths');
Route::post('projects/{project_id}/auths/{id}/delete', 'Project\AuthController@deleteAuth');
Route::get('projects/chart_details/{project_id}', 'Project\ProjectsController@getProjectChartDetails');
Route::get('projects/storage_details/{project_id}', 'Project\ProjectsController@getProjectStorageDetails');
//index service
Route::get('projects/{project_id}/indexes', 'IndexController@showIndexes')->name('indexes_home');
Route::post('projects/{project_id}/indexes/new', 'IndexController@createNewIndex');
Route::get('projects/{project_id}/indexes/{id}', 'IndexController@editIndex');
Route::post('projects/{project_id}/indexes/{id}/change', 'IndexController@saveChanges');
Route::post('projects/{project_id}/indexes/delete', 'IndexController@deleteIndex');

Route::get('projects/{project_id}/channels', 'ChannelsController@showChannels');
Route::post('projects/{project_id}/channels/new', 'ChannelsController@create');
Route::post('projects/{project_id}/channels/change', 'ChannelsController@changeChannel');

Route::get('projects/{project_id}/traffic', 'RequestController@getTraffic');
Route::get('admin/traffic', 'RequestController@getPlatformTraffic');
Route::get('projects/{project_id}/settings', 'Project\ProjectsController@showSettingsPage');
Route::post('projects/{project_id}/delete', 'Project\ProjectsController@deleteProject');
Route::post('projects/{project_id}/rename', 'Project\ProjectsController@renameProject');

//services


//storage
Route::get('projects/{project_id}/storage/', 'StorageController@showAll');
Route::post('projects/{project_id}/storage/upload', 'StorageController@createNewFile');
Route::get('projects/{project_id}/logs', 'Project\ProjectsController@showLogs');
Route::post('projects/{project_id}/folder/new', 'StorageController@createNewFolder');
Route::get('projects/{project_id}/storage/download/{id}', 'StorageController@downloadAsset');
Route::post('projects/{project_id}/storage/delete/folder', 'StorageController@deleteFolder');
Route::post('projects/{project_id}/storage/delete/file', 'StorageController@deleteFile');
//accounting
Route::get('user/plans', 'PlansController@showUserPlan')->name('user_plans');
Route::post('user/plans/migrate', 'PlansController@migrateToPlan');
Route::get('user/request_auth', 'UserAccountsController@showAuthPage');
//issue: the simple bar should the present for all pages, both admin and users

//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');  
});

