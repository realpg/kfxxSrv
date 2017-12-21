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


//登录

Route::get('/admin/login', 'Admin\LoginController@login');        //登录
Route::post('/admin/login', 'Admin\LoginController@loginPost');   //post登录请求
Route::get('/admin/loginout', 'Admin\LoginController@loginout');  //注销

Route::group(['prefix' => 'admin', 'middleware' => ['admin.login']], function () {

    //首页
    Route::get('/', 'Admin\IndexController@index');       //首页
    Route::get('/index', 'Admin\IndexController@index');  //首页
    Route::get('/dashboard/index', 'Admin\IndexController@index');    //首页

    //错误页面
    Route::get('/error/500', 'Admin\IndexController@error');  //错误页面

    //轮播管理
    Route::get('/ad/index', 'Admin\ADController@index');  //轮播管理首页
    Route::get('/ad/setStatus/{id}', 'Admin\ADController@setStatus');  //设置轮播状态
    Route::get('/ad/del/{id}', 'Admin\ADController@del');  //删除轮播
    Route::get('/ad/edit', 'Admin\ADController@edit');  //新建或编辑轮播
    Route::post('/ad/edit', 'Admin\ADController@editPost');  //新建或编辑轮播

    //管理员管理
    Route::get('/doctor/index', 'Admin\DoctorController@index');  //管理员管理首页
    Route::get('/doctor/del/{id}', 'Admin\DoctorController@del');  //删除管理员
    Route::get('/doctor/edit', 'Admin\DoctorController@edit');  //新建或编辑管理员
	Route::post('/doctor/search', 'Admin\DoctorController@search');  //搜索管理员
    Route::post('/doctor/edit', 'Admin\DoctorController@editPost');  //新建或编辑管理员

    //宣教管理
    Route::get('/xj/index', 'Admin\XJController@index');  //宣教管理首页
    Route::get('/xj/setStatus/{id}', 'Admin\XJController@setStatus');  //设置宣教状态
    Route::get('/xj/setStep/{id}', 'Admin\XJController@setStep');  //设置宣教图文
    Route::post('/xj/setStep/{id}', 'Admin\XJController@setStepPost');  //编辑宣教步骤信息
    Route::get('/xj/delStep/{id}', 'Admin\XJController@delStep');  //删除宣教步骤
    Route::get('/xj/edit', 'Admin\XJController@edit');  //新建或编辑宣教
    Route::post('/xj/edit', 'Admin\XJController@editPost');  //新建或编辑宣教
    Route::get('/xj/del/{id}', 'Admin\XJController@del');  //删除宣教
	

    //宣教分类管理
    Route::get('/xjType/index', 'Admin\XJController@indexType');  //宣教分类管理首页
    Route::get('/xjType/edit', 'Admin\XJController@editType');  //新建或编辑宣教分类
    Route::post('/xjType/edit', 'Admin\XJController@editTypePost');  //新建或编辑宣教分类
	

    //数据项管理
    Route::get('/sjx/index', 'Admin\SJXController@index');  //数据项管理首页
    Route::get('/sjx/edit', 'Admin\SJXController@edit');  //新建或编辑数据项
    Route::post('/sjx/edit', 'Admin\SJXController@editPost');  //新建或编辑数据项

    //康复模板管理
    Route::get('/kfmb/index', 'Admin\KFMBController@index');  //康复模板管理首页
    Route::get('/kfmb/edit', 'Admin\KFMBController@edit');  //康复模板管理
    Route::get('/kfmb/setStatus/{id}', 'Admin\KFMBController@setStatus');  //设置康复模板状态
    Route::post('/kfmb/edit', 'Admin\KFMBController@editPost');  //新建或编辑宣教分类
    Route::get('/kfmb/del/{id}', 'Admin\KFMBController@del');  //删除康复模板
    Route::get('/kfmb/setStep/{id}', 'Admin\KFMBController@setStep');  //设置康复模板
    Route::post('/kfmb/setStep/{id}', 'Admin\KFMBController@setStepPost');  //编辑步骤信息
    Route::get('/kfmb/delStep/{id}', 'Admin\KFMBController@delStep');  //删除步骤
    Route::get('/kfmb/setJH/{id}', 'Admin\KFMBController@setJH');  //设置康复模板计划
    Route::post('/kfmb/setJH/{id}', 'Admin\KFMBController@setJHPost');  //编辑康复模板计划
    Route::get('/kfmb/delJH/{id}', 'Admin\KFMBController@delJH');  //删除康复模板计划
    Route::get('/kfmb/delJHXJ', 'Admin\KFMBController@delJHXJ');  //删除康复模板计划关联的宣教
    Route::post('/kfmb/setCJSJ', 'Admin\KFMBController@setCJSJPost');  //删除康复模板计划关联的宣教
    Route::get('/kfmb/delCJSJ', 'Admin\KFMBController@delCJSJ');  //删除康复模板计划采集数据

    //患者管理
    Route::get('/user/index', 'Admin\UserController@index');  //患者管理首页
    Route::post('/user/search', 'Admin\UserController@search');  //搜索用户信息
    Route::post('/user/edit', 'Admin\UserController@editPost');  //患者管理首页
    Route::get('/user/editUserCase', 'Admin\UserController@editUserCase');  //编辑患者病例
    Route::post('/user/editUserCase', 'Admin\UserController@editUserCasePost');  //编辑患者病例


    //量表相关
    Route::get('/lb/index', 'Admin\LBController@index');  //量表管理首页  ok
    Route::get('/lb/setStatus/{id}', 'Admin\LBController@setStatus');  //量表宣教状态 ok
    Route::get('/lb/detail/{id}', 'Admin\LBController@detail');  //量表问题 ok
	Route::post('/lb/setQue/{id}', 'Admin\LBController@setQuestionPost');  //编辑量表问题 ok
	Route::get('/lb/setQue/{id}', 'Admin\LBController@setQuestion');  //编辑量表问题 ok
	Route::get('/lb/delQue/{id}', 'Admin\LBController@delQue');  //删除量表问题条目 ok
    Route::get('/lb/edit', 'Admin\LBController@edit');  //新建或编辑量表
    Route::post('/lb/edit', 'Admin\LBController@editPost');  //新建或编辑量表
    Route::get('/lb/del/{id}', 'Admin\LBController@del');  //删除量表ok

});