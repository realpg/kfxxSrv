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
use App\Components\KFMBManager;
use App\Components\UserManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\ViewModels\HomeView;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class KFMBController extends Controller
{

    /*
     * 获取康复模板列表
     *
     * By TerryQi
     *
     * 2017-11-27
     */
    public function getKFMBList(Request $request)
    {
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'type' => 'required',
        ]);
        $kfmbs = KFMBManager::getKFMBList($data['type']);
        return ApiResponse::makeResponse(true, $kfmbs, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 根据id获取康复模板基本信息
     *
     * By TerryQi
     *
     * 2017-12-12
     */
    public function getKFMBById(Request $request)
    {
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }

        $kfmb = KFMBManager::getKFMBById($data['id']);
        $kfmb = KFMBManager::getKFMBInfoByLevel($kfmb, $data['level']);
        return ApiResponse::makeResponse(true, $kfmb, ApiResponse::SUCCESS_CODE);
    }


}