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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


//用户类路由
Route::group(['prefix' => '', 'middleware' => ['BeforeRequest']], function () {
    // 示例接口
    Route::get('test', 'API\TestController@test');
    Route::post('test', 'API\TestController@test');

    //获取七牛token
    Route::get('user/getQiniuToken', 'API\UserController@getQiniuToken');

    //获取首页广告图信息
    Route::get('ad/getADs', 'API\ADController@getADs');
    Route::get('ad/getById', 'API\ADController@getADById');

    Route::get('ad/getADs', 'API\ADController@getADs');

    //根据id获取用户信息
    Route::get('user/getById', 'API\UserController@getUserById');
    //根据id获取用户信息带token
    Route::get('user/getByIdWithToken', 'API\UserController@getUserInfoByIdWithToken')->middleware('CheckToken');
    //根据code获取openid
    Route::get('user/getXCXOpenId', 'API\UserController@getXCXOpenId');
    //登录
    Route::post('user/login', 'API\UserController@login');
    //注册
    Route::post('user/register', 'API\UserController@register');
    //更新用户信息
    Route::post('user/updateById', 'API\UserController@updateUserById')->middleware('CheckToken');
    //发送验证码
    Route::post('user/sendVertifyCode', 'API\UserController@sendVertifyCode');

    //宣教相关
    Route::get('xj/getXJTypes', 'API\XJController@getXJTypes');
    Route::get('xj/getXJTypeById', 'API\XJController@getXJTypeById');
    Route::get('xj/getByCon', 'API\XJController@getXJList');
    Route::get('xj/getXJInfoById', 'API\XJController@getXJInfoById');
    Route::get('xj/getAll', 'API\XJController@getAllXJs');
    //Route::post('xj/editXJ', 'API\XJController@editXJ');

    //数据项相关
    Route::get('sjx/getList', 'API\SJXController@getList');
    Route::get('sjx/getById', 'API\SJXController@getSJXById');

    //康复模板相关
    Route::get('kfmb/getKFMBById', 'API\KFMBController@getKFMBById');
    Route::get('kfmb/getKFMBList', 'API\KFMBController@getKFMBList');
    //Route::post('kfmb/editKFMB', 'API\KFMBController@editKFMB');

    //医生康复师相关
    Route::get('doctor/getDoctorsByRole', 'API\DoctorController@getDoctorsByRole');
    Route::get('doctor/getById', 'API\DoctorController@getDoctorById');

    //量表相关
    Route::get('lb/getList', 'API\LBController@getList');
    Route::get('lb/getById', 'API\LBController@getById');
    Route::get('lb/getQuestionsById', 'API\LBController@getQuestionsById');
    Route::post('lb/answerLB', 'API\LBController@answerLB');
    Route::get('lb/getAnswerHistory', 'API\LBController@getAnswerHistoryByUserId');

    //康复计划
    Route::get('kfjh/getBLByUserId', 'API\KFJHController@getBLByUserId');//获取病历
    Route::get('kfjh/getKFJHByUserId', 'API\KFJHController@getKFJHByUserId');//获取康复计划
    Route::get('kfjh/getKFSJByUserId', 'API\KFJHController@getKFSJByUserId');//获取康复数据

    //患者病例
    Route::get('userCase/getUserCaseById', 'API\UserCaseController@getUserCaseInfoByCaseId');//获取病历
    Route::post('userCase/editUserCaseKFJH', 'API\UserCaseController@editUserCaseKFJH');


});