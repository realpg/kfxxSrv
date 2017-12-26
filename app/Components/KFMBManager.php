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
     * 4:级别，计划带采集列表
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
        if (strpos($level, '4') !== false) {
            foreach ($kfmb->jhs as $jh) {
                $jhsjs = self::getJHSJByJHId($jh->id);
                if ($jhsjs) {
                    $jh->jhsjs = self::getJHSJByJHId($jh->id);
                } else {
                    $jh->jhsjs = [];
                }
            }
        }
        return $kfmb;
    }


    /*
     * 根据计划id获取关联的计划数据
     * 
     * By TerryQi
     * 
     * 2017-12-26
     * 
     */
    public static function getJHSJByJHId($jh_id)
    {
        $jhsjs = KFMBJHSJ::where('mbjh_id', '=', $jh_id)->get();
        return $jhsjs;
    }


    /*
     * 获取康复模板计划的采集数据列表信息
     *
     * By TerryQi
     *
     * 2017-12-13
     *
     */
    public static function getJHSJById($id)
    {
        $sjmb = KFMBJHSJ::where('id', '=', $id)->first();
        return $sjmb;
    }


    public static function getMBById($jh_id)
    {
        $jhsjs = KFMBJHSJ::where('mbjh_id', '=', $jh_id)->get();
        foreach ($jhsjs as $jhsj) {
            $jhsj->sjx = SJXManager::getSJXById($jhsj->sjx_id);
        }
        return $jhsjs;
    }

    public static function getSJMBById($id)
    {
        $kfmb = KFMB::where('id', '=', $id)->first;
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
        foreach ($jhs as $jh) {
            $jh->jhsjs = KFMBJHSJ::where('mbjh_id', '=', $jh->id)->get();
            //为计划数据补充数据项信息
            foreach ($jh->jhsjs as $jhsj) {
                $jhsj->sjx = SJXManager::getSJXById($jhsj->sjx_id);
            }
        }
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


    /*
     * 设置康复计划
     *
     * By TerryQi
     *
     * 2017-12-12
     *
     */
    public static function setKFMBJHSJ($kfmbjhsj, $data)
    {
        if (array_key_exists('mbjh_id', $data)) {
            $kfmbjhsj->mbjh_id = array_get($data, 'mbjh_id');
        }
        if (array_key_exists('sjx_id', $data)) {
            $kfmbjhsj->sjx_id = array_get($data, 'sjx_id');
        }
        if (array_key_exists('min_value', $data)) {
            $kfmbjhsj->min_value = array_get($data, 'min_value');
        }
        if (array_key_exists('max_value', $data)) {
            $kfmbjhsj->max_value = array_get($data, 'max_value');
        }
        return $kfmbjhsj;
    }


}