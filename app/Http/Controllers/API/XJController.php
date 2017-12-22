<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\HomeManager;
use App\Components\UserManager;
use App\Components\XJManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\XJ;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class XJController extends Controller
{

    /*
     * 获取宣教列表
     *
     * By TerryQi
     *
     * 2017-12-08
     */
    public function getXJList(Request $request)
    {
        $data = $request->all();    //request转array
        $types_arr = [];
        if (array_key_exists('type', $data)) { //如果不存在type
            $types_arr = explode(',', $data['type']);
        }
        $xjs = XJManager::getXJListByCon($types_arr);
        return ApiResponse::makeResponse(true, $xjs, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 获取全部生效宣教
     *
     * By TerryQi
     *
     * 2017-12-14
     */
    public function getAllXJs(Request $request)
    {
        $data = $request->all();    //request转array
        $xjs = XJManager::getAllXJs();
        return ApiResponse::makeResponse(true, $xjs, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 根据id获取宣教详情
     *
     * By TerryQi
     *
     * 2017-12-08
     *
     */
    public function getXJInfoById(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }

        $xj = XJManager::getXJById($data['id']);
        if (!$xj) {
            return ApiResponse::makeResponse(false, '未找到宣教信息', ApiResponse::INNER_ERROR);
        }
        $xj = XJManager::getXJInfoByLevel($xj, 3);
        return ApiResponse::makeResponse(true, $xj, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 获取宣教类型
     *
     * By TerryQi
     *
     * 2017-12-07
     */
    public function getXJTypes(Request $request)
    {
        $xj_types = XJManager::getXJTypes();
        return ApiResponse::makeResponse(true, $xj_types, ApiResponse::SUCCESS_CODE);
    }

    public function getXJTypeById(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }

        $xjType = XJManager::getXJTypeById($data['id']);
        return ApiResponse::makeResponse(true, $xjType, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 编辑宣教
     *
     * By TerryQi
     *
     * 2017-12-21
     */
    public function editXJ(Request $request)
    {
        //将json数据转化为数组
        $data = json_decode(json_encode($request->all()));

        //新建宣教信息


        return ApiResponse::makeResponse(true, $data->steps[0], ApiResponse::SUCCESS_CODE);

    }

}