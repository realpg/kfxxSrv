<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin;

use App\Components\DoctorManager;
use App\Http\Controllers\ApiResponse;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;

class IndexController
{
	//首页
	public function index(Request $request)
	{
		$serverInfo = ServerUtils::getServerInfo();
		$admin = $request->session()->get('admin');
		return view('admin.index.index', ['serverInfo' => $serverInfo, 'admin' => $admin]);
	}
	
	//错误
	public function error(Request $request)
	{
		$data = $request->all();
		$msg = null;
		if (array_key_exists('msg', $data)) {
			$msg = $data['msg'];
		}
		$admin = $request->session()->get('admin');
		return view('admin.index.error500', ['msg' => $msg, 'admin' => $admin]);
	}
	
	public function changePassword(Request $request)
	{
		$admin = $request->session()->get('admin');
		return view('admin.admin.changePassword', ['admin' => $admin]);
	}
	
	public function changePasswordPost(Request $request)
	{
		$data = $request->all();
		$doctor = DoctorManager::getDoctorById($data['admin_id']);
		if (DoctorManager::changePassword($doctor, $data))
			return ApiResponse::makeResponse(true, "修改密码成功", ApiResponse::SUCCESS_CODE);
		else
			return ApiResponse::makeResponse(false, '修改密码失败', ApiResponse::INNER_ERROR);
	}
}