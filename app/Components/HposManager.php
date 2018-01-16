<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\HPos;
use Qiniu\Auth;

class HposManager
{
    /*
     * 获取全部的部位信息
     *
     * By TerryQi
     *
     */
    public static function getHPosList()
    {
        $hPoss = HPos::orderby('seq', 'desc')->get();
        return $hPoss;
    }


    /*
     * 根据id获取患部位置详情
     *
     * By TerryQi
     *
     */
    public static function getHPosById($id)
    {
        $hPos = HPos::where('id', '=', $id)->first();
        return $hPos;
    }


    /*
     * 设置患部详情
     *
     * By TerryQi
     *
     */
    public static function setHPos($hPos, $data)
    {
        if (array_key_exists('doctor_id', $data)) {
            $hPos->doctor_id = array_get($data, 'doctor_id');
        }
        if (array_key_exists('name', $data)) {
            $hPos->name = array_get($data, 'name');
        }
        if (array_key_exists('number', $data)) {
            $hPos->number = array_get($data, 'number');
        }
        if (array_key_exists('img', $data)) {
            $hPos->img = array_get($data, 'img');
        }
        if (array_key_exists('seq', $data)) {
            $hPos->seq = array_get($data, 'seq');
        }
        return $hPos;
    }
}