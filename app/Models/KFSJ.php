<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2017/12/18
 * Time: 9:45
<<<<<<< Updated upstream
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KFSJ extends Model{
	use SoftDeletes;    //使用软删除
	protected $table = 't_jhsj_info';     //表名
	public $timestamps = true;  //自动管理时间戳
	protected $dates = ['deleted_at'];  //软删除
}

