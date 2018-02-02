<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\KFJH;
use App\Models\User;
use App\Models\UserCase;
use App\Models\UserKFJH;
use App\Models\Vertify;
use App\Models\ZXJH;
use GuzzleHttp\Psr7\Request;

class UserManager
{
	
	/*
	 * 根据id获取用户信息，带token
	 *
	 * By TerryQi
	 *
	 * 2017-09-28
	 */
	public static function getUserInfoByIdWithToken($id)
	{
		$user = User::where('id', '=', $id)->first();
		return $user;
	}
	
	/*
	 * 根据id获取用户信息
	 *
	 * By TerryQi
	 *
	 * 2017-09-28
	 */
	public static function getUserInfoById($id)
	{
		$user = self::getUserInfoByIdWithToken($id);
		if ($user) {
			$user->token = null;
		}
		return $user;
	}
	
	/*
	 * 根据id获取用户信息
	 *
	 * By TerryQi
	 *
	 * 2017-09-28
	 */
	public static function getUserInfoByPhonenum($phonenum)
	{
		$user = User::where('phonenum', '=', $phonenum)->first();
		if ($user) {
			$user->token = null;
		}
		return $user;
	}
	
	/*
	 * 根据user_code和token校验合法性，全部插入、更新、删除类操作需要使用中间件
	 *
	 * By TerryQi
	 *
	 * 2017-09-14
	 *
	 * 返回值
	 *
	 */
	public static function ckeckToken($id, $token)
	{
		//根据id、token获取用户信息
		$count = User::where('id', '=', $id)->where('token', '=', $token)->count();
		if ($count > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * 用户登录
	 *
	 * By TerryQi
	 *
	 * 2017-09-28
	 */
	public static function login($data)
	{
		//获取account_type，后续进行登录类型判断
		$account_type = $data['account_type'];
		// 判断小程序，按照类型查询
		if ($account_type === 'xcx') {
			$user = self::getUserByXCXOpenId($data['xcx_openid']);
			//存在用户即返回用户信息
			if ($user != null) {
				return $user;
			}
		}
		//不存在即新建用户
		return self::register($data);
	}
	
	/*
	 * 配置用户信息，用于更新用户信息和新建用户信息
	 *
	 * By TerryQi
	 *
	 * 2017-09-28
	 *
	 */
	public static function setUser($user, $data)
	{
		if (array_key_exists('real_name', $data)) {
			$user->real_name = array_get($data, 'real_name');
		}
		if (array_key_exists('nick_name', $data)) {
			$user->nick_name = array_get($data, 'nick_name');
		}
		if (array_key_exists('birthday', $data)) {
			$user->birthday = array_get($data, 'birthday');
		}
		if (array_key_exists('avatar', $data)) {
			$user->avatar = array_get($data, 'avatar');
		}
		if (array_key_exists('phonenum', $data)) {
			$user->phonenum = array_get($data, 'phonenum');
		}
		if (array_key_exists('xcx_openid', $data)) {
			$user->xcx_openid = array_get($data, 'xcx_openid');
		}
		if (array_key_exists('unionid', $data)) {
			$user->unionid = array_get($data, 'unionid');
		}
		if (array_key_exists('gender', $data)) {
			$user->gender = array_get($data, 'gender');
		}
		if (array_key_exists('status', $data)) {
			$user->status = array_get($data, 'status');
		}
		if (array_key_exists('type', $data)) {
			$user->type = array_get($data, 'type');
		}
		if (array_key_exists('province', $data)) {
			$user->province = array_get($data, 'province');
		}
		if (array_key_exists('city', $data)) {
			$user->city = array_get($data, 'city');
		}
		return $user;
	}
	
	/*
	 * 配置用户病例信息
	 *
	 * By TerryQi
	 *
	 * 2017-12-19
	 *
	 */
	public static function setUserCase($userCase, $data)
	{
		if (array_key_exists('user_id', $data)) {
			$userCase->user_id = array_get($data, 'user_id');
		}
		if (array_key_exists('doctor_id', $data)) {
			$userCase->doctor_id = array_get($data, 'doctor_id');
		}
		if (array_key_exists('desc', $data)) {
			$userCase->desc = array_get($data, 'desc');
		}
		if (array_key_exists('surgery_id', $data)) {
			$userCase->surgery_id = array_get($data, 'surgery_id');
		}
		if (array_key_exists('ss_time', $data)) {
			$userCase->ss_time = array_get($data, 'ss_time');
		}
		if (array_key_exists('hpos_id', $data)) {
			$userCase->hpos_id = array_get($data, 'hpos_id');
		}
		if (array_key_exists('side', $data)) {
			$userCase->side = array_get($data, 'side');
		}
		if (array_key_exists('kf_doctor_id', $data)) {
			$userCase->kf_doctor_id = array_get($data, 'kf_doctor_id');
		}
		if (array_key_exists('ss_time', $data)) {
			$userCase->ss_time = array_get($data, 'ss_time');
		}
		return $userCase;
	}
	
	/*
	 * 注册用户
	 *
	 * By TerryQi
	 *
	 * 2017-09-28
	 *
	 */
	public static function register($data)
	{
		$user = self::getUserInfoByPhonenum($data['phonenum']);
		//创建用户信息
		if (!$user)
			$user = new User;
		$user = self::setUser($user, $data);
		$user->token = self::getGUID();
		$user->save();
		$user = self::getUserInfoByIdWithToken($user->id);
		return $user;
	}
	
	/*
	 * 更新用户信息
	 *
	 * By TerryQi
	 *
	 * 2017-09-28
	 *
	 */
	public static function updateUser($data)
	{
		//配置用户信息
		$user = self::getUserInfoByIdWithToken($data['user_id']);
//	    $user = self::getUserInfoById($data['user_id']);
		$user = self::setUser($user, $data);
		$user->save();
		return $user;
	}
	
	
	/*
	 * 根据用户openid获取用户信息
	 *
	 * By TerryQi
	 *
	 * 2017-09-28
	 */
	public static function getUserByXCXOpenId($openid)
	{
		$user = User::where('xcx_openid', '=', $openid)->first();
		return $user;
	}
	
	
	// 生成guid
	/*
	 * 生成uuid全部用户相同，uuid即为token
	 *
	 */
	public static function getGUID()
	{
		if (function_exists('com_create_guid')) {
			return com_create_guid();
		} else {
			mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			
			$uuid = substr($charid, 0, 8)
				. substr($charid, 8, 4)
				. substr($charid, 12, 4)
				. substr($charid, 16, 4)
				. substr($charid, 20, 12);
			return $uuid;
		}
	}
	
	/*
	 * 生成验证码
	 *
	 * By TerryQi
	 */
	public static function sendVertify($phonenum)
	{
		$vertify_code = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);  //生成4位验证码
		$vertify = new Vertify;
		$vertify->phonenum = $phonenum;
		$vertify->code = $vertify_code;
		$vertify->save();
		/*
		 * 预留，需要触发短信端口进行验证码下发
		 */
		if ($vertify) {
			SMSManager::sendSMSVerification($phonenum, $vertify_code);
			return true;
		}
		return false;
	}
	
	/*
	 * 校验验证码
	 *
	 * By TerryQi
	 *
	 * 2017-11-28
	 */
	public static function judgeVertifyCode($phonenum, $vertify_code)
	{
		$vertify = Vertify::where('phonenum', '=', $phonenum)
			->where('code', '=', $vertify_code)->where('status', '=', '0')->first();
		if ($vertify) {
			//验证码置为失效
			$vertify->status = '1';
			$vertify->save();
			return true;
		} else {
			return false;
		}
	}
	
	
	/*
	 * 获取全部用户信息
	 *
	 * By TerryQi
	 *
	 * 2017-12-19
	 *
	 */
	public static function getAllUsers()
	{
		$users = User::orderby('id', 'desc')->get();
		return $users;
	}
	
	/*
	 * 设置用户的年龄，单个用户
	 *
	 * By TerryQi
	 *
	 * 2017-12-19
	 *
	 */
	public static function setUserAge($user)
	{
		if ($user->birthday) {
			$user->age = Utils::getAge($user->birthday);
		}
		return $user;
	}
	
	/*
	 * 根据级别不同获取用户的具体病例信息
	 *
	 * By TerryQi
	 *
	 * 2017-12-19
	 *
	 * level：获取级别，暂无
	 *
	 * level:0：带基本信息
	 *       1：带康复计划列表
	 *       2：获取康复计划详情
	 *
	 *
	 */
	public static function getUserCaseInfoByLevel($userCase, $level)
	{
		//0级获取用户信息
		if (strpos($level, '0') !== false) {
//            作废
//            if ($userCase->zz_doctor_id) {
//                $userCase->zz_doctor = DoctorManager::getDoctorById($userCase->zz_doctor_id);
//            }
			if ($userCase->kf_doctor_id) {
				$userCase->kf_doctor = DoctorManager::getDoctorById($userCase->kf_doctor_id);
			}
			if ($userCase->surgery_id) {
				$userCase->surgery = SurgeryManager::getSurgeryById($userCase->surgery_id);
			}
			if ($userCase->hpos_id) {
				$userCase->hpos = HposManager::getHPosById($userCase->hpos_id);
			}
		}
		//1级获取康复计划信息
		if (strpos($level, '1') !== false) {
			$userCase->jhs = self::getUserKFJHByCaseId($userCase->id);
		}
		return $userCase;
	}
	
	
	/*
	 * 根据用户病例获取康复计划列表
	 *
	 * By TerryQi
	 *
	 * 2017-1-28
	 *
	 */
	public static function getUserKFJHByCaseId($userCase_id)
	{
		$jhs = UserKFJH::where('userCase_id', '=', $userCase_id)->orderby('seq', 'asc')->get();
		return $jhs;
	}
	
	/*
	 * 根据用户id获取病例，即获取该用户的全部病例
	 *
	 * By TerryQi
	 *
	 * 2017-12-19
	 *
	 */
	public static function getUserCasesByUserId($user_id)
	{
		$userCases = UserCase::where('user_id', '=', $user_id)->orderby('id', 'desc')->get();
		return $userCases;
	}
	
	/*
	 * 获取用户的最新的病例信息
	 *
	 * By TerryQi
	 *
	 * 2017-12-19
	 */
	public static function getTopUserCaseByUserId($user_id)
	{
		$userCase = UserCase::where('user_id', '=', $user_id)->orderby('id', 'desc')->first();
		return $userCase;
	}
	
	/*
	 * 根据id获取用户病例
	 *
	 * By TerryQi
	 *
	 * 2017-12-19
	 *
	 * level级别
	 */
	public static function getUserCaseById($userCase_id)
	{
		$userCase = UserCase::where('id', '=', $userCase_id)->first();
		return $userCase;
	}
	
	
	/*
	 * 搜索用户信息
	 *
	 * By TerryQi
	 *
	 * 2017-12-19
	 *
	 */
	public static function searchUser($search_word)
	{
		$users = User::where('real_name', 'like', '%' . $search_word . '%')
			->orwhere('phonenum', 'like', '%' . $search_word . '%')->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
		return $users;
	}
	
	//设置用户病例康复计划信息
	public static function setKFJH($kfjh, $data)
	{
		if (array_key_exists('user_id', $data)) {
			$kfjh->user_id = array_get($data, 'user_id');
		}
		if (array_key_exists('userCase_id', $data)) {
			$kfjh->userCase_id = array_get($data, 'userCase_id');
		}
		if (array_key_exists('name', $data)) {
			$kfjh->name = array_get($data, 'name');
		}
		if (array_key_exists('desc', $data)) {
			$kfjh->desc = array_get($data, 'desc');
		}
		
		if (array_key_exists('important', $data)) {
			$kfjh->important = array_get($data, 'important');
		}
		if (array_key_exists('seq', $data)) {
			$kfjh->seq = array_get($data, 'seq');
		}
		if (array_key_exists('btime_type', $data)) {
			$kfjh->btime_type = array_get($data, 'btime_type');
		}
		if (array_key_exists('start_time', $data)) {
			$kfjh->start_time = array_get($data, 'start_time');
		}
		if (array_key_exists('start_unit', $data)) {
			$kfjh->start_unit = array_get($data, 'start_unit');
		}
		if (array_key_exists('end_time', $data)) {
			$kfjh->end_time = array_get($data, 'end_time');
		}
		if (array_key_exists('end_unit', $data)) {
			$kfjh->end_unit = array_get($data, 'end_unit');
		}
		
		if (array_key_exists('set_date', $data)) {
			$kfjh->set_date = array_get($data, 'set_date');
		}
		if (array_key_exists('xj_ids', $data)&&array_get($data, 'xj_ids')!=null) {
			$kfjh->xj_ids = array_get($data, 'xj_ids');
		}
		return $kfjh;
	}
	
	
	//处理过期的康复计划
	/*
	 * 遍历全量为0或者1的康复计划，当已经超过了执行时间，则将status==2,
	 *
	 * By TerryQi
	 *
	 */
	public static function handleToFinisheKFJH($kfjh)
	{
		$userCase = self::getUserCaseById($kfjh->userCase_id);
		//如果未找到患者病历，则返回false
		if (!$userCase) {
			$kfjh->status = "2";        //康复计划设置为已经完成
			$kfjh->save();
			return false;
		}
		//存在患者病历，则根据btime_type获取btime，即为康复计划的基线日期
		$btime = self::getKFJHBtime($kfjh, $userCase);
		//如果得到基线时间，可以继续向下执行
		if ($btime) {
			$end_time = self::getKFJHEndTime($kfjh, $btime);
			$today = DateTool::getToday();
			$diff = DateTool::dateDiff('D', $end_time, $today);
			//若diff>0，则应该结束执行计划
			if ($diff > 0) {
				//则应结束康复计划
				$kfjh->status = "2";
				$kfjh->save();
			}
		}
	}
	
	
	//处理计划执行的康复计划
	/*
	 * 根据kfjh获取开始执行的时间，如果没有开始时间，则返回false（例如还没有设置ss_time和wt_time等）
	 *
	 * By TerryQi
	 *
	 */
	public static function handleStartKFJH($kfjh)
	{
		$userCase = self::getUserCaseById($kfjh->userCase_id);
		//如果未找到患者病历，则返回false
		if (!$userCase) {
			$kfjh->status = "2";        //康复计划设置为已经完成
			$kfjh->save();
			return false;
		}
		//存在患者病历，则根据btime_type获取btime，即为康复计划的基线日期
		$btime = self::getKFJHBtime($kfjh, $userCase);
		//如果得到了基线时间，可以继续向下执行
		if ($btime) {
			$start_time = self::getKFJHStartTime($kfjh, $btime);
			$today = DateTool::getToday();
			$diff = DateTool::dateDiff('D', $start_time, $today);
			//若diff>=0，则应该开始执行计划
			if ($diff >= 0) {
				//康复计划状态变为执行中
				$kfjh->status = "1";
				$kfjh->save();
				//生成患者的执行计划
				if (!ZXJHManager::isZXJHExist($kfjh, DateTool::getToday())) {
					ZXJHManager::createZXJH($kfjh);
					//进行通知
					NoticeManager::sendNotice($userCase->user_id, 'user', '您有新的康复计划，请按照完成');
				}
			}
		}
	}
	
	/*
	 * 处理执行中的康复计划
	 *
	 * 根据kfjh获取执行结束的时间，若没到结束时间，则生成执行计划，若到了结束时间，则将康复计划状态置为2，并且不再生成康复计划
	 *
	 * By TerryQi
	 */
	public static function handleExecutingKFJH($kfjh)
	{
		$userCase = self::getUserCaseById($kfjh->userCase_id);
		//如果未找到患者病历，则返回false
		if (!$userCase) {
			$kfjh->status = "2";        //康复计划设置为已经完成
			$kfjh->save();
			return false;
		}
		//存在患者病历，则根据btime_type获取btime，即为康复计划的基线日期
		$btime = self::getKFJHBtime($kfjh, $userCase);
		//如果得到基线时间，可以继续向下执行
		if ($btime) {
			$end_time = self::getKFJHEndTime($kfjh, $btime);
			$today = DateTool::getToday();
			$diff = DateTool::dateDiff('D', $end_time, $today);
			//若diff>0，则应该结束执行计划
			if ($diff <= 0) {
				//生成患者的执行计划
				if (!ZXJHManager::isZXJHExist($kfjh, DateTool::getToday())) {
					ZXJHManager::createZXJH($kfjh);
					//进行通知
					NoticeManager::sendNotice($userCase->user_id, 'user', '您有新的康复计划，请按照完成');
				}
			}
		}
	}
	
	
	
	
	
	//获取基线日期
	/*
	 * 根据kfjh和userCase获取基线日期，如果还未设置，则返回false（例如还没有设置ss_time和wt_time等）
	 *
	 * By TerryQi
	 */
	public static function getKFJHBtime($kfjh, $userCase)
	{
		$btime = null;
		if (Utils::isObjNull($kfjh) || Utils::isObjNull($userCase)) {
			return false;
		}
		switch ($kfjh->btime_type) {
			case "0":   //手术后
				if (Utils::isObjNull($userCase->ss_time)) {   //如果手术时间为空，则返回false
					return false;
				}
				$btime = $userCase->ss_time;
				break;
			case "1":   //首次弯腿后
				if (Utils::isObjNull($userCase->wt_time)) {     //如果首次弯腿时间为空，则返回false
					return false;
				}
				$btime = $userCase->wt_time;
			case "2":   //制定日期
				if (Utils::isObjNull($kfjh->set_date)) {
					return false;
				}
				$btime = $kfjh->set_date;
		}
		return $btime;
	}
	
	//根据基线日期获取康复计划的开始执行日期
	/*
	 * By TerryQi
	 *
	 * 2017-12-31
	 */
	public static function getKFJHStartTime($kfjh, $btime)
	{
		$start_time = null;
		$start_o_time = $kfjh->start_time;
		if ($start_o_time == 0) {
			$start_o_time = 1;
		}
		switch ($kfjh->btime_type) {
			case "0":   //手术后
				$days = Utils::computeDaysByUnit(($start_o_time - 1), $kfjh->start_unit) + 1;
				$start_time = DateTool::dateAdd('D', $days, $btime, 'Y-m-d');
				break;
			case "1":   //首次弯腿后
				$days = Utils::computeDaysByUnit(($start_o_time - 1), $kfjh->start_unit) + 1;
				$start_time = DateTool::dateAdd('D', $days, $btime, 'Y-m-d');
				break;
			case "2":   //指定日期
				$start_time = $kfjh->set_date;
				break;
		}
		return $start_time;
	}
	
	//根据基线日期获取康复计划的结束日期
	public static function getKFJHEndTime($kfjh, $btime)
	{
		$end_time = null;
		switch ($kfjh->btime_type) {
			case "0":   //手术后
				$days = Utils::computeDaysByUnit($kfjh->end_time, $kfjh->end_unit);
				$end_time = DateTool::dateAdd('D', $days, $btime, 'Y-m-d');
				break;
			case "1":   //首次弯腿后
				$days = Utils::computeDaysByUnit($kfjh->start_time, $kfjh->end_unit);
				$end_time = DateTool::dateAdd('D', $days, $btime, 'Y-m-d');
				break;
			case "2":   //指定日期
				$end_time = $kfjh->set_date;
				break;
		}
		return $end_time;
	}
	
	
	/*
	 * 根据状态生成获取用户的康复计划列表信息
	 *
	 * By TerryQi
	 *
	 * 2017-12-31
	 *
	 * $status为数组，即["0"]\["0","1"]
	 *
	 */
	public static function getKFJHListByStatus($status)
	{
		$kfjhs = KFJH::whereIn('status', $status)->get();
		return $kfjhs;
	}
	
	
}