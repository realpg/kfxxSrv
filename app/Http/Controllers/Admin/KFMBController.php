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
use App\Components\Utils;
use App\Components\XJManager;
use App\Http\Controllers\ApiResponse;
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
        $xjs = XJManager::getAllXJs("all");       //"all"代表全部，用于新建康复模板时关联宣教使用
        foreach ($xjs as $xj) {
            //进行宣教信息的截取
            if (strlen($xj->title) > 25) {
                $xj->title = mb_substr($xj->title, 0, 25, 'utf-8') . "...";
            }
        }
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
	
	public function editKFMB(Request $request)
	{
		//获取数据，要求ajax设置Content-Type为application/json; charset=utf-8
		$data = $request->all();
		//新建/编辑康复模板
		$kfmb = new KFMB();
		if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
			$kfmb = KFMBManager::getKFMBById($data['id']);
		}
		$kfmb = KFMBManager::setKFMB($kfmb, $data);
		//保存康复模板
		$kfmb->save();
		
		//编辑康复模板计划
		//删除已经无用的康复计划
		$ori_jhs = KFMBManager::getJHListByKFMBId($kfmb->id);
		//删除原有康复计划以及计划下关联的数据
		foreach ($ori_jhs as $ori_jh) {
			$ori_jhsjs = KFMBManager::getJHSJByJHId($ori_jh->id);
			$ori_jh->delete();
			foreach ($ori_jhsjs as $ori_jhsj) {
				$ori_jhsj->delete();
			}
		}
		//新建康复计划及数据
		$new_jhs = $data['jhs'];
		foreach ($new_jhs as $new_jh) {
			$jh = new KFMBJH();
			$jh = KFMBManager::setKFMBJH($jh, $new_jh);
			$jh->kfmb_id = $kfmb->id;
			$jh->save();
			//新建康复计划采集数据
			$new_jhsjs = $new_jh['jhsjs'];
			foreach ($new_jhsjs as $new_jhsj) {
				$jhsj = new KFMBJHSJ();
				$jhsj = KFMBManager::setKFMBJHSJ($jhsj, $new_jhsj);
				$jhsj->mbjh_id = $jh->id;
				$jhsj->save();
			}
		}
		//保存康复模板
		$kfmb = KFMBManager::getKFMBInfoByLevel($kfmb, "03");
		return ApiResponse::makeResponse(true, $kfmb, ApiResponse::SUCCESS_CODE);
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


    //设置康复计划
    public function editJH(Request $request)
    {
        $admin = $request->session()->get('admin');
        $sjxs = SJXManager::getSJXs();
        return view('admin.kfmb.editJH', ['admin' => $admin, 'sjxs' => $sjxs]);
    }


}