<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2017/12/15
 * Time: 10:36
<<<<<<< Updated upstream
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LBQuestion extends Model{
	use SoftDeletes;    //使用软删除
	protected $table = 't_lb_question';     //表名
	public $timestamps = true;  //自动管理时间戳
	protected $dates = ['deleted_at'];  //软删除
}

