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
    // ��������
    // ��������
    Route::get('/posts', 'PostController@index');
    // ��������
    Route::post('/post', 'PostController@store');
    // ��ȡС������
    Route::get('/qrcode', 'PostController@qrcode');
    // ����
    Route::post('/post/like', 'PostController@like');
    // �ҵ���������
    Route::get('my-posts', 'PostController@myPost');
    // ��������ҳ
    Route::get('/post', 'PostController@show');
    // ��������
    Route::get('/posts/search', 'PostController@search');


    // �����û�
    Route::get('/users', 'UserController@index')->name('api.users');
    // ���� code ��ȡ����΢�Ż�ȡ openid
    Route::get('/user-info', 'UserController@info')->name('api.user-info');
    // �洢�û���Ϣ
    Route::post('/user-store', 'UserController@store')->name('api.user-store');
    Route::get('/user', 'UserController@userInfo');

    // ������������
    Route::get('/comments', 'CommentController@index');
    Route::post('/comment', 'CommentController@store');

});