<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use Qiniu\Auth;
use App\Components\HomeManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Components\XJManager;
use App\Components\ZXJHManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\User;
use App\Models\UserCase;
use Illuminate\Support\Facades\Log;
use App\Models\XJ;
use Illuminate\Http\Request;
use App\Components\RequestValidator;

class SechduleManager
{
    /*
     * 自动生成每日患者的执行计划
     *
     * By TerryQi
     *
     * 2018-01-20
     */
    public static function autoCreateUserZXJH()
    {
        //停止所有的应该停止的康复计划
        $kfjhs_to_finish = UserManager::getKFJHListByStatus(["0", "1"]);
        foreach ($kfjhs_to_finish as $kfjh) {
            UserManager::handleToFinisheKFJH($kfjh);
        }
        //处理开始的康复计划
        $kfjhs_to_start = UserManager::getKFJHListByStatus(["0"]);
        foreach ($kfjhs_to_start as $kfjh) {
            UserManager::handleStartKFJH($kfjh);
        }
        //处理执行中的康复计划
        $kfjhs_to_execute = UserManager::getKFJHListByStatus(["1"]);
        foreach ($kfjhs_to_execute as $kfjh) {
            UserManager::handleExecutingKFJH($kfjh);
        }
    }

    /*
     * 自动结束掉康复计划
     *
     * By TerryQi
     *
     * 2018-01-20
     */
    public static function autoFinishUserZXJH()
    {
        $zxjhs_init_status = ZXJHManager::getZXJHListByStatus(["0"]);
        foreach ($zxjhs_init_status as $zxjh) {
            ZXJHManager::handleNoExecutedZXJH($zxjh);
        }
    }
}