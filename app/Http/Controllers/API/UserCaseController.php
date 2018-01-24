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
use App\Components\ZXJHManager;
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
     * 根据病例id获取用户病例信息
     *
     * By TerryQi
     *
     * 2017-11-27
     */
    public function getUserCaseInfoByCaseId(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $requestValidationResult = RequestValidator::validator($data, [
            'id' => 'required',
            'level' => 'required',
        ]);
        if ($requestValidationResult != true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $userCase = UserManager::getUserCaseById($data['id']);
        $userCase = UserManager::getUserCaseInfoByLevel($userCase, $data['level']);
        return ApiResponse::makeResponse(true, $userCase, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 根据user_id获取用户病例信息
     *
     * By TerryQi
     *
     * 2017-11-27
     */
    public function getUserCasesByUserId(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $userCases = UserManager::getUserCasesByUserId($data['user_id']);
        foreach ($userCases as $userCase) {
            $userCase = UserManager::getUserCaseInfoByLevel($userCase, "0");
        }
        return ApiResponse::makeResponse(true, $userCases, ApiResponse::SUCCESS_CODE);
    }

    /*
    * 根据user_id和日期获取执行计划
    *
    * By TerryQi
    *
    * 2018-1-2
    */
    public function getUserZXJHByDate(Request $request)
    {
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
            'date' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $data = $request->all();
        $zxjhs = ZXJHManager::getZXJHByUserIdAndDate($data['user_id'], $data['date']);
        return ApiResponse::makeResponse(true, $zxjhs, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 根据id获取执行计划信息
     *
     * By TerryQi
     *
     * 2018-1-2
     */
    public function getZXJHById(Request $request)
    {
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $data = $request->all();
        $zxjh = ZXJHManager::getZXJHById($data['id']);
        return ApiResponse::makeResponse(true, $zxjh, ApiResponse::SUCCESS_CODE);
    }

    //患者进行计划执行，POST执行结果及采集数据的复杂结果
    /*
     * By TerryQi
     *
     * 2018-1-2
     */
    public function executeZXJH(Request $request)
    {
        //获取数据，要求ajax设置Content-Type为application/json; charset=utf-8
        $data = $request->all();
        //新建/编辑宣教信息
        $zxjh = ZXJHManager::getZXJHById($data['id']);
        $zxjh = ZXJHManager::setZXJH($zxjh, $data);
        $zxjh->status = "2";  //代表已经执行完成
        $zxjh->save();      //保存执行结果
        //返回执行结果
        $zxjh = ZXJHManager::getZXJHById($zxjh->id);
        return ApiResponse::makeResponse(true, $zxjh, ApiResponse::SUCCESS_CODE);
    }

}