<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin;

use App\Components\AdminManager;
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\QNManager;
use App\Components\SJXManager;
use App\Components\XJManager;
use App\Models\AD;
use App\Models\Admin;
use App\Models\SJX;
use App\Models\XJ;
use App\Models\TWStep;
use App\Models\XJType;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class SJXController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $sjxs = SJXManager::getSJXs();
        foreach ($sjxs as $sjx) {
            $sjx->created_at_str = DateTool::formateData($sjx->created_at, 1);
            $sjx->doctor = DoctorManager::getDoctorById($sjx->doctor_id);
        }
        return view('admin.sjx.index', ['admin' => $admin, 'datas' => $sjxs]);
    }

    //新建或编辑数据项-get
    public function edit(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $sjx = new SJX();

        if (array_key_exists('id', $data)) {
            $sjx = SJXManager::getSJXById($data['id']);
        }

        return view('admin.sjx.edit', ['admin' => $admin, 'data' => $sjx]);
    }

    //新建或编辑数据项->post
    public function editPost(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $sjx = new SJX();
        //存在id是保存
        if (array_key_exists('id', $data)) {
            $sjx = SJXManager::getSJXById($data['id']);
        }
        $sjx = SJXManager::setSJX($sjx, $data);
        $sjx->save();
        return redirect('/admin/sjx/index');
    }
}