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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', 'PassportController@register');
Route::post('login', 'PassportController@login');
Route::get('logout', 'PassportController@logout')->middleware('auth:api');
 
Route::middleware('auth:api', 'admin.only')->group(function () {
    // List Users
    Route::get('users', 'UserController@index');

    // List single User
    Route::get('user/{id}', 'UserController@show');

    // Create new User
    Route::post('user/', 'UserController@store');

    // Update a User
    Route::put('user/', 'UserController@store');

    // Delete a User
    Route::delete('user/{id}', 'UserController@destroy');

    // Delete multiple User
    Route::post('users/delete-multiple', 'UserController@deleteMultiple');
});