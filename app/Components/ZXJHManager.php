<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\User;
use App\Models\UserCase;
use App\Models\UserKFJH;
use App\Models\Vertify;
use App\Models\ZXJH;
use GuzzleHttp\Psr7\Request;

class ZXJHManager
{

    /*
     * 是否已经存在康复计划（未来多线程考虑）
     *
     * By TerryQi
     *
     * 2017-12-31
     *
     */
    public static function isZXJHExist($kfjh, $date)
    {
        $zxjh = ZXJH::where('kfjh_id', '=', $kfjh->id)->where('jh_date', '=', $date)->first();
        if ($zxjh) {
            return true;
        } else {
            return false;
        }
    }


    /*
     * 根据kfjh生成zxjh
     *
     * By TerryQi
     *
     * 2017-12-31
     */
    public static function createZXJH($kfjh)
    {
        //生成执行计划
        $zxjh = new ZXJH();
        $zxjh = self::setZXJH($zxjh, $kfjh->toArray());
        $zxjh->kfjh_id = $kfjh->id;
        $zxjh->jh_date = DateTool::getToday();
        $zxjh->save();
    }

    /*
     * 根据状态生成执行计划
     *
     * By TerryQi
     *
     * 2018-1-2
     *
     * $status为数组，即["0"]\["0","1"]
     */
    public static function getZXJHListByStatus($status)
    {
        $zxjhs = ZXJH::whereIn('status', $status)->get();
        return $zxjhs;
    }


    /*
    * 生成执行计划
    *
    * By TerryQi
    *
    * 2017-12-31
    *
    */
    public static function setZXJH($zxjh, $data)
    {
        if (array_key_exists('user_id', $data)) {
            $zxjh->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('userCase_id', $data)) {
            $zxjh->userCase_id = array_get($data, 'userCase_id');
        }
        if (array_key_exists('name', $data)) {
            $zxjh->name = array_get($data, 'name');
        }
        if (array_key_exists('desc', $data)) {
            $zxjh->desc = array_get($data, 'desc');
        }
        if (array_key_exists('important', $data)) {
            $zxjh->important = array_get($data, 'important');
        }
        if (array_key_exists('jh_date', $data)) {
            $zxjh->jh_date = array_get($data, 'jh_date');
        }
        if (array_key_exists('zx_time', $data)) {
            $zxjh->zx_time = array_get($data, 'zx_time');
        }
        if (array_key_exists('zx_desc', $data)) {
            $zxjh->zx_desc = array_get($data, 'zx_desc');
        }
        return $zxjh;
    }

    //根据患者id和日期获取当日的执行计划
    public static function getZXJHByUserIdAndDate($user_id, $date)
    {
        $zxjh = ZXJH::where('user_id', '=', $user_id)->where('jh_date', '=', $date)->get();
        return $zxjh;
    }


    //根据执行计划id获取执行计划信息
    public static function getZXJHById($id)
    {
        $zxjh = ZXJH::where('id', '=', $id)->first();
        return $zxjh;
    }


    /*
     * 处理患者执行计划，如果患者status==0并且超期，则将患者执行计划置为1，未执行
     *
     * By TerryQi
     *
     * 2018-1-2
     */
    public static function handleNoExecutedZXJH($zxjh)
    {
        $jh_date = $zxjh->jh_date;      //计划执行的时间
        $today = DateTool::getToday();      //今天的日期
        $diff = DateTool::dateDiff('D', $jh_date, $today);
        //如果diff>0，且status==0，则代表需要设置执行计划为未执行
        if ($diff > 0) {
            if ($zxjh->status == "0") {
                $zxjh->status = "1";
                $zxjh->save();
            }
        }
    }
}