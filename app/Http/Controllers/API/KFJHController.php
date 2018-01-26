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
		$kfjhs = KFJHManager::getKFJHById($data['id']);
		if (!$kfjhs) {
			return ApiResponse::makeResponse(false, '未找到用户病历信息', ApiResponse::INNER_ERROR);
		} else {
			$bl = KFJHManager::getBLById($data['id']);
			if (!$bl) {
				return ApiResponse::makeResponse(false, '未找到用户病历信息', ApiResponse::INNER_ERROR);
			}
			$ss_time = strtotime($bl->ss_time);
			foreach ($kfjhs as $kfjh) {
				//手术后
				if ($kfjh->btime_type == '0') {
					$start_unit = ($kfjh->start_unit == '0' ? 86400 : ($kfjh->start_unit == '1' ? 604800 : 2592000));
					$kfjh->start_time_stamp = $ss_time + $kfjh->start_time * $start_unit;
					$kfjh->start_date = date("Y-m-d", $kfjh->start_time_stamp);
					$end_unit = ($kfjh->end_unit = '0' ? 86400 : ($kfjh->end_unit = '1' ? 604800 : 2592000));
					$kfjh->end_time_stamp = $ss_time + $kfjh->end_time * $end_unit;
					$kfjh->end_date = date("Y-m-d", $kfjh->end_time_stamp);
				} else if ($kfjh->btime_type == '2') {
					$kfjh->set_date_time_stamp = strtotime($kfjh->set_date);
					//指定时间
				}
			}
			
		}
		return ApiResponse::makeResponse(true, $kfjhs, ApiResponse::SUCCESS_CODE);
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