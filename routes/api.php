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

Route::group(['prefix' => 'todos', 'as' => 'todos'], function(){
    Route::get('/', ['as' => 'index', 'uses' => 'TodosController@index']);
    Route::post('/create', ['as' => 'create', 'uses' => 'TodosController@create']);
    Route::patch('/edit/{id}', ['as' => 'edit', 'uses' => 'TodosController@edit']);
    Route::delete('/delete/{id}', ['as' => 'edit', 'uses' => 'TodosController@delete']);
});

Route::group(['prefix' => 'user', 'as' => 'user'], function(){
    Route::post('/register', ['as' => 'register', 'uses' => 'UserController@register']);
    Route::post('/login', ['as' => 'login', 'uses' => 'UserController@login']);

    Route::group(['middleware' => 'auth:api'], function (){
        Route::get('/', ['as' => 'index', 'uses' => 'UserController@index']);
        Route::post('/logout', ['as' => 'logout', 'uses' => 'UserController@logout']);
    });
});
