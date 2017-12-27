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


}