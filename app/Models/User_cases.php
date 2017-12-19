<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2017/12/16
 * Time: 10:41
<<<<<<< Updated upstream
 */
=======
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User_cases extends Model
{
	use SoftDeletes;    //使用软删除
	protected $table = 't_user_cases';     //表名
	public $timestamps = true;  //自动管理时间戳
	protected $dates = ['deleted_at'];  //软删除
	
}
>>>>>>> Stashed changes
