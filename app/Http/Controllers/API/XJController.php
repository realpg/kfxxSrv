<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\HomeManager;
use App\Components\HposManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Components\XJManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\TWStep;
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
        $hpos_arr = [];
        //如果存在hpos，则获取hpos数组
        if (array_key_exists('hpos_ids', $data) && !Utils::isObjNull($data['hpos_ids'])) { //如果不存在hpos
            $hpos_arr = explode(',', $data['hpos_ids']);
        } else {
            $all_hposs = HposManager::getHPosList();
            //如果不存在hpos，则获取全部的hpos_ids
            foreach ($all_hposs as $hpos) {
                array_push($hpos_arr, $hpos->id);
            }
        }
        $xjs = XJManager::getXJListByCon($hpos_arr);
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
        $xjs = XJManager::getAllXJs("1");   //获取生效宣教
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

}