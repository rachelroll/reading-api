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
    // 所有书评
    Route::get('/posts', 'PostController@index');
    // 保存书评
    Route::post('/post', 'PostController@store');
    // 获取小程序码
    Route::get('/qrcode', 'PostController@qrcode');
    // 点赞
    Route::post('/post/like', 'PostController@like');
    // 我的所有书评
    Route::get('my-posts', 'PostController@myPost');
    // 书评详情页
    Route::get('/post', 'PostController@show');
    // 所有书评
    Route::get('/posts/search', 'PostController@search');


    // 关于用户
    Route::get('/users', 'UserController@index')->name('api.users');
    // 根据 code 获取请求微信获取 openid
    Route::get('/user-info', 'UserController@info')->name('api.user-info');
    // 存储用户信息
    Route::post('/user-store', 'UserController@store')->name('api.user-store');
    Route::get('/user', 'UserController@userInfo');

    // 对书评的评论
    Route::get('/comments', 'CommentController@index');
    Route::post('/comment', 'CommentController@store');

});