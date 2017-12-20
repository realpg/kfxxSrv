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
     * 获取全部数据项
     *
     * By TerryQi
     *
     * 2017-11-27
     *
     */
    public static function getSJXs()
    {
        $sjx = SJX::orderby('seq', 'asc')->paginate(Utils::PAGE_SIZE);
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