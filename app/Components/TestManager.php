<?php
/**
 * File_Name:TestManager.phpger.php
 * Author: leek
 * Date: 2017/9/26
 * Time: 11:23
 */

namespace App\Components;

use App\Http\Controllers\ApiResponse;

class TestManager
{
    public static function test($data)
    {
        if ($data) {
            return $data;
        } else {
            return 'error';
        }
    }
}