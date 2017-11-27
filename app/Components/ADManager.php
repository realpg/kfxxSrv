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
        $ads = AD::where('status', '=', '1')->get();
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
        if (array_key_exists('title', $data)) {
            $ad->title = array_get($data, 'title');
        }
        if (array_key_exists('img', $data)) {
            $ad->img = array_get($data, 'img');
        }
        if (array_key_exists('url', $data)) {
            $ad->url = array_get($data, 'url');
        }
        if (array_key_exists('type', $data)) {
            $ad->type = array_get($data, 'type');
        }
        if (array_key_exists('seq', $data)) {
            $ad->seq = array_get($data, 'seq');
        }
        if (array_key_exists('status', $data)) {
            $ad->status = array_get($data, 'status');
        }
        return $ad;
    }
}