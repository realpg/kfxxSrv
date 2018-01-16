<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\ADManager;
use App\Components\HomeManager;
use App\Components\HposManager;
use App\Components\UserManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\ViewModels\HomeView;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class HPosController extends Controller
{

    /*
     * 获取位置列表
     *
     * By TerryQi
     *
     */
    public function getHPosList(Request $request)
    {
        $hPoss = HposManager::getHPosList();
        return ApiResponse::makeResponse(true, $hPoss, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 根据id获取位置详情
     *
     * By TerryQi
     *
     */
    public function getHPosById(Request $request)
    {
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $hPos = HposManager::getHPosById($data['id']);
        return ApiResponse::makeResponse(true, $hPos, ApiResponse::SUCCESS_CODE);
    }
}