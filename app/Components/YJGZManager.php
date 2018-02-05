<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/2/5
 * Time: 9:24
 */

namespace App\Components;

use App\Models\YJGZ;

class YJGZManager
{
	public static function getAll(){
		$YJGZs = YJGZ::get();
		return $YJGZs;
	}
	
	public static function getYJGZbyId($id){
		$yjgz=YJGZ::where('id','=',$id)->first();
		return $yjgz;
	}
	public static function getYJGZbySJXid($sjx_id)
	{
		$YJGZs = YJGZ::where('sjx_id', '=', $sjx_id)->get();
		return $YJGZs;
	}
	
	public static function getYJ($sj,$YJGZs)
	{
		$result = 0;
		foreach ($YJGZs as $YJGZ) {
			$values = explode(',', $sj->value);
			if ($YJGZ->type == 0) {
				foreach ($values as $value) {
					if ($value > $YJGZ->max_value || $value < $YJGZ->min_value) {
						$result = 1;
					}
				}
			} else if ($YJGZ->type == 1) {
				$values = explode(',', $sj->value);
				$value = abs($values[1] - $values[0]);
				if ($value > $YJGZ->max_value || $value < $YJGZ->min_value) {
					$result = 1;
				}
			} else if ($YJGZ->type == 2){
				//多次对比
			}
		}
		$sj->result = $result;
		return $sj;
	}
	public static function setYJGZ($yjgz,$data){
		if (array_key_exists('sjx_id', $data)) {
			$yjgz->sjx_id = array_get($data, 'sjx_id');
		}
		if (array_key_exists('type', $data)) {
			$yjgz->type = array_get($data, 'type');
		}
		if (array_key_exists('max_value', $data)) {
			$yjgz->max_value = array_get($data, 'max_value');
		}
		if (array_key_exists('min_value', $data)) {
			$yjgz->min_value = array_get($data, 'min_value');
		}
		if (array_key_exists('trend', $data)) {
			$yjgz->trend = array_get($data, 'trend');
		}
		return $yjgz;
	}
}