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

// 示例接口
Route::get('test', 'API\TestController@test');
Route::post('test', 'API\TestController@test');

//用户类路由
Route::group(['prefix' => '', 'middleware' => ['BeforeRequest']], function () {
	
	
	//获取七牛token  完成
	Route::get('user/getQiniuToken', 'API\UserController@getQiniuToken');
	
	//获取首页广告图信息  完成
	Route::get('ad/getADs', 'API\ADController@getADs');
	Route::get('ad/getById', 'API\ADController@getADById');//没用到
	
	//根据id获取用户信息  完成
	Route::get('user/getById', 'API\UserController@getUserById');
	//根据id获取用户信息带token  完成
	Route::get('user/getByIdWithToken', 'API\UserController@getUserInfoByIdWithToken')->middleware('CheckToken');
	//根据code获取openid  完成
	Route::get('user/getXCXOpenId', 'API\UserController@getXCXOpenId');
	//登录  完成
	Route::post('user/login', 'API\UserController@login');
	//注册  完成
	Route::post('user/register', 'API\UserController@register');
	//更新用户信息  完成
	Route::post('user/updateById', 'API\UserController@updateUserById')->middleware('CheckToken');
	//发送验证码  完成
	Route::post('user/sendVertifyCode', 'API\UserController@sendVertifyCode');
	
	//宣教相关
	Route::get('xj/getXJTypes', 'API\XJController@getXJTypes');//  完成
	Route::get('xj/getXJTypeById', 'API\XJController@getXJTypeById');//废弃
	Route::get('xj/getByCon', 'API\XJController@getXJList');  //  完成
	Route::get('xj/getXJInfoById', 'API\XJController@getXJInfoById');//  完成
	Route::get('xj/getAll', 'API\XJController@getAllXJs');//  完成
	
	//数据项相关
	Route::get('sjx/getList', 'API\SJXController@getList');//  废弃
	Route::get('sjx/getListByHPosId', 'API\SJXController@getListByHPosId');//  完成
	Route::get('sjx/getById', 'API\SJXController@getSJXById');//  废弃
	
	//康复模板相关
	Route::get('kfmb/getKFMBById', 'API\KFMBController@getKFMBById');//  完成
	Route::get('kfmb/getKFMBList', 'API\KFMBController@getKFMBList');//  完成
	
	//医生康复师相关
	Route::get('doctor/getDoctorsByRole', 'API\DoctorController@getDoctorsByRole');//  完成
	Route::get('doctor/getById', 'API\DoctorController@getDoctorById');//  完成
	
	//量表相关
	Route::get('lb/getList', 'API\LBController@getList');//  完成
	Route::get('lb/getById', 'API\LBController@getById');//用不到
	Route::get('lb/getQuestionsById', 'API\LBController@getQuestionsById');//  完成
	Route::post('lb/answerLB', 'API\LBController@answerLB')->middleware('CheckToken');//  完成
	Route::get('lb/getAnswerHistory', 'API\LBController@getAnswerHistoryByUserId');//  完成
	Route::get('lb/getAnswerHistoryByID', 'API\LBController@getAnswerHistoryById');//  废弃
	
	//康复计划
	Route::get('kfjh/getBLByUserId', 'API\KFJHController@getBLByUserId');//获取病历   完成
	Route::get('kfjh/getKFJHByUserId', 'API\KFJHController@getKFJHByUserId');//获取康复计划   完成
	
	
	//患者病例
	Route::get('userCase/getUserCaseById', 'API\UserCaseController@getUserCaseInfoByCaseId');//->middleware('CheckToken');//获取病历   完成
	Route::get('userCase/getUserCasesByUserId', 'API\UserCaseController@getUserCasesByUserId')->middleware('CheckToken');//获取病历列表   完成
	Route::post('userCase/getUserZXJHByDate', 'API\UserCaseController@getUserZXJHByDate')->middleware('CheckToken');//根据日期获取当日的康复计划   完成
	Route::get('userCase/getZXJHById', 'API\UserCaseController@getZXJHById');//根据id获取患者执行计划详情       废弃
	Route::post('userCase/executeZXJH', 'API\UserCaseController@executeZXJH')->middleware('CheckToken');//上传执行计划结果      废弃
	
	//患处位置
	Route::get('hPos/getList', 'API\HPosController@getHPosList');//获取患处位置列表  完成
	Route::get('hPos/getById', 'API\HPosController@getHPosById');//根据id获取患处位置  完成
	
	//启动生成康复计划
	Route::post('schedule/autoCreateUserZXJH', 'API\ScheduleController@autoCreateUserZXJH');     //每日自动生成患者执行计划任务    完成
	Route::post('schedule/autoFinishUserZXJH', 'API\ScheduleController@autoFinishUserZXJH');     //每日自动结束患者康复计划   完成
	
	//上报数据
	Route::post('cjsj/reportCJSJ', 'API\CJSJController@reportCJSJ')->middleware('CheckToken');     //上报采集数据  完成
	Route::get('cjsj/getCJSJsByUserId', 'API\CJSJController@getCJSJsByUserId')->middleware('CheckToken');     //根据用户id获取采集上报数据列表   完成
	Route::get('cjsj/getCJSJsByUserCaseId', 'API\CJSJController@getCJSJsByUserCaseId')->middleware('CheckToken');     //根据用户病例id获取采集上报数据列表   完成
	
	
});