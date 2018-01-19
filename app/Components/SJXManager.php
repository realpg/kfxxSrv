<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\SJX;
use App\Models\TWStep;
use App\Models\SJXType;
use Qiniu\Auth;

class SJXManager
{
    /*
     * 根据id获取数据项
     *
     * By TerryQi
     *
     * 2017-11-27
     *
     */
    public static function getSJXById($id)
    {
        $sjx = SJX::where('id', '=', $id)->first();
        return $sjx;
    }

    /*
     * 根据患处位置搜索数据项-分页
     *
     * By TerryQi
     *
     * 2018-01-19
     *
     */
    public static function getSJXsByHPosPaginate($hpos_id)
    {
        $sjxs = SJX::where('hpos_id', '=', $hpos_id)->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $sjxs;
    }

    /*
     * 根据患处位置搜索数据项
     *
     * By TerryQi
     *
     * 2018-01-19
     *
     */
    public static function getSJXsByHPos($hpos_id)
    {
        $sjxs = SJX::where('hpos_id', '=', $hpos_id)->orderby('id', 'desc')->get();
        return $sjxs;
    }

    /*
     * 获取全部数据项-分页
     *
     * By TerryQi
     *
     * 2017-11-27
     *
     */
    public static function getSJXsPaginate()
    {
        $sjx = SJX::orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $sjx;
    }

    /*
     * 获取全部数据项
     *
     * By TerryQi
     *
     * 2017-11-27
     *
     */
    public static function getSJXs()
    {
        $sjx = SJX::orderby('seq', 'desc')->get();
        return $sjx;
    }

    /*
     * 设置数据项
     *
     * By TerryQi
     *
     * 2017-12-11
     *
     */
    public static function setSJX($sjx, $data)
    {
        if (array_key_exists('name', $data)) {
            $sjx->name = array_get($data, 'name');
        }
        if (array_key_exists('hpos_id', $data)) {
            $sjx->hpos_id = array_get($data, 'hpos_id');
        }
        if (array_key_exists('type', $data)) {
            $sjx->type = array_get($data, 'type');
        }
        if (array_key_exists('side', $data)) {
            $sjx->side = array_get($data, 'side');
        }
        if (array_key_exists('is_dis_lr', $data)) {
            $sjx->is_dis_lr = array_get($data, 'is_dis_lr');
        }
        if (array_key_exists('is_dis_pos', $data)) {
            $sjx->is_dis_pos = array_get($data, 'is_dis_pos');
        }
        if (array_key_exists('side', $data)) {
            $sjx->side = array_get($data, 'side');
        }
        if (array_key_exists('desc', $data)) {
            $sjx->desc = array_get($data, 'desc');
        }
        if (array_key_exists('unit', $data)) {
            $sjx->unit = array_get($data, 'unit');
        }
        if (array_key_exists('doctor_id', $data)) {
            $sjx->doctor_id = array_get($data, 'doctor_id');
        }
        if (array_key_exists('seq', $data)) {
            $sjx->seq = array_get($data, 'seq');
        }
        return $sjx;
    }

}