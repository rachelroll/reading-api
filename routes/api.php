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

Route::group(['namespace' => 'Api'], function () {
    // 关于书评
    Route::get('/posts', 'PostController@index');
    Route::post('/post', 'PostController@store');
    // 获取小程序码
    Route::get('/qrcode', 'PostController@qrcode');



    // 关于用户
    Route::middleware('auth:api')->get('/users', 'UserController@index')->name('api.users');
    // 根据 code 获取请求微信获取 openid
    Route::get('/user-info', 'UserController@info')->name('api.user-info');
    // 存储用户信息
    Route::post('/user-store', 'UserController@store')->name('api.user-store');
    Route::get('/user', 'UserController@userInfo');


});