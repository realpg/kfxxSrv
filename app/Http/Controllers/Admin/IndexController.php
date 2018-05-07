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
use App\Components\QNManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\Doctor;
use App\Models\User;
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
		$admin = $request->session()->get('admin');
		if (DoctorManager::changePassword($doctor, $data))
			return redirect('/admin/index');
		else
			return view('admin.index.error500', ['msg' => "修改密码成功", 'admin' => $admin]);
	}
	
	public function editInfo(Request $request)
	{
		$admin = $request->session()->get('admin');
		$upload_token = QNManager::uploadToken();
		return view("admin.admin.editInfo", ['admin' => $admin, 'upload_token' => $upload_token]);
	}
	
	public function editPost(Request $request)
	{
		$data = $request->all();
//        dd($data);
		$doctor = new Doctor();
		//存在id是保存
		if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
			$doctor = DoctorManager::getDoctorById($data['id']);
		}
		$doctor = DoctorManager::setDoctor($doctor, $data);
		//如果不存在id代表新建，则默认设置密码
		if (!array_key_exists('id', $data) || Utils::isObjNull($data['id'])) {
			return redirect('/admin/index');
		}
		$doctor->save();
		$request->session()->remove('admin');
		$request->session()->put('admin', $doctor);
		return redirect('/admin/editInfo');
	}
	
}