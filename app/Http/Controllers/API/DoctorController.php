<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\DoctorManager;
use App\Components\HomeManager;
use App\Components\UserManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\User;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class DoctorController extends Controller
{
    //根据类型获取医生列表
    /*
     * By TerryQi
     *
     * 2017-12-19
     */
    public function getDoctorsByRole(Request $request)
    {
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'role' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $doctors = DoctorManager::getDoctorsByRole($data['role']);
        return ApiResponse::makeResponse(true, $doctors, ApiResponse::SUCCESS_CODE);
    }

}