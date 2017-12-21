<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\Doctor;
use Qiniu\Auth;

class DoctorManager
{

    /*
     * 获取全部医生信息
     *
     * By TerryQi
     *
     */
    public static function getAllDoctorsByPage()
    {
        $doctors = Doctor::orderBy('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $doctors;
    }

    /*
     * 根据id获取医生信息
     *
     * By TerryQi
     *
     */
    public static function getDoctorById($id)
    {
        $doctor = self::getDoctorByIdWithToken($id);
        if ($doctor) {
            $doctor->token = "";
        }
        return $doctor;
    }

    /*
     * 根据类型获取医生列表
     *
     * By TerryQi
     *
     */
    public static function getDoctorsByRole($role)
    {
        $doctors = Doctor::orderby('id', 'asc');
        if ($role == "all") {

        } else {
            $doctors = $doctors->where('role', '=', $role);
        }
        return $doctors->get();
    }

    /*
     * 根据id获取医生信息（带token）
     *
     * By TerryQi
     */
    public static function getDoctorByIdWithToken($id)
    {
        $doctor = Doctor::where('id', '=', $id)->first();
        return $doctor;
    }


    /*
     * 设置管理员信息，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setDoctor($doctor, $data)
    {
        if (array_key_exists('name', $data)) {
            $doctor->name = array_get($data, 'name');
        }
        if (array_key_exists('token', $data)) {
            $doctor->token = array_get($data, 'token');
        }
        if (array_key_exists('avatar', $data)) {
            $doctor->avatar = array_get($data, 'avatar');
        }
        if (array_key_exists('phonenum', $data)) {
            $doctor->phonenum = array_get($data, 'phonenum');
        }
        if (array_key_exists('password', $data)) {
            $doctor->password = array_get($data, 'password');
        }
        if (array_key_exists('duty', $data)) {
            $doctor->duty = array_get($data, 'duty');
        }
        if (array_key_exists('dep', $data)) {
            $doctor->dep = array_get($data, 'dep');
        }
        if (array_key_exists('role', $data)) {
            $doctor->role = array_get($data, 'role');
        }
        if (array_key_exists('gender', $data)) {
            $doctor->gender = array_get($data, 'gender');
        }
        return $doctor;
    }
	
	public static function searchDoctor($search_word)
	{
		$users = Doctor::where('name', 'like', '%' . $search_word . '%')
			->orwhere('phonenum', 'like', '%' . $search_word . '%')->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
		return $users;
	}
}