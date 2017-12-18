<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2017/12/14
 * Time: 17:52
 */
namespace App\Http\Controllers\API;
use App\Components\LBMannager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;

class LBController extends Controller{
	public static function getList(){
		$LBlist=LBMannager::getLBList();
		return ApiResponse::makeResponse(true, $LBlist, ApiResponse::SUCCESS_CODE);
	}
}