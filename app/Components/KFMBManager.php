<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\KFMB;
use App\Models\Doctor;
use App\Models\KFMBJH;
use App\Models\KFMBJHSJ;
use App\Models\SJX;
use App\Models\TWStep;
use Qiniu\Auth;

class KFMBManager
{
    /*
     * 获取首页康复模板信息
     *
     * By TerryQi
     *
     * 2017-11-27
     *
     * type类型，all:全部 s1:生效 s0:失效
     *
     */
    public static function getKFMBList($type)
    {
        $kfmbs = KFMB::orderby('id', 'desc')->orderby('seq', 'desc');
        switch ($type) {
            case "all":
                break;
            case "s1":
                $kfmbs = $kfmbs->where('status', '=', '1');
                break;
            case "s0":
                $kfmbs = $kfmbs->where('status', '=', '0');
                break;
        }

        $kfmbs = $kfmbs->paginate(Utils::PAGE_SIZE);
        return $kfmbs;
    }

    /*
     * 根据状态获取康复模板
     *
     * By TerryQi
     *
     * 2018-01-19
     *
     */
    public static function getListByStatus($status)
    {
        $kfmbs = KFMB::wherein('status', $status)->paginate(Utils::PAGE_SIZE);
        return $kfmbs;
    }

    /*
     * 为admin.kfmb.index页面提供数据
     *
     * By TerryQi
     *
     * 2018-01-20
     */
    public static function getIndexList($doctor_id)
    {
        $kfmbs = KFMB::wherein('status', [""]);
        $kfmbs = $kfmbs->where('is_personal', '=', '0')->orwhere('doctor_id', '=', $doctor_id)->paginate(Utils::PAGE_SIZE);
        return $kfmbs;
    }

    /*
     * 根据id获取康复模板
     *
     * By TerryQi
     *
     * 2017-12-11
     *
     */
    public static function getKFMBById($id)
    {
        $kfmb = KFMB::where('id', '=', $id)->first();
        return $kfmb;
    }

    /*
     * 根据id获取康复模板计划id
     *
     * By TerryQI
     *
     * 2017-12-11
     *
     */
    public static function getKFMBJHById($id)
    {
        $kfmbjh = KFMBJHSJ::where('id', '=', $id)->first();
        return $kfmbjh;
    }


    /*
     * 获取康复模板信息
     *
     * By TerryQi
     *
     * 2017-12-06
     *
     * 根据level级别不同获取康复模板样式
     * 0:级别，只带康复模板基本信息
     * 1:级别，带录入人员信息
     * 2:级别，带宣教信息
     * 3:级别，带计划列表
     */
    public static function getKFMBInfoByLevel($kfmb, $level)
    {
        if (strpos($level, '0') !== false) {

        }
        if (strpos($level, '1') !== false) {
            $kfmb->doctor = DoctorManager::getDoctorById($kfmb->doctor_id);
        }
        if (strpos($level, '2') !== false) {
            $kfmb->xj = XJManager::getXJInfoById($kfmb->xj_id, 3);
        }
        if (strpos($level, '3') !== false) {
            $kfmb->jhs = self::getJHListByKFMBId($kfmb->id);
        }
        return $kfmb;
    }

    /*
     * 获取康复模板下的计划列表
     *
     * By TerryQi
     *
     * 2017-12-13
     *
     */
    public static function getJHListByKFMBId($kfmb_id)
    {
        $jhs = KFMBJH::where('kfmb_id', '=', $kfmb_id)->orderby('seq', 'asc')->get();
        return $jhs;
    }


    /*
     * 设置康复模板
     *
     * By TerryQi
     *
     * 2017-12-12
     *
     */
    public static function setKFMB($kfmb, $data)
    {
        if (array_key_exists('name', $data)) {
            $kfmb->name = array_get($data, 'name');
        }
        if (array_key_exists('desc', $data)) {
            $kfmb->desc = array_get($data, 'desc');
        }
        if (array_key_exists('doctor_id', $data)) {
            $kfmb->doctor_id = array_get($data, 'doctor_id');
        }
        if (array_key_exists('is_personal', $data)) {
            $kfmb->is_personal = array_get($data, 'is_personal');
        }
        if (array_key_exists('xj_id', $data)) {
            $kfmb->xj_id = array_get($data, 'xj_id');
        }
        if (array_key_exists('status', $data)) {
            $kfmb->status = array_get($data, 'status');
        }
        if (array_key_exists('seq', $data)) {
            $kfmb->seq = array_get($data, 'seq');
        }
        return $kfmb;
    }

    /*
     * 设置康复计划
     *
     * By TerryQi
     *
     * 2017-12-12
     *
     */
    public static function setKFMBJH($kfmbjh, $data)
    {
        if (array_key_exists('kfmb_id', $data)) {
            $kfmbjh->kfmb_id = array_get($data, 'kfmb_id');
        }
        if (array_key_exists('name', $data)) {
            $kfmbjh->name = array_get($data, 'name');
        }
        if (array_key_exists('desc', $data)) {
            $kfmbjh->desc = array_get($data, 'desc');
        }
        if (array_key_exists('seq', $data)) {
            $kfmbjh->seq = array_get($data, 'seq');
        }
        if (array_key_exists('btime_type', $data)) {
            $kfmbjh->btime_type = array_get($data, 'btime_type');
        }
        if (array_key_exists('start_time', $data)) {
            $kfmbjh->start_time = array_get($data, 'start_time');
        }
        if (array_key_exists('start_unit', $data)) {
            $kfmbjh->start_unit = array_get($data, 'start_unit');
        }
        if (array_key_exists('end_time', $data)) {
            $kfmbjh->end_time = array_get($data, 'end_time');
        }
        if (array_key_exists('end_unit', $data)) {
            $kfmbjh->end_unit = array_get($data, 'end_unit');
        }
        if (array_key_exists('xj_ids', $data)) {
            $kfmbjh->xj_ids = array_get($data, 'xj_ids');
        }
        return $kfmbjh;
    }

}