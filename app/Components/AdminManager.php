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

class AdminManager
{

    /*
     * 设置管理员信息，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setAdmin($admin, $data)
    {
        if (array_key_exists('nick_name', $data)) {
            $admin->nick_name = array_get($data, 'nick_name');
        }
        if (array_key_exists('avatar', $data)) {
            $admin->avatar = array_get($data, 'avatar');
        }
        if (array_key_exists('phonenum', $data)) {
            $admin->phonenum = array_get($data, 'phonenum');
        }
        if (array_key_exists('password', $data)) {
            $admin->password = array_get($data, 'password');
        }
        if (array_key_exists('role', $data)) {
            $admin->role = array_get($data, 'role');
        }
        return $admin;
    }
}