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
use App\Components\UserManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\User;
use App\Models\UserKFJH;
use App\Models\UserKFJHSJ;
use App\Models\ViewModels\HomeView;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class UserCaseController extends Controller
{

    /*
     * 根据user_id获取用户病例信息
     *
     * By TerryQi
     *
     * 2017-11-27
     */
    public function getUserCaseInfoByCaseId(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
            'level' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $userCase = UserManager::getUserCaseById($data['id']);
        $userCase = UserManager::getUserCaseInfoByLevel($userCase, $data['level']);
        return ApiResponse::makeResponse(true, $userCase, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 编辑患者病例康复计划
     *
     * By TerryQi
     *
     * 2017-12-28
     */
    public function editUserCaseKFJH(Request $request)
    {
        //获取数据，要求ajax设置Content-Type为application/json; charset=utf-8
        $data = $request->all();
//        dd($data);
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //新建/编辑康复模板
        $userCase = UserManager::getUserCaseById($data['id']);
        $userCase = UserManager::getUserCaseInfoByLevel($userCase, "12");
        //根据用户病例id获取全部康复计划
        $ori_jhs = $userCase->jhs;
        //删除原有康复计划以及计划下关联的数据
        foreach ($ori_jhs as $ori_jh) {
            $ori_jhsjs = UserManager::getUserCaseJHSJByJHId($ori_jh->id);
            $ori_jh->delete();
            foreach ($ori_jhsjs as $ori_jhsj) {
                $ori_jhsj->delete();
            }
        }
        //新建康复计划及数据
        $new_jhs = $data['jhs'];
        foreach ($new_jhs as $new_jh) {
            $jh = new UserKFJH();
            $jh = UserManager::setKFJH($jh, $new_jh);
            $jh->user_id = $userCase->user_id;
            $jh->userCase_id = $userCase->id;
            $jh->save();
            //新建康复计划采集数据
            $new_jhsjs = $new_jh['jhsjs'];
            foreach ($new_jhsjs as $new_jhsj) {
                $jhsj = new UserKFJHSJ();
                $jhsj = UserManager::setKFJHSJ($jhsj, $new_jhsj);
                $jhsj->user_id = $userCase->user_id;
                $jhsj->kfjh_id = $jh->id;
                $jhsj->userCase_id = $userCase->id;
                $jhsj->save();
            }
        }
        //保存患者病例的康复计划
        $userCase = UserManager::getUserCaseInfoByLevel($userCase, "12");
        return ApiResponse::makeResponse(true, $userCase, ApiResponse::SUCCESS_CODE);
    }

}