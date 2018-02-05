<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/2/5
 * Time: 9:11
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YJGZ extends Model
{
	use SoftDeletes;    //使用软删除
	protected $table = 't_yjgz_info';     //表名
	public $timestamps = true;  //自动管理时间戳
	protected $dates = ['deleted_at'];  //软删除
}