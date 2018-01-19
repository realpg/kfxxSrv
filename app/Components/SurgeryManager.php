<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\Surgery;
use Qiniu\Auth;

class SurgeryManager
{
    /*
     * 获取手术列表-带分页
     *
     * By TerryQi
     *
     * 2018-1-18
     *
     */
    public static function getSurgeryPaginate()
    {
        $surgerys = Surgery::orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $surgerys;
    }

    /*
     * 根据id获取手术信息
     *
     * By TerryQi
     *
     * 2018-01-18
     */
    public static function getSurgeryById($id)
    {
        $surgery = Surgery::find($id);
        return $surgery;
    }

    /*
     * 获取全部手术
     *
     * By TerryQi
     *
     * 2018-01-18
     *
     */
    public static function getAllSurgerys()
    {
        $surgerys = Surgery::orderby('id', 'desc')->get();
        return $surgerys;
    }

    /*
     * 设置手术信息，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setSurgery($surgery, $data)
    {
        if (array_key_exists('doctor_id', $data)) {
            $surgery->doctor_id = array_get($data, 'doctor_id');
        }
        if (array_key_exists('name', $data)) {
            $surgery->name = array_get($data, 'name');
        }
        if (array_key_exists('hpos_id', $data)) {
            $surgery->hpos_id = array_get($data, 'hpos_id');
        }
        return $surgery;
    }
}