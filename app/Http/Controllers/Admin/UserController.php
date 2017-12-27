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
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\KFMBManager;
use App\Components\QNManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Components\XJManager;
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
        $users = UserManager::setUsersAge($users);  //设置用户年龄
        foreach ($users as $user) {
            $userCase = UserManager::getTopUserCaseByUserId($user->id);
            if ($userCase) {
                $userCase = UserManager::getUserCaseInfoByLevel($userCase, '0');
            }
            $user->userCase = $userCase;
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
    public function userCaseIndex(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        //获取页面基础数据
        $user = UserManager::getUserInfoById($data['user_id']); //需要编辑的患者信息
        $user = UserManager::setUserAge($user);
//        dd($user);
        $zz_doctors = DoctorManager::getDoctorsByRole("0"); //全部主治医师
        $kf_doctors = DoctorManager::getDoctorsByRole("1"); //全部康复医师
        $kfmbs = KFMBManager::getKFMBList("s1");    //生效康复模板
        $xj_types = XJManager::getXJTypes();
        //获取用户病例
        $userCases = UserManager::getUserCaseByUserId($data['user_id']);
        foreach ($userCases as $userCase) {
            $userCase = UserManager::getUserCaseInfoByLevel($userCase, "0");
        }
//        dd($userCases);
        return view('admin.user.userCase', ['admin' => $admin, 'datas' => $userCases, 'user' => $user
            , 'zz_doctors' => $zz_doctors, 'kf_doctors' => $kf_doctors, 'kfmbs' => $kfmbs, 'xj_types' => $xj_types]);
    }

    /*
     * 新建用户病例-post
     *
     * By TerryQi
     *
     * 2017-12-20
     *
     */
    public function editUserCasePost(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $useCase = new UserCase();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $useCase = UserManager::getUserCaseById($data['id']);
        }
        $useCase = UserManager::setUserCase($useCase, $data);
//        $useCase
        $useCase->save();

        return redirect('/admin/user/userCaseIndex?user_id=' . $useCase->user_id);
    }

    /*
     *
     *
     *
     */
    public function userKFJH(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();

        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'userCase_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }

        $userCase = UserManager::getUserCaseById($data['userCase_id']);
        $userCase = UserManager::getUserCaseInfoByLevel($userCase, "0");
        $user = UserManager::getUserInfoById($userCase->user_id);
        $user->age = Utils::getAge($user->birthday);

        return view('admin.user.userKFJH', ['admin' => $admin, 'user' => $user, 'userCase' => $userCase]);
    }


}