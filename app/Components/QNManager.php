<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use Qiniu\Auth;

class QNManager
{

    /*
     * 获取七牛upload token
     *
     * By TerryQi
     *
     */
    public static function uploadToken()
    {
        $accessKey = 'JXanCoTnAoyJd4WclS-zPhA8JmWooPTqvK5RCHXb';
        $secretKey = 'ouc-dLEY42KijHeUaTzTBzFeM2Q1mKk_M_3vNpmT';
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'twst';
        $upToken = $auth->uploadToken($bucket);
        return $upToken;
    }
}