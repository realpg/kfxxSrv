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
use App\Models\ViewModels\HomeView;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class ADController extends Controller
{

    /*
     * 获取首页轮播图信息
     *
     * By TerryQi
     *
     * 2017-11-27
     */
    public function getADs(Request $request)
    {
        $ads = ADManager::getADsForIndex();
        return ApiResponse::makeResponse(true, $ads, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 根据id获取轮播图信息
     *
     * By TerryQi
     *
     * 2017-12-13
     *
     *
     */
    public function getADById(Request $request)
    {
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $ad = ADManager::getADById($data['id']);
        return ApiResponse::makeResponse(true, $ad, ApiResponse::SUCCESS_CODE);
    }
}