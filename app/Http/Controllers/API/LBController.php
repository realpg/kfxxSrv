<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2017/12/14
 * Time: 17:52
 */


namespace App\Http\Controllers\API;

use App\Components\LBMannager;
use App\Components\RequestValidator;
use App\Components\UserManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;

class LBController extends Controller
{
	//获得量表列表
	public static function getList()
	{
		$LBlist = LBMannager::getLBList();
		return ApiResponse::makeResponse(true, $LBlist, ApiResponse::SUCCESS_CODE);
	}
	public function getById(Request $request)
	{
		$data = $request->all();
		//合规校验account_type
		$requestValidationResult = RequestValidator::validator($request->all(), [
			'id' => 'required',
		]);
		if ($requestValidationResult !== true) {
			return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
		}
		$lb = LBMannager::getLBById($data['id']);
		if (!$lb) {
			return ApiResponse::makeResponse(false, '未找到宣教信息', ApiResponse::INNER_ERROR);
		}
		$lb = LBMannager::getLBDetailByLevel($lb,3);
		return ApiResponse::makeResponse(true, $lb, ApiResponse::SUCCESS_CODE);
	}
	//答复量表
	public static function answerLB(Request $request)
	{
		$data=$request->all();
		$requestValidationResult = RequestValidator::validator($request->all(), [
			'user_id' => 'required',
			'lb_id'=>'required',
			'result' => 'required',
		]);
		if ($requestValidationResult !== true) {
			return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
		}
		$ans=LBMannager::answerLB($data);
		if (!$ans) {
			return ApiResponse::makeResponse(false, '创建问题失败', ApiResponse::INNER_ERROR);
		}
		
		return ApiResponse::makeResponse(true, $ans, ApiResponse::SUCCESS_CODE);
		
	}
	//根据用户id获取回答历史
	public static function getAnswerHistoryByUserId(Request $request){
		$requestValidationResult = RequestValidator::validator($request->all(), [
			'user_id' => 'required',
		]);
		if ($requestValidationResult !== true) {
			return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
		}
		$data=$request->all();
		$ans=LBMannager::getAnswersByID($data['user_id']);
		if (!$ans) {
			return ApiResponse::makeResponse(false, '获取答案失败', ApiResponse::INNER_ERROR);
		}
		foreach ($ans as $an) {
			$an->lb = LBMannager::getLBById($an->lb_id);
		}
		
		return ApiResponse::makeResponse(true, $ans, ApiResponse::SUCCESS_CODE);
	}
	
	//根据ID获取量表问题
	public static function getQuestionsById(Request $request)
	{
		$data = $request->all();
		$requestValidationResult = RequestValidator::validator($request->all(), [
			'id' => 'required',
		]);
		if ($requestValidationResult !== true) {
			return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
		}
		$lb = LBMannager::getLBById($data['id']);
		if (!$lb) {
			return ApiResponse::makeResponse(false, '未找到问题信息', ApiResponse::INNER_ERROR);
		}
		$lbQuestion = LBMannager::getLBDetailByLevel($lb, 2);
		return ApiResponse::makeResponse(true, $lbQuestion, ApiResponse::SUCCESS_CODE);
	}

}