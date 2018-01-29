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
use App\Components\CJSJManager;
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\HposManager;
use App\Components\KFMBManager;
use App\Components\QNManager;
use App\Components\SJXManager;
use App\Components\SurgeryManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Components\XJManager;
use App\Http\Controllers\ApiResponse;
use App\Libs\CommonUtils;
use App\Models\Doctor;
use App\Models\Enter;
use App\Models\SJX;
use App\Models\User;
use App\Models\UserCase;
use App\Models\UserKFJH;
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
            $user = UserManager::setUserAge($user);
            $userCase = UserManager::getTopUserCaseByUserId($user->id);
            if ($userCase) {
                $userCase = UserManager::getUserCaseInfoByLevel($userCase, '0');
            }
            $user->userCase = $userCase;
        }
//        dd($users);
        return view('admin.user.index', ['admin' => $admin, 'datas' => $users]);
    }
	//患者采集数据页面
	public function userCJSJ(Request $request)
	{
		$admin = $request->session()->get('admin');
		$data=$request->all();
		$user_id=$data['user_id'];
		$cjsjs = CJSJManager::getCJSJsByUserId($user_id);
		
		foreach ($cjsjs as $cjsj) {
			$cjsj=CJSJManager::getCJSJByLevel($cjsj,1);
		}
//        dd($users);
		return view('admin.user.userCJSJ', ['admin' => $admin, 'datas' => $cjsjs]);
	}

    //搜索，按照手机号、姓名进行搜索
    public function search(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
//        dd($data);
        $search_word = $data['search_word'];
        $users = UserManager::searchUser($search_word);
        foreach ($users as $user) {
            $user = UserManager::setUserAge($user);
            $userCase = UserManager::getTopUserCaseByUserId($user->id);
            if ($userCase) {
                $userCase = UserManager::getUserCaseInfoByLevel($userCase, '0');
            }
            $user->userCase = $userCase;
        }
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
        } else {
            //如果是新建，根据手机号判断用户是否存在
            $is_user_exist = UserManager::getUserInfoByPhonenum($data['phonenum']);
            if ($is_user_exist) {
                return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '手机号' . $data['phonenum'] . "已经存在，请检索该用户"]);
            }
        }

        $user = UserManager::setUser($user, $data);
        //如果token为空，则设置患者的token
        if (Utils::isObjNull($user->token)) {
            $user->token = UserManager::getGUID();
        }
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
        $kf_doctors = DoctorManager::getDoctorsByRole("1"); //全部康复医师
        $surgerys = SurgeryManager::getAllSurgerys();
        $hposs = HposManager::getHPosList();
        //获取用户病例
        $userCases = UserManager::getUserCasesByUserId($data['user_id']);
        foreach ($userCases as $userCase) {
            $userCase = UserManager::getUserCaseInfoByLevel($userCase, "0");
        }
//        dd($userCases);
        return view('admin.user.userCase', ['admin' => $admin, 'datas' => $userCases, 'user' => $user
            , 'kf_doctors' => $kf_doctors, 'surgerys' => $surgerys, 'hposs' => $hposs]);
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
        $sjxs = SJXManager::getSJXs();
        $kfmbs = KFMBManager::getIndexList($admin->id);

        return view('admin.user.userKFJH', ['admin' => $admin, 'user' => $user
            , 'userCase' => $userCase, 'sjxs' => $sjxs, 'kfmbs' => $kfmbs]);
    }

    /*
     * 编辑患者病例康复计划
     *
     * By TerryQi
     *
     * 2017-12-28
     */
    public function editUserCaseKFJH(Request $request)
    {
        //获取数据，要求ajax设置Content-Type为application/json; charset=utf-8
        $data = $request->all();
//        dd($data);
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //新建/编辑康复模板
        $userCase = UserManager::getUserCaseById($data['id']);
        $userCase = UserManager::getUserCaseInfoByLevel($userCase, "12");
        //根据用户病例id获取全部康复计划
        $ori_jhs = $userCase->jhs;
        //删除原有康复计划以及计划下关联的数据
        foreach ($ori_jhs as $ori_jh) {
            $ori_jh->delete();
        }
        //新建康复计划
        $new_jhs = $data['jhs'];
        foreach ($new_jhs as $new_jh) {
            $jh = new UserKFJH();
            $jh = UserManager::setKFJH($jh, $new_jh);
            $jh->user_id = $userCase->user_id;
            $jh->userCase_id = $userCase->id;
            $jh->save();
        }
        //保存患者病例的康复计划
        $userCase = UserManager::getUserCaseInfoByLevel($userCase, "12");
        return ApiResponse::makeResponse(true, $userCase, ApiResponse::SUCCESS_CODE);
    }


}