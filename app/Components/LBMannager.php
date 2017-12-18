<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2017/12/14
 * Time: 17:45
 */
namespace App\Components;

use App\Models\LB;


class LBMannager{
	//微信小程序使用，获取可用量表
	public static function lblist(){
			$list=LB::where('status', '=', '1')->orderby('seq', 'desc')->get();
			return $list;
	}
	//管理后台使用，获取所有量表
	public static function lblist_all(){
		$list=Lb::get();
		return $list;
	}
	//根据ID获取量表
	public static function getLBById($id){
		$lb=LB::where('id', '=', $id)->first();
		return $lb;
	}
	
	
}