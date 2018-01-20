<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\HomeManager;
use App\Components\SechduleManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Components\XJManager;
use App\Components\ZXJHManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\TWStep;
use App\Models\User;
use App\Models\UserCase;
use Illuminate\Support\Facades\Log;
use App\Models\XJ;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class ScheduleController extends Controller
{

    /*
     * 每日生成患者当日的执行计划
     *
     * By TerryQi
     *
     * 2017-12-31
     *
     */
    public static function autoCreateUserZXJH(Request $request)
    {
        $data = $request->all();
        SechduleManager::autoCreateUserZXJH();
        return ApiResponse::makeResponse(true, "执行完成", ApiResponse::SUCCESS_CODE);
    }


    /*
     * 处理未执行的患者执行计划，如果超期未执行status==0就将执行计划置未status==2
     *
     * By TerryQI
     *
     * 2018-1-2
     */
    public static function autoFinishUserZXJH(Request $request)
    {
        $data = $request->all();
        SechduleManager::autoFinishUserZXJH();
        return ApiResponse::makeResponse(true, "执行完成", ApiResponse::SUCCESS_CODE);
    }


}