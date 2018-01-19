<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin;

use App\Components\SurgeryManager;
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\HposManager;
use App\Components\QNManager;
use App\Components\XJManager;
use App\Http\Controllers\ApiResponse;
use App\Libs\CommonUtils;
use App\Models\Surgery;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class SurgeryController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $surgerys = SurgeryManager::getSurgeryPaginate();
        foreach ($surgerys as $surgery) {
            $surgery->created_at_str = DateTool::formateData($surgery->created_at, 1);
            $surgery->doctor = DoctorManager::getDoctorById($surgery->doctor_id);
            $surgery->hpos = HposManager::getHPosById($surgery->hpos_id);
        }
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        //生成患处位置列表
        $hposs = HposManager::getHPosList();
        return view('admin.surgery.index', ['admin' => $admin, 'datas' => $surgerys, 'upload_token' => $upload_token, 'hposs' => $hposs]);
    }

    //删除患处位置
    public function del(Request $request, $id)
    {
        //患处位置id非数字
        if (!is_numeric($id)) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数患处id$id']);
        }
        $surgery = Surgery::find($id);
        $surgery->delete();
        return redirect('/admin/surgery/index');
    }

    //新建或编辑患处位置-get
    public function edit(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $surgery = new Surgery();
        if (array_key_exists('id', $data)) {
            $surgery = Surgery::find($data['id']);
        }
        $admin = $request->session()->get('admin');
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return ApiResponse::makeResponse(true, $surgery, ApiResponse::SUCCESS_CODE);
    }

    //新建或编辑患处位置->post
    public function editPost(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $surgery = new Surgery();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $surgery = SurgeryManager::getSurgeryById($data['id']);
        }
        $surgery = SurgeryManager::setSurgery($surgery, $data);
        $surgery->save();
        return redirect('/admin/surgery/index');
    }

}