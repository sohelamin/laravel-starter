<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
 */

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

// Admin group area
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'roles'], 'roles' => 'admin'], function () {
    Route::get('/', 'Admin\\AdminController@index');
    Route::post('give-role-permissions', 'Admin\\PermissionsController@postGiveRolePermissions');
    Route::resource('roles', 'Admin\\RolesController', ['only' => ['index', 'store', 'update', 'destroy']]);
    Route::resource('permissions', 'Admin\\PermissionsController', ['only' => ['index', 'store', 'update', 'destroy']]);
    Route::resource('users', 'Admin\\UsersController', ['only' => ['index', 'store', 'update', 'destroy']]);
});
