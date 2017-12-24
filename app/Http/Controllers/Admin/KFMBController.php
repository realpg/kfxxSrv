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
use App\Components\KFMBManager;
use App\Components\QNManager;
use App\Components\SJXManager;
use App\Components\XJManager;
use App\Models\AD;
use App\Models\Admin;
use App\Models\KFMB;
use App\Models\KFMBJH;
use App\Models\KFMBJHSJ;
use App\Models\TWStep;
use App\Models\XJ;
use App\Models\XJStep;
use App\Models\XJType;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class KFMBController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $kfmbs = KFMBManager::getKFMBList("all");
        foreach ($kfmbs as $kfmb) {
            //补充医生信息
            $kfmb->doctor = DoctorManager::getDoctorById($kfmb->doctor_id);
            $kfmb->created_at_str = DateTool::formateData($kfmb->created_at, 1);
        }
        //获取所有宣教列表
        $xjs = XJManager::getAllXJs("all");       //"all"代表全部
        foreach ($xjs as $xj) {
            if (strlen($xj->title) > 25) {
                $xj->title = mb_substr($xj->title, 0, 25, 'utf-8') . "...";
            }
        }
        //进行宣教信息的截取
        return view('admin.kfmb.index', ['admin' => $admin, 'datas' => $kfmbs, 'xjs' => $xjs]);
    }

    //删除康复模板
    public function del(Request $request, $id)
    {
        //康复模板id非数字
        if (!is_numeric($id)) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数广告id$id']);
        }
        $kfmb = KFMB::find($id);
        $kfmb->delete();
        return redirect('/admin/kfmb/index');
    }

    //设置状态
    public function setStatus(Request $request, $id)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'opt' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        //opt必须为0或者1
        $opt = $data['opt'];
        if (!($opt == '0' || $opt == '1')) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数,opt必须为0或者1，现值为' . $opt]);
        }
        $kfmb = KFMB::where('id', '=', $id)->first();
        $kfmb->status = $opt;
        $kfmb->save();
        return redirect('/admin/kfmb/index');
    }


    //编辑康复模板-get
    public function edit(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $kfmb = new KFMB();
        //获取康复模板
        if (array_key_exists('id', $data)) {
            $kfmb = KFMBManager::getKFMBById($data['id']);
        }
        return view('admin.kfmb.edit', ['admin' => $admin, 'data' => $kfmb]);
    }


    //编辑康复模板-post
    public function editPost(Request $request)
    {
        $data = $request->all();
        $kfmb = new KFMB();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $kfmb = KFMBManager::getKFMBById($data['id']);
        }
        $kfmb = KFMBManager::setKFMB($kfmb, $data);
        $kfmb->save();
        return redirect('/admin/kfmb/index');
    }

    //康复计划
    public function setJHPost(Request $request, $kfmb_id)
    {
        $data = $request->all();
        $kfmbjh = new KFMBJH();
        if (array_key_exists('jh_id', $data) && $data['jh_id'] != null) {
            $kfmbjh = KFMBManager::getKFMBJHById($data['jh_id']);
        }
        $kfmbjh = KFMBManager::setKFMBJH($kfmbjh, $data);
        $kfmbjh->save();
        return redirect('/admin/kfmb/setJH/' . $kfmbjh->kfmb_id);
    }

    //删除康复计划
    public function delJH(Request $request, $id)
    {
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数宣教id$id']);
        }
        $kfmbjh = KFMBManager::getKFMBJHById($id);
        $kfmbjh->delete();
        return redirect('/admin/kfmb/setJH/' . $kfmbjh->kfmb_id);
    }

    //设置康复计划
    public function setJH(Request $request, $kfmb_id)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $kfmb = KFMBManager::getKFMBById($kfmb_id);
        $kfmb = KFMBManager::getKFMBInfoByLevel($kfmb, 5);
        $kfmb->created_at_str = DateTool::formateData($kfmb->created_at, 1);
//        dd($kfmb);
        //获取单条康复计划
        //如果有jh_id说明是编辑
        $jh = new KFMBJH();
        if (array_key_exists('jh_id', $data) && $data['jh_id'] != null) {
            $jh = KFMBManager::getKFMBJHById($data['jh_id']);
        }
        //获取全部宣教信息
        $all_xjs = XJManager::getAllXJs('all');
        $all_sjxs = SJXManager::getSJXs();
//        dd($kfmb);
        return view('admin.kfmb.editJH', ['admin' => $admin, 'data' => $kfmb, 'jh' => $jh, 'all_xjs' => $all_xjs, 'all_sjxs' => $all_sjxs]);
    }


    //删除康复计划管理的宣教
    public function delJHXJ(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'jh_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        $kfmbjh = KFMBManager::getKFMBJHById($data['jh_id']);
        $kfmbjh->xj_ids = null;
        $kfmbjh->save();
        return redirect('/admin/kfmb/setJH/' . $kfmbjh->kfmb_id);
    }

    //设置采集数据
    public function setCJSJPost(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $kfmbjhsj = new KFMBJHSJ();
        if (array_key_exists('jhsj_id', $data) && $data['jhsj_id'] != null) {
            $kfmbjhsj = KFMBManager::getJHSJById($data['jhsj_id']);
        }
        $kfmbjhsj = KFMBManager::setKFMBJHSJ($kfmbjhsj, $data);
//        dd($kfmbjhsj);
        $kfmbjhsj->save();
        $kfmbjh = KFMBManager::getKFMBJHById($data['mbjh_id']);
        return redirect('/admin/kfmb/setJH/' . $kfmbjh->kfmb_id);
    }

    //删除采集数据
    public function delCJSJ(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'jhsj_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        $kfmbjhsj = KFMBManager::getJHSJById($data['jhsj_id']);
        $kfmbjh = KFMBManager::getKFMBJHById($kfmbjhsj->mbjh_id);
        $kfmbjhsj->delete();
        return redirect('/admin/kfmb/setJH/' . $kfmbjh->kfmb_id);
    }
}