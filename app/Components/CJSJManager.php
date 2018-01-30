<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\CJSJ;
use App\Models\UserCase;
use Qiniu\Auth;

class CJSJManager
{
    /*
     * 根据用户id获取采集数据
     *
     * By TerryQi
     *
     * 2018-01-19
     *
     */
    public static function getCJSJsByUserId($user_id)
    {
        $cjsjs = CJSJ::where('user_id', '=', $user_id)->orderby('created_at', 'desc')->get();
        return $cjsjs;
    }

    /*
     * 根据用户id获取采集数据-分页
     *
     * By TerryQi
     *
     * 2018-01-19
     *
     */
    public static function getCJSJsByUserIdPaginate($user_id)
    {
        $cjsjs = CJSJ::where('user_id', '=', $user_id)->orderby('created_at', 'desc')->paginate(Utils::PAGE_SIZE);
        return $cjsjs;
    }

    /*
     * 根据病例id获取采集数据信息
     *
     * By TerryQi
     *
     */
    public static function getCJSJsByUserCaseId($userCase_id)
    {
        $cjsjs = CJSJ::where('userCase_id', '=', $userCase_id)->orderby('created_at', 'desc')->get();
        return $cjsjs;
    }

    /*
    * 根据病例id获取采集数据信息-分页
    *
    * By TerryQi
    *
    */
    public static function getCJSJsByUserCaseIdPaginate($userCase_id)
    {
        $cjsjs = CJSJ::where('userCase_id', '=', $userCase_id)->orderby('created_at', 'desc')->paginate(Utils::PAGE_SIZE);
        return $cjsjs;
    }
	
	//根据采集数据id获取采集数据
	public static function getCJSJById($id)
	{
		$cjsj = CJSJ::where('id', '=', $id)->first();
		return $cjsj;
	}
    /*
     * 设置采集数据的详细值
     *
     * By TerryQi
     *
     */
    public static function getCJSJByLevel($cjsj, $level)
    {
        $cjsj->userCase = UserManager::getUserCaseById($cjsj->userCase_id);
        $cjsj->sjx = SJXManager::getSJXById($cjsj->sjx_id);
        $cjsj->hpos=HposManager::getHPosById($cjsj->sjx->hpos_id);
        if($level>=2){
        	$cjsj->user=UserManager::getUserInfoById($cjsj->user_id);
        }
        return $cjsj;
    }


    /*
     * 设置采集数据信息，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setCJSJ($cjsj, $data)
    {
        if (array_key_exists('user_id', $data)) {
            $cjsj->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('userCase_id', $data)) {
            $cjsj->userCase_id = array_get($data, 'userCase_id');
        }
        if (array_key_exists('sjx_id', $data)) {
            $cjsj->sjx_id = array_get($data, 'sjx_id');
        }
        if (array_key_exists('c_pos', $data)) {
            $cjsj->c_pos = array_get($data, 'c_pos');
        }
        if (array_key_exists('c_side', $data)) {
            $cjsj->c_side = array_get($data, 'c_side');
        }
        if (array_key_exists('value', $data)) {
            $cjsj->value = array_get($data, 'value');
        }
        if (array_key_exists('result', $data)) {
            $cjsj->result = array_get($data, 'result');
        }
        if (array_key_exists('attach', $data)) {
            $cjsj->attach = array_get($data, 'attach');
        }

        return $cjsj;
    }
}