<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2017/12/14
 * Time: 17:46
<<<<<<< Updated upstream
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LB extends Model{
	use SoftDeletes;    //使用软删除
	protected $table = 't_lb_info';     //表名
	public $timestamps = true;  //自动管理时间戳
	protected $dates = ['deleted_at'];  //软删除
}
