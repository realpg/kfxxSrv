<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2017/12/16
 * Time: 10:34
 */

namespace App\Http\Controllers\API;

use App\Components\KFJHManager;
use App\Components\KFMBManager;
use App\Components\RequestValidator;
use App\Http\Controllers\ApiResponse;
use Illuminate\Http\Request;

class KFJHController
{
	//根据ID获取康复计划
	public static function getKFJHByUserId(Request $request)
	{
		$data = $request->all();
		$requestValidationResult = RequestValidator::validator($request->all(), [
			'id' => 'required',
		]);
		if ($requestValidationResult !== true) {
			return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
		}
		$bl = KFJHManager::getKFJHById($data['id']);
		if (!$bl) {
			return ApiResponse::makeResponse(false, '未找到用户病历信息', ApiResponse::INNER_ERROR);
		} else
			return ApiResponse::makeResponse(true, $bl, ApiResponse::SUCCESS_CODE);
	}
	
	//根据ID获取病历
	public static function getBLByUserId(Request $request)
	{
		$data = $request->all();
		$requestValidationResult = RequestValidator::validator($request->all(), [
			'id' => 'required',
		]);
		if ($requestValidationResult !== true) {
			return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
		}
		$bl = KFJHManager::getBLById($data['id']);
		if (!$bl) {
			return ApiResponse::makeResponse(false, '未找到用户病历信息', ApiResponse::INNER_ERROR);
		} else {
			$bl = KFJHManager::getBLByLevel($bl, 2);
		}
		return ApiResponse::makeResponse(true, $bl, ApiResponse::SUCCESS_CODE);
	}
	
	//根据ID获取康复数据
	public static function getKFSJByUserId(Request $request)
	{
		$data = $request->all();
		$requestValidationResult = RequestValidator::validator($request->all(), [
			'id' => 'required',
		]);
		if ($requestValidationResult !== true) {
			return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
		}
		$sj = KFJHManager::getKFSJById($data['id']);
		if (!$sj) {
			return ApiResponse::makeResponse(false, '未找到用户病历信息', ApiResponse::INNER_ERROR);
		} else {
			foreach ($sj as $item)
			$item = KFJHManager::getKFSJByLevel($item, 2);
			//增加按照level获得数据，完善数据细节
			return ApiResponse::makeResponse(true, $sj, ApiResponse::SUCCESS_CODE);
		}
	}
}