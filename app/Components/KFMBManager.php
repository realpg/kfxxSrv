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

        $kfmbs = $kfmbs->paginate(10);
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
        $kfmbjh = KFMBJH::where('id', '=', $id)->first();
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
     * 0:最简级别，只带康复模板基本信息
     * 1:基本级别，带录入人员信息
     * 2:中级级别，带宣教信息
     * 3:高级级别，带计划列表
     * 4:高级级别，计划带宣教列表
     * 5:高级级别，计划带采集列表
     */
    public static function getKFMBInfoByLevel($kfmb, $level)
    {
        if ($level >= 1) {
            $kfmb->doctor = DoctorManager::getDoctorById($kfmb->doctor_id);
        }
        if ($level >= 2) {
            $kfmb->steps = self::getStepsByKFMBId($kfmb->id);
        }
        if ($level >= 3) {
            $kfmb->jhs = self::getJHListByKFMBId($kfmb->id);
        }
        if ($level >= 4) {
            foreach ($kfmb->jhs as $jh) {
                if ($jh->xj_ids != null) {
                    $jh->xj = XJManager::getXJById($jh->xj_ids);
                }
            }
        }
        if ($level >= 5) {
            foreach ($kfmb->jhs as $jh) {
                $jh->jhsjs = self::getJHSJByJHId($jh->id);
            }
        }
        return $kfmb;
    }


    /*
     * 获取康复模板计划的采集数据列表信息
     *
     * By TerryQi
     *
     * 2017-12-13
     *
     */
    public static function getJHSJByJHId($jh_id)
    {
        $jhsjs = KFMBJHSJ::where('mbjh_id', '=', $jh_id)->get();
        foreach ($jhsjs as $jhsj) {
            $jhsj->sj = self::getSJById($jhsj->sj_id);
        }
        return $jhsjs;
    }


    /*
     * 根据id获取数据项
     *
     * By TerryQi
     *
     * 2017-12-13
     *
     */
    public static function getSJById($id)
    {
        $sj = SJXManager::getSJXById($id);
        return $sj;
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
     * 根据康复模板id获取图文信息
     *
     * By TerryQi
     *
     * 2017-12-12
     *
     */
    public
    static function getStepsByKFMBId($kfmb_id)
    {
        $tw_steps = TWStep::where('f_table', '=', 'kfmb')->where('f_id', '=', $kfmb_id)->get();
        return $tw_steps;
    }


    /*
     * 根据图文id获取图文信息
     *
     * By TerryQi
     *
     * 2017-12-12
     *
     */
    public
    static function getTWById($tw_id)
    {
        $tw = TWStep::where('id', '=', $tw_id)->first();
        return $tw;
    }


    /*
     * 设置康复模板
     *
     * By TerryQi
     *
     * 2017-12-12
     *
     */
    public
    static function setKFMB($kfmb, $data)
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
    public
    static function setKFMBJH($kfmbjh, $data)
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
        if (array_key_exists('btime', $data)) {
            $kfmbjh->btime = array_get($data, 'btime');
        }
        if (array_key_exists('start_time', $data)) {
            $kfmbjh->start_time = array_get($data, 'start_time');
        }
        if (array_key_exists('end_time', $data)) {
            $kfmbjh->end_time = array_get($data, 'end_time');
        }
        if (array_key_exists('xj_ids', $data)) {
            $kfmbjh->xj_ids = array_get($data, 'xj_ids');
        }
        return $kfmbjh;
    }


}