<?php
/**
 * File_Name:ApiResponse.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 14:37
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class ApiResponse
{
    //未知错误
    const UNKNOW_ERROR = 999;
    //缺少参数
    const MISSING_PARAM = 901;

    //成功
    const SUCCESS_CODE = 200;

    //缺少token
    const TOKEN_LOST = 101;
    //token校验失败
    const TOKEN_ERROR = 102;
    //用户编码丢失
    const USER_ID_LOST = 103;

    //注册失败
    const REGISTER_FAILED = 104;
    //未找到用户
    const NO_USER = 105;

    //映射错误信息
    public static $errorMassage = [
        self::UNKNOW_ERROR => '未知错误',
        self::MISSING_PARAM => '缺少参数',
        self::TOKEN_LOST => '缺少token',
        self::TOKEN_ERROR => 'token校验失败',
        self::USER_ID_LOST => '缺少用户编码',
        self::NO_USER => '未找到用户',
        self::REGISTER_FAILED => '注册失败'
    ];

    //格式化返回
    public static function makeResponse($result, $ret, $code, $mapping_function = null, $params = [])
    {
        $rsp = [];
        $rsp['code'] = $code;

        if ($result === true) {
            $rsp['result'] = true;
            $rsp['ret'] = $ret;
        } else {
            $rsp['result'] = false;
            if ($ret) {
                $rsp['message'] = $ret;
            } else {
                if (array_key_exists($code, self::$errorMassage)) {
                    $rsp['message'] = self::$errorMassage[$code];
                } else {
                    $rsp['message'] = 'undefind error code';
                }
            }
        }
        Log::info(__METHOD__ . " response:" . response()->json($rsp));
        return response()->json($rsp);
    }
}