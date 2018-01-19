<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\ADManager;
use App\Components\CJSJManager;
use App\Components\HomeManager;
use App\Components\UserManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\CJSJ;
use App\Models\ViewModels\HomeView;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class CJSJController extends Controller
{

    /*
     * 上传患者数据
     *
     * By TerryQi
     *
     * 2018-01-19
     */
    public function reportCJSJ(Request $request)
    {
        $data = $request->all();
//        dd($data);
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
            'userCase_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //建立数据项
        $user_id = $data['user_id'];
        $userCase_id = $data['userCase_id'];
        $cjsjs = $data['cjsjs'];
        //遍历数据进行数据保存
        foreach ($cjsjs as $cjsj) {
            $sj = new CJSJ();
            //将值由数组转化为字符串
            $cjsj['value'] = implode(",", $cjsj['value']);
            $sj = CJSJManager::setCJSJ($sj, $cjsj);
            $sj->user_id = $user_id;
            $sj->userCase_id = $userCase_id;
            $sj->save();
        }
        return ApiResponse::makeResponse(true, '数据保存成功', ApiResponse::SUCCESS_CODE);
    }


    /*
     * 根据用户id获取采集数据
     *
     *  By TerryQi
     *
     * 2018-01-19
     */
    public function getCJSJsByUserId(Request $request)
    {
        $data = $request->all();
//        dd($data);
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $cjsjs = CJSJManager::getCJSJsByUserIdPaginate($data['user_id']);
        foreach ($cjsjs as $cjsj) {
            $cjsj = CJSJManager::getCJSJByLevel($cjsj, 1);
        }
        return ApiResponse::makeResponse(true, $cjsjs, ApiResponse::SUCCESS_CODE);
    }

    /*
    * 根据用户病例id获取采集数据
    *
    *  By TerryQi
    *
    * 2018-01-19
    */
    public function getCJSJsByUserCaseId(Request $request)
    {
        $data = $request->all();
//        dd($data);
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'userCase_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $cjsjs = CJSJManager::getCJSJsByUserCaseIdPaginate($data['userCase_id']);
        foreach ($cjsjs as $cjsj) {
            $cjsj = CJSJManager::getCJSJByLevel($cjsj, 1);
        }
        return ApiResponse::makeResponse(true, $cjsjs, ApiResponse::SUCCESS_CODE);
    }

}