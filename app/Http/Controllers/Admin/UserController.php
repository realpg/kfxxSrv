<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin;

use App\Components\ADManager;
use App\Components\DoctorManager;
use App\Components\KFMBManager;
use App\Components\QNManager;
use App\Components\UserManager;
use App\Libs\CommonUtils;
use App\Models\Doctor;
use App\Models\Enter;
use App\Models\User;
use App\Models\UserCase;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class UserController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $users = UserManager::getAllUsers();
        foreach ($users as $user) {
            $user->useCase = UserManager::getUserCaseByLevel(0, $user->id);
        }
//        dd($users);
        return view('admin.user.index', ['admin' => $admin, 'datas' => $users]);
    }

    //搜索，按照手机号、姓名进行搜索
    public function search(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
//        dd($data);
        $search_word = $data['search_word'];
        if (!array_key_exists('nick_name', $data)) {
            $data['nick_name'] = '';
        }
        $users = UserManager::searchUser($search_word);
        return view('admin.user.index', ['admin' => $admin, 'datas' => $users]);
    }


    //用户信息详情
    public function info(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');

        $user = User::where('id', '=', $data['id'])->get()->first();
        if ($user == null) {
            return redirect('/admin/enter/index');
        }
        $enters = Enter::where('user_id', '=', $user->id)->get();
        return view('admin.user.info', ['admin' => $admin, 'datas' => $user, 'enters' => $enters]);
    }


    //新建、编辑患者信息-post
    public function editPost(Request $request)
    {
        $data = $request->all();
        $user = new User();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $user = UserManager::getUserInfoById($data['id']);
        }
        $user = UserManager::setUser($user, $data);
        $user->save();
        return redirect('/admin/user/index');
    }

    //新建编辑患者病例
    /*
     * By TerryQi
     *
     * 2017-12-19
     *
     */
    public function editUserCase(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        //获取用户病例
        $userCase = UserManager::getTopUserCaseByUserId($data['user_id']);
        if ($userCase) {
            $userCase->user = UserManager::getUserInfoById($userCase->user_id);
            $userCase->zz_doctor = DoctorManager::getDoctorById($userCase->zz_doctor_id);
            $userCase->kf_doctor = DoctorManager::getDoctorById($userCase->kf_doctor_id);
            $userCase->kfmb = KFMBManager::getKFMBById($userCase->kfmb_id);
        }

        $user = UserManager::getUserInfoById($data['user_id']); //需要编辑的患者信息
        $zz_doctors = DoctorManager::getDoctorsByRole("0"); //全部主治医师
        $kf_doctors = DoctorManager::getDoctorsByRole("1"); //全部康复医师
        $kfmbs = KFMBManager::getKFMBList("s1");    //生效康复模板

        return view('admin.user.editUserCase', ['admin' => $admin, 'data' => $userCase, 'user' => $user
            , 'zz_doctors' => $zz_doctors, 'kf_doctors' => $kf_doctors, 'kfmbs' => $kfmbs]);
    }

}