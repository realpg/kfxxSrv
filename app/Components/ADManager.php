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

class ADManager
{
    /*
     * 获取首页生效的广告轮播图信息
     *
     * By TerryQi
     *
     * 2017-11-27
     *
     */
    public static function getADsForIndex()
    {
        $ads = AD::where('status', '=', '1')->orderby('seq', 'desc')->get();
        return $ads;
    }

    /*
     * 根据id获取轮播图
     *
     * By TerryQi
     *
     * 2017-12-13
     *
     */
    public static function getADById($id)
    {
        $ad = AD::where('id', '=', $id)->first();
        return $ad;
    }

    /*
     * 获取全部未删除的广告图
     *
     * By TerryQI
     *
     * 2017-12-04
     *
     */
    public static function getAllADs()
    {
        $ads = AD::orderBy('seq', 'desc')->orderBy('id', 'desc')->paginate(10);
        return $ads;
    }


    /*
     * 设置广告信息，用于编辑、
     *
     * By TerryQi
     *
     */
    public static function setAD($ad, $data)
    {
        if (array_key_exists('doctor_id', $data)) {
            $ad->doctor_id = array_get($data, 'doctor_id');
        }
        if (array_key_exists('img', $data)) {
            $ad->image = array_get($data, 'img');
        }
        if (array_key_exists('content', $data)) {
            $ad->content = array_get($data, 'content');
        }
        if (array_key_exists('xj_id', $data)) {
            $ad->xj_id = array_get($data, 'xj_id');
        }
        if (array_key_exists('status', $data)) {
            $ad->status = array_get($data, 'status');
        }
        return $ad;
    }
}