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

Route::middleware('auth:api')->get('photo', 'PhotoController@index');
Route::middleware('auth:api')->get('photo/{photo}', 'PhotoController@show');
Route::middleware('auth:api')->post('photo', 'PhotoController@store');
Route::middleware('auth:api')->patch('photo/{photo}', 'PhotoController@update');
Route::middleware('auth:api')->delete('photo/{photo}', 'PhotoController@delete');

Route::middleware('auth:api')->get('user', 'UserController@index');
Route::middleware('auth:api')->post('/user/{user}/share', 'UserController@share');

Route::post('signup', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::middleware('auth:api')->post('logout', 'Auth\LoginController@logout');

