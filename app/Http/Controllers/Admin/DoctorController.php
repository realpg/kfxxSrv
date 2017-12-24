<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Doctoristrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin;

use App\Components\DoctorManager;
use App\Components\QNManager;
use App\Components\UserManager;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Comment\Doc;


class DoctorController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $doctors = DoctorManager::getAllDoctorsByPage();
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.doctor.index', ['admin' => $admin, 'datas' => $doctors, 'upload_token' => $upload_token]);
    }


    //删除管理员
    public function del(Request $request, $id)
    {
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\Doctor\IndexController@error', ['msg' => '合规校验失败，请检查参数管理员id$id']);
        }
        $doctor = DoctorManager::getDoctorById($id);
        $doctor->delete();
        return redirect('/admin/doctor/index');
    }


    //新建或编辑管理员-get
    public function edit(Request $request)
    {
        $data = $request->all();
        $doctor = new Doctor();
        if (array_key_exists('id', $data)) {
            $doctor = DoctorManager::getDoctorById($data['id']);
        }
        $admin = $request->session()->get('admin');
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.doctor.edit', ['admin' => $admin, 'data' => $doctor, 'upload_token' => $upload_token]);
    }

    //新建或编辑管理员->post
    public function editPost(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $doctor = new Doctor();
        //存在id是保存
        if (array_key_exists('id', $data)) {
            $doctor = DoctorManager::getDoctorById($data['id']);
        }
        $doctor = DoctorManager::setDoctor($doctor, $data);
        //如果不存在id代表新建，则默认设置密码
        if (!array_key_exists('id', $data)) {
            $doctor->password = 'afdd0b4ad2ec172c586e2150770fbf9e';  //该password为Aa123456的码
            $doctor->token = UserManager::getGUID();
        }
        $doctor->save();
        return redirect('/admin/doctor/index');
    }

    //搜索，按照手机号、姓名进行搜索
    public static function search(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $search_word = $data['search_word'];
        if (!array_key_exists('nick_name', $data)) {
            $data['nick_name'] = '';
        }//做什么用的？
        $doctors = DoctorManager::searchDoctor($search_word);
        return view('admin.doctor.index', ['admin' => $admin, 'datas' => $doctors]);
    }
}