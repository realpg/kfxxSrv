<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2017/12/16
 * Time: 10:39
 * <<<<<<< Updated upstream
 */
namespace App\Components;

use App\Models\KFJH;
use App\Models\KFSJ;
use App\Components\XJManager;
use App\Models\UserCase;

class KFJHManager
{
    //获取康复计划
    public static function getKFJHById($u_id)
    {
        $kfjh = KFJH::where('user_id', '=', $u_id)->get();
        return $kfjh;
    }
	///获取康复计划
	public static function getKFJHByBLId($bl_id)
	{
		$kfjh = KFJH::where('userCase_id', '=', $bl_id)->get();
		return $kfjh;
	}
	//根据级别获取病历信息
	//* 0:最简级别，只带量表基本信息
	//* 1:普通级别，带病历
	//* 2:高级级别,带病历详情
	public static function getKFJHByLevel($kfjh, $level)
	{
		if ($level >= 1) {
			$kfjh->bl=self::getBLById($kfjh[0]->user_id);
		}
		if ($level >= 2) {
			$kfjh->bl=self::getBLByLevel($kfjh->bl,'2');
		}
		return $kfjh;
	}

    //获取病历
    public static function getBLById($u_id)
    {
        $bl = UserCase::where('user_id', '=', $u_id)->first();
        return $bl;
    }
	public static function getUsefulBLById($u_id)
	{
		$bl = UserCase::where('user_id', '=', $u_id)->where('status', '=', '1')->first();
		return $bl;
	}
    //根据级别获取病历信息
    //* 0:最简级别，只带量表基本信息
    //* 1:普通级别，带主治医生和康复医师
    //* 2:高级级别,康复计划和采集数据
    public static function getBLByLevel($bl, $level)
    {
        if ($level >= 1) {
            $bl->zz_doctor = DoctorManager::getDoctorById($bl->zz_doctor_id);
            $bl->kf_doctor = DoctorManager::getDoctorById($bl->kf_doctor_id);
        }
        if ($level >= 2) {
            //这里根据id获取康复计划和采集数据
        }
        return $bl;
    }

    //根据id获取数据
    public static function getKFSJById($u_id)
    {
        $sj = KFSJ::where('user_id', '=', $u_id)->get();
        return $sj;
    }

    //根据级别获取康复数据(   待实现）
    //* 0:最简级别，只带数据基本信息
    //* 1:带数据模版
    //* 2:带数据模版和数据ID
    public static function getKFSJByLevel($sj, $level)
    {
        if ($level >= 1) {
            $mb = KFMBManager::getKFMBJHById($sj->sjmb_id);
            $sj->sjmb = $mb;
        }
        if ($level >= 2) {
            if ($sj->sjmb)
                //$sj->sjx = SJXManager::getSJXById($sj->sjmb->sjx_id);
                $sj->sjx = SJXManager::getSJXById($sj->sjmb->sjx_id);
            //这里根据id获取康复计划和采集数据
        }
        return $sj;
    }
}

