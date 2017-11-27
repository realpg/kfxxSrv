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
use App\Components\QNManager;
use App\Libs\CommonUtils;
use App\Models\Enter;
use App\Models\User;
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
        $users = User::orderBy('id', 'desc')->get();
        return view('admin.user.index', ['admin' => $admin, 'datas' => $users]);
    }

    //搜索-按照昵称
    public function search(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        if (!array_key_exists('nick_name', $data)) {
            $data['nick_name'] = '';
        }
        $users = User::where('nick_name', 'like', "%" . $data['nick_name'] . "%")->get();
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

}