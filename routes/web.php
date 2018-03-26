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
    return 'Hello World!';
});

Route::get('/project', 'ProjectController@index');
Route::get('/project/create', 'ProjectController@create');
Route::get('/project/update', 'ProjectController@update');
Route::get('/project/delete', 'ProjectController@delete');

Route::get('/route', 'RouteController@index');
Route::get('/route/create', 'RouteController@create');
Route::get('/route/update', 'RouteController@update');



Route::any('/wechat', 'WeChatDockingController@index');
Route::get('/wechatAuth', 'WeChatAuthController@index');
Route::any('/upload/image', 'UploadController@image');
Route::any('/upload/dir', 'UploadController@dir');
Route::get('/wechat/path/{id}', 'WeChatAuthController@path');
