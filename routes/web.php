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

use Illuminate\Support\Facades\Route;

Route::get('/', 'DashboardController@index')->middleware('auth')->name('home');

Route::resource('projects', 'ProjectsController')->middleware('auth');
Route::resource('tasks', 'TasksController')->middleware('auth');
Route::get('/users', 'UsersController@index')->middleware('auth')->name('users.index');

Route::get('/file/{name}','FileController@index')->middleware('auth')->name('file');
Route::delete('/file/{id}','FileController@destroy')->middleware('auth')->name('file.destroy');

Auth::routes();
