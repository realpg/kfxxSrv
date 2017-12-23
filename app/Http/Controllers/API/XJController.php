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
        //获取数据，要求ajax设置Content-Type为application/json; charset=utf-8
        $data = $request->all();
        //新建/编辑宣教信息
        $xj = new XJ();
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $xj = XJManager::getXJById($data['id']);
        }
        $xj = XJManager::setXJ($xj, $data);
        $xj->save();
        //获取数据库中原有的信息
        $ori_steps = XJManager::getStepsByFidAndFtable($xj->id, 'xj');
        $new_steps = $data['steps'];
        //删除步骤
        foreach ($ori_steps as $ori_step) {
            if (!Utils::isIdInArray($ori_step->id, $new_steps)) {
                $ori_step->delete();
            }
        }
        //新建/编辑步骤
        foreach ($new_steps as $new_step) {
            $new_step['f_id'] = $xj->id;
            $new_step['f_table'] = "xj";
            $twStep = new TWStep();
            if (array_key_exists('id', $new_step) && !Utils::isObjNull($new_step['id'])) {
                $twStep = XJManager::getStepById($new_step['id']);
            }
            $twStep = XJManager::setTWStep($twStep, $new_step);
            $twStep->save();
        }
        //重新获取宣教信息并返回
        $xj = XJManager::getXJInfoByLevel($xj, 3);
        return ApiResponse::makeResponse(true, $xj, ApiResponse::SUCCESS_CODE);
    }


}